<?php
    class Router {

        /**
         *   Allowed URL patterns
         * @var Array
         */
        private $routes;

        /**
         *   Allowed prefixs for URLs
         * @var String
         */
        private $prefix;

        /**
         *   Requested URL
         * @var String
         */
        private $url;

        /**
         *   Run the router
         */
        public function run() {
            $this->set_url();
            $this->dispatch();
        }

        /**
         *   Set a new route
         */
        public function set($route) {

            if ( is_array($route) ) {
                foreach ( $route as $regex => $control ) {
                    $this->routes[$regex] = $control;
                }
            } else {
                $default_routes = $this->generate_default_routes($route);
                foreach ( $default_routes as $regex => $control ) {
                    $this->routes[$regex] = $control;
                }
            }

        }

        /**
         *   get routes
         */
        public function get_routes() {

            return $this->routes;

        }

        /**
         *   Default routes
         */
        public function generate_default_routes($object) {
            ucfirst($object);
            return $routes = array(
                '~^\/'.$object.'s\/$~'               => $object.'sController@list_all',
                '~^\/'.$object.'\/(\d+)\/$~'         => $object.'sController@display',
                '~^\/admin\/'.$object.'s\/$~'               => $object.'sController@list_all',
                '~^\/admin\/'.$object.'\/new\/$~'           => $object.'sController@create',
                '~^\/admin\/'.$object.'\/(\d+)\/edit\/$~'   => $object.'sController@edit',
                '~^\/admin\/'.$object.'\/(\d+)\/delete\/$~' => $object.'sController@delete',
            );
        }

        /**
         *   set routes prefix
         */
        public function set_prefix($prefix) {

            foreach ( $prefix as $regex => $control ) {
                $this->prefix[$regex] = $control;
            }

        }

        /**
         *   get requested url
         */
        public function set_url() {

            $url = isset($_GET['uri']) ? '/' . $_GET['uri'] . '/' : '';
            $this->url = urldecode(trim($url));

        }

        /**
         *   get requested url
         */
        public function get_url() {

            return $this->url;

        }

        /**
         *   Call the correct controller class and method
         */
        public function dispatch() {

            //find if the url match any specified route
            $args = $this->find_match($this->url, $this->routes);

            //set data
            $action = isset($args['action']) ? $args['action'] : '';
            $id = isset($args['id']) ? $args['id'] : '';
            $admin = isset($args['admin']) ? $args['admin'] : '';

            if ( !empty($action) ) {

                if ( preg_match('/@/', $action) ) {

                    list($object, $method, $params) = $this->get_call_params($action, $id, $admin);
                    call_user_func_array(array($object,$method), $params);

                } else {
                    echo "invalid method supplied for the route: " . $this->url;
                }

            } else {
                echo "invalid route: " . $this->url;
            }

        }

        /**
         *   find if the requested URL match any of the allowed route patterns
         */
        public function find_match($uri, $routes) {

            
            $args = array();

            foreach ( $routes as $regex => $controller) {
                
                if ( preg_match($regex, $uri) ) {
                    $args['action'] = $controller;

                    if ( preg_match('/([a-zA-Z]+)\/(\d+)/', $uri) ) {
                        preg_match('/(\d+)/', $uri, $matches);
                        $args['id'] = $matches[0];
                    }

                    if ( preg_match('~^\/admin/~', $uri) ) {
                        preg_match('~^\/admin/~', $uri, $matches);
                        $args['admin'] = $matches[0];
                    }
                }
            }

            return $args;

        }

        /**
         *   Prepare data for correct dispatching
         */
        public function get_call_params($action, $id, $admin) {

            //split action into controller and method
            $actions = explode('@', $action);
            list($controller, $method) = $actions;

            if ( !empty($admin) ) {
                $prefix = $this->prefix['admin'];
            } else {
                $prefix = $this->prefix['default'];
            }

            $controller = ucfirst($controller);
            //instantiate the controller object
            //instantiate the controller object
           $class = $_SERVER['DOCUMENT_ROOT'].'/server/controllers/' . $controller;
           $class ='\Controllers\\' . $controller;
           $class =  $controller;
            $object = new $class($action, $prefix);

            //set params array
            $params = array();
            $params[] = $id;

            return array($object, $method, $params);

        }


    }


