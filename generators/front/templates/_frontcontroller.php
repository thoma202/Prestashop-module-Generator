<?php
/**
 * Link to access the controller : $link->getModuleLink('<%= moduleName %>', '<%= controllerName %>')
 */

class <%= moduleName %><%= controllerName %>ModuleFrontController extends ModuleFrontController
{
	public function __construct()
	{
		$this->display_column_left = false;
		$this->display_column_right = false;
		parent::__construct();
		$this->context = Context::getContext();

	}

	public function postProcess()
	{
		parent::postProcess();
	}

	public function init()
	{
		parent::init();
	}

	public function initContent()
	{
		parent::initContent();

		// Init smarty content and set template to display

		$this->setTemplate('<%= controllerName %>.tpl');
	}

	public function setMedia()
	{
		parent::setMedia();		
		$this->addCSS(__PS_BASE_URI__.'modules/<%= moduleName %>/views/css/<%= controllerName %>.css');
	}
}
