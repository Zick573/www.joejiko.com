<?php

namespace Shared
{
    use Framework\Events as Events;
    use Framework\Registry as Registry;

    class Controller extends \Framework\Controller
    {
        /**
        * @readwrite
        */
        protected $_user;

        /**
        * @protected
        */
        public function _admin()
        {
            if (!$this->user->admin)
            {
                throw new \Framework\Router\Exception\Controller("You're not special enough to see this page.");
            }
        }

        /**
         * @protected
         */
        public function _beta()
        {
            // team jiko
            if(!$this->user->beta || !$this->user->admin)
            {
                throw new \Framework\Router\Exception\Controller("#TeamJiko");
            }
        }

        /**
        * @protected
        */
        public function _secure()
        {
            $user = $this->getUser();
            if (!$user)
            {
                $controller = Registry::get("controller");
                $session = Registry::get("session");
                $session->set("continue", $_SERVER[REQUEST_URI]);
                $this->redirect('/user/login');
                exit();
            }
        }

        public static function redirect($url)
        {
            header("Location: {$url}");
            exit();
        }

        public function setUser($user=false)
        {
            $session = Registry::get("session");

            if(array_key_exists("jjcom_user", $_COOKIE))
            {
                // get info from cookie
                $saved = explode("|", $_COOKIE["jjcom_user"]);
            }

            if ($user)
            {
                // user from session
                $session->set("user", $user->id);
            }
            elseif($saved)
            {
                // remembered from cookie
                // lookup user information
                $user = \User::first(array(
                    "id = ?" => $saved[0]
                ));

                $session->set("user", $saved[0]);
            }
            else
            {
                // no user
                $session->erase("user");
            }

            $this->_user = $user;

            return $this;
        }


        public function __construct($options = array())
        {
            parent::__construct($options);

            $database = \Framework\Registry::get("database");
            $database->connect();

            // schedule: load user from session
            Events::add("framework.router.beforehooks.before", function($name, $parameters) {

                $session = Registry::get("session");
                $controller = Registry::get("controller");

                $user = $session->get("user");

                if ($user)
                {
                    $controller->user = \User::first(array(
                        "id = ?" => $user
                    ));
                }

            });

            // schedule: save user to session
            Events::add("framework.router.afterhooks.after", function($name, $parameters) {
                $session = Registry::get("session");
                $controller = Registry::get("controller");

                if ($controller->user)
                {
                    $session->set("user", $controller->user->id);
                }
            });

            // schedule disconnect from database
            Events::add("framework.controller.destruct.after", function($name) {
                $database = Registry::get("database");
                $database->disconnect();

                // $databaseX = Registry::get("databaseX");
                // $databaseX->disconnect();
            });
        }

        public function render()
        {
            if ($this->getUser())
            {
                $user = array(
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email
                );
                $this->smarty->assign("user", $user);
            }

            parent::render();
        }
    }
}