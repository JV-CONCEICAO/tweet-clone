<?php

    namespace App\Models;
    use MF\Model\Model;

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
    }

?>