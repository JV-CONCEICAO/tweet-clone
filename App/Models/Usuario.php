<?php

    namespace App\Models;
    use MF\Model\Model;
use PDO;

    class Usuario extends Model {
        private $id;
        private $nome;
        private $email;
        private $senha;

        //getters e setters

        public function __get($attr) {
            return $this -> $attr;
        }   

        public function __set($atributo, $value) {
            $this-> $atributo = $value;
        }

        // Salvar
        public function salvar() {
            $query = "INSERT INTO usuarios(nome, email, senha) VALUES (?, ?, ?)";
            $stmt = $this -> db -> prepare($query);
            $stmt -> bindValue(1, $this -> __get('nome'));
            $stmt -> bindValue(2, $this -> __get('email'));
            $stmt -> bindValue(3, $this -> __get('senha')); //md5 -> hash 32 caracteres
            $stmt -> execute();

            return $this;
        }

        //Validar se um cadastro pode ser feito
        public function validarCadastro() {
            $valido = true;

            if(strlen($this -> __get('nome')) < 3) {
                $valido = false;
            }

            if(strlen($this -> __get('email')) < 3) {
                $valido = false;
            }
           if(strlen($this -> __get('senha')) < 3) {
                $valido = false;
            }


            return $valido;
        }

        //recuperar um usuario por email
        public function getUserPorEmail() {
            $query = "SELECT nome, email FROM usuarios where email = :email";
            $stmt = $this ->db -> prepare($query);
            $stmt -> bindValue(":email", $this -> __get('email'));
            $stmt -> execute();

            return $stmt -> fetchAll(\PDO::FETCH_ASSOC);
        }

        public function autenticar() {
            $query = "select id, nome, email from usuarios where email = :email and senha = :senha";
            $stmt = $this -> db -> prepare($query);
            $stmt -> bindValue(':email', $this -> __get('email'));
            $stmt -> bindValue(':senha', $this -> __get('senha'));
            $stmt -> execute();

            $usuario = $stmt -> fetch(\PDO::FETCH_ASSOC);

            if($usuario && $usuario['id'] != '' && $usuario['nome'] != '') {
                $this -> __set('id', $usuario['id']);
                $this -> __set('nome', $usuario['nome']);
            }

            return $this;
        }

        public function getAll() {
            $query = "
            SELECT
             u.id, u.nome, u.email, (
             select count(*) 
             from 
              usuarios_seguidores as us
             where
              us.id_usuario = :id_user and us.id_usuario_seguindo = u.id
             ) as seguindo_sn
            from
              usuarios as u
            where u.nome like :nome and u.id != :id_user";
            $stmt = $this -> db -> prepare($query);
            $stmt -> bindValue(":nome", '%'.$this ->__get('nome').'%');
            $stmt -> bindValue(":id_user", $this -> __get('id'));
            $stmt -> execute();

            return $stmt -> fetchAll(\PDO::FETCH_ASSOC);
        }

        public function seguirUsuario($id_user_seguindo) {
            $query = "INSERT INTO usuarios_seguidores(id_usuario,id_usuario_seguindo) VALUES(:id_usuario, :id_user_seguindo)";

            $stmt = $this -> db -> prepare($query);
            $stmt -> bindValue(':id_usuario', $this -> __get('id'));
            $stmt -> bindValue(':id_user_seguindo', $id_user_seguindo);
            $stmt -> execute();

            return true;
        }

        public function deixarSeguirUsuario($id_user_seguindo) {
            $query = "DELETE FROM usuarios_seguidores where id_usuario = :id_usuario and id_usuario_seguindo = :id_user_seguindo";
            $stmt = $this -> db -> prepare($query); 
            $stmt -> bindValue(':id_usuario', $this -> __get('id'));
            $stmt -> bindValue(':id_user_seguindo', $id_user_seguindo);
            $stmt -> execute();

            return true;
        }
    }

?>