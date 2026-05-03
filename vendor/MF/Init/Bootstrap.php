<?php 
    // Nesse caso o nome Bootstrap é um termo para scripts de inicialização
    namespace MF\Init;

    abstract class Bootstrap {
        private $routes;

        abstract protected function initRoutes();

        public function __construct() {
            $this -> initRoutes();
            $this -> run($this -> getUrl());
        }

        public function getRoutes() {
            return $this -> routes;
        }

        public function setRoutes(array $rout) {
            $this -> routes = $rout;
        }

        protected function run($url) {
            foreach($this -> getRoutes() as $path => $routes) {
                if($url == $routes['route']) {
                    $class = "App\\Controllers\\".ucfirst($routes['controller']);
                    $controller = new $class();
                    
                    $action = $routes['action'];
                    $controller -> $action();
                }
            }
        }

        protected function getUrl() {
            //A função parse_url recebe uma url interpreta essa url e retorna os seus 
            //respectivos componentes, ou seja retorna um array detalhando quais são
            //os componentes da url
            // com PHP_URL_PATH ele faz com que retorne apenas a string relativa ao path
            // ao invez de Array([path] => / )
            //retorna apenas /
            return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        }
    }
?>