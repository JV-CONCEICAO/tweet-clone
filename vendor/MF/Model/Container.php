<?php 
    namespace MF\Model;

    use App\Connection;

    class Container {
        public static function getMOdel($model) {
            $class = "\\App\\Models\\".ucfirst($model);
            //Instancia de conexão
            $conexao = Connection::getDB();
            return new $class($conexao);
        }
    }

?>