<?php

namespace Framework
{
    use Framework\Base as Base;
    use Framework\Assets as Assets;
    use Framework\Events as Events;
    use Framework\Registry as Registry;
    use Framework\Controller\Exception as Exception;
    use Shared\Context as Context;

    class Controller extends Base
    {
        /**
        * @read
        */
        protected $_name;

        /**
        * @readwrite
        */
        protected $_parameters;

		/**
		* @readwrite
		*/
		protected $_smarty;

        /**
        * @readwrite
        */
        protected $_layoutView;

        /**
        * @readwrite
        */
        protected $_actionView;

		/**
		* @readwrite
		*/
		protected $_assets;

        /**
         * @readwrite
         */
        protected $_context;

        /**
        * @readwrite
        */
        protected $_willRenderLayoutView = true;

        /**
        * @readwrite
        */
        protected $_willRenderActionView = true;

				/**
        * @readwrite
        */
        protected $_willRenderJSON = false;

        /**
        * @readwrite
        */
        protected $_defaultPath = "application/views";

        /**
        * @readwrite
        */
        protected $_defaultLayout = "layouts/default/layout";

        /**
        * @readwrite
        */
        protected $_defaultExtension = "tpl";

		/**
		* @readwrite
		*/
        protected $_defaultAssets = true;

        /**
        * @readwrite
        */
        protected $_defaultContentType = "text/html";

        protected function getName()
        {
            if (empty($this->_name))
            {
                $this->_name = get_class($this);
            }
            return $this->_name;
        }

        protected function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }

        public function __construct($options = array())
        {
            parent::__construct($options);

            Events::fire("framework.controller.construct.before", array($this->name));
            $session = Registry::get("session");
            $continue = $session->get('continue');
			$this->smarty = Registry::get("smarty");
			$this->smarty->debugging = false;
            $this->smarty->assign(array(
                'controller' => array(
                    'continue' => $continue
                )
            ));
            $this->context = new Context();

            if($this->context->isMobile() || array_key_exists('mobile', $_GET))
            {
              // mobile device
              $this->defaultLayout = "layouts/default/mobile";
            }
            elseif ($this->context->isTablet()) {
              $this->defaultLayout = "layouts/default/tablet";
            }

			$router = Registry::get("router");

			/* set assets */
			$assets = new Assets();
			$this->_assets = $assets->set(array(
				'scripts' => array(
					'libraries/modernizr/modernizr' => 'head',
					'layouts/default/scripts' => 'head',
					$router->controller => 'footer'
				),
				'styles' => array(
					'layouts/default/styles' => 'all',
					$router->controller => 'all'
				)
			));

            Events::fire("framework.controller.construct.after", array($this->name));
        }

        public function render()
        {
            Events::fire("framework.controller.render.before", array($this->name));

            $router = Registry::get("router");
            $session = Registry::get('session');
            if($router->url !== "user/login")
            {
                $session->set('continue', $router->url);
            }
            $this->smarty->assign(array(
                'framework' => array(
                    'url' => $router->url
                )
            ));
			$this->smarty->assign(array(
				'scripts' => $this->assets->getScripts(),
				'styles' => $this->assets->getStyles()
			));

            if ($this->willRenderLayoutView)
            {
                $defaultPath = $this->defaultPath;
                $defaultLayout = $this->defaultLayout;
                $defaultExtension = $this->defaultExtension;

                $this->layoutView = APP_PATH."/{$defaultPath}/{$defaultLayout}.{$defaultExtension}";
            }

            if ($this->willRenderActionView)
            {

				$controller = $router->controller;

				// action template override
				if($this->smarty->getTemplateVars("action"))
				{
					$oaction = $this->smarty->getTemplateVars("action");
					if($this->smarty->templateExists(APP_PATH."/{$defaultPath}/{$oaction}.{$defaultExtension}"))
					{
						$this->actionView = APP_PATH."/{$defaultPath}/{$oaction}.{$defaultExtension}";
					}
					else
					{
						$action = $router->action;
						$this->actionView = APP_PATH."/{$defaultPath}/{$controller}/{$action}.{$defaultExtension}";
					}
				}
				else
				{
					$action = $router->action;
					$this->actionView = APP_PATH."/{$defaultPath}/{$controller}/{$action}.{$defaultExtension}";
				}

				if($this->smarty->templateExists($this->actionView))
				{

					$actionTemplate = $this->smarty->fetch($this->actionView);

				}
				else
				{

					// missing action template
					$this->smarty->assign("message", "action template not found");
					$actionTemplate = $this->smarty->fetch("errors/404.tpl");
				}
			}
			else
			{

				// don't render action view
				$actionTemplate = '';
			}

			// layout view
			if($this->willRenderLayoutView)
			{
				if($this->smarty->templateExists($this->layoutView))
				{

					// assign action view
					$this->smarty->assign('action', $actionTemplate);
					$this->smarty->display($this->layoutView);

				}
				else
				{

					// missing layout template
					$this->smarty->assign("message", "layout template not found");
					$this->smarty->display("errors/404.tpl");
				}
			}
			else
			{
				if($this->willRenderActionView)
				{
					$this->smarty->display($actionTemplate);
				}

				if($this->smarty->getTemplateVars('layoutTpl'))
				{
                    $defaultPath = $this->defaultPath;
                    $defaultLayout = $this->defaultLayout;
                    $defaultExtension = $this->defaultExtension;
					$path = APP_PATH."/{$defaultPath}/".$this->smarty->getTemplateVars('layoutTpl').".{$defaultExtension}";
					if($this->smarty->templateExists($path))
					{
						$this->smarty->display($path);
					}
				}
			}
			// do nothing

            Events::fire("framework.controller.render.after", array($this->name));
        }

        public function __destruct()
        {
            Events::fire("framework.controller.destruct.before", array($this->name));
            $this->render();
            Events::fire("framework.controller.destruct.after", array($this->name));
        }
    }
}