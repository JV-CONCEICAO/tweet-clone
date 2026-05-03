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
    }

?>