<?php 

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class AppController extends Action{

        public function timeline() {
            $this -> validaAutenticacao();
            //Recuperação do tweets
            $tweet = Container::getMOdel('Tweet');

            //Passar o id do usuario para que não venha tweet de outros
            $tweet -> __set('id_usuario', $_SESSION['id']);

            $tweets = $tweet -> getAll();

            $this -> view -> tweets = $tweets;

            $this -> render('timeline');
        }

        public function tweet() {
            $this -> validaAutenticacao();
            $tweet = Container::getMOdel('Tweet');

            $tweet -> __set('tweet', $_POST['tweet']);
            $tweet -> __set('id_usuario', $_SESSION['id']);

            $tweet -> salvar();
            header('Location: /timeline');
        }

        public function validaAutenticacao() {
            session_start();
            if(!isset($_SESSION['id']) || $_SESSION['id']  == '' || !isset($_SESSION['nome']) || $_SESSION['nome']  == '' ) {
                header('Location: /?login=error');
            } else {
                return true;
            }
        }

        public function quemSeguir(){
            $this -> validaAutenticacao();

            $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';
            
            $usuarios = array();

            if($pesquisarPor != '') {
                $usuario = Container::getMOdel('Usuario');
                $usuario -> __set('nome', $pesquisarPor);
                $usuario -> __set('id', $_SESSION['id']);
                $usuarios = $usuario -> getAll();
            }
            $this -> view -> usuarios = $usuarios;
            $this -> render('quemSeguir');
        }


        public function acao() {
            $this -> validaAutenticacao();
            //acao
            $acao = isset($_GET['acao'])? $_GET['acao']: '';
            
            //id_usuario a ser seguido
            $id_user = isset($_GET['id_usuario']) ? $_GET['id_usuario']: '';

            //Usuario que quer seguir : Usuario logado atualmente
            $usuario = Container::getMOdel('Usuario');
            $usuario -> __set('id', $_SESSION['id']);

            if($acao == 'seguir') {
                $usuario -> seguirUsuario($id_user);
            } else if($acao == 'deixar_de_seguir') {
                $usuario -> deixarSeguirUsuario($id_user);
            }
            $pesquisarPor = $_GET['pesquisarPor'];
            header("Location: /quem_seguir?pesquisarPor=$pesquisarPor");
        }
    }

?>