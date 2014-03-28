<?php

    class Framework
    {
        private $instances = array();

        public function __get($class)
        {
            if (!isset($this->instances[$class]))
            {
                $this->instances[$class] = $this->get($class);
            }
            return $this->instances[$class];
        }

        public function get($class)
        {
           return new $class($this);
        }

    }

    abstract class ClassFactory
    {
        protected $framework;
        public function __construct(Framework $instance)
        {
            $this->framework = $instance;
        }
    }
    
    // --------------------------------------
    
    class Registry extends ClassFactory
    {
        private $store = array();

        public function __get($var)
        {
            return isset($this->store[$var])
                   ? $this->store[$var]
                   : null;
        }

        public function __set($var, $val)
        {
            $this->store[$var] = $val;
        }
    }

    class App extends ClassFactory
    {
        public function bootstrap(stdClass $config)
        {
            $this->framework->registry->config = $config;
            return $this;
        }

        public function start()
        {
            $this->framework->HelloWorld->print_base();
        }
    }

    class HelloWorld extends ClassFactory
    {
        public function print_base()
        {
            var_dump(
                $this->framework->registry->config->base_dir
            );
        }
    }

    // --------------------------------------

    $config = (object) array(
        'base_dir' => dirname(__FILE__).'/',
    );

    (new Framework)->app->bootstrap($config)->start();

