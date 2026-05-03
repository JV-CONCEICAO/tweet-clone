<?php 
    namespace App;

    use PDO;
    use PDOException;

    class Connection {

        public static function getDB() {

            try {
                $dsn = 'mysql:host=localhost;dbname=twiter_clone;charset=utf8';
                $usuario = 'root';
                $senha = '';
                $conexao = new PDO($dsn, $usuario, $senha);

                return $conexao;

            } catch (PDOException $erro) {
                echo $erro -> getMessage();
            }

        }
    }
?>