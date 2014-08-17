<?php 

if( !defined ('_PS_VERSION_') ) {
	exit;
}
/* Import models */
$classes = scandir(dirname(__FILE__).'/classes');
foreach ($classes as $class) {
	if(is_file(dirname(__FILE__).'/classes/'.$class))
	{
		$class_name = substr($class, 0, -4);
		//Check if class_name is an existing Class or not
		if(!class_exists($class_name) && $class_name != 'index')
		{
			require_once(dirname(__FILE__) . '/classes/' . $class_name . '.php');
		}
	}
}

/* Import helpers */
$classes = scandir(dirname(__FILE__).'/classes/helper');
foreach ($classes as $class) {
	if(is_file(dirname(__FILE__).'/classes/helper/'.$class))
	{
		$class_name = substr($class, 0, -4);
		//Check if class_name is an existing Class or not
		if(!class_exists($class_name) && $class_name != 'index')
		{
			require_once(dirname(__FILE__) . '/classes/helper/' . $class_name . '.php');
		}
	}
}

class <%= moduleName %> extends Module {

	/**
	 * Module link in BO
	 * @var String
	 */
	private $_link;

	/**
	 * Constructor of module
	 */
	public function __construct()
	{

		$this->name = '<%= moduleNameLower %>';
		$this->tab = '<%= moduleTab %>';
		$this->version = '1.0';
		$this->author = '202-ecommerce';

		parent::__construct();

		$this->displayName = $this->l('<%= moduleDisplayName %>');
		$this->description = $this->l('<%= moduleDescription %>');

		// Check upgrade if enabled and installed
		if (self::isInstalled($this->name) && self::isEnabled($this->name))
		{
			$this->upgrade();
		}

	}

	############################################################################################################
	# Install / Upgrade / Uninstall
	############################################################################################################

	/**
	 * Module install
	 * @return boolean if install was successfull
	 */
	public function install()
	{
		// Install default
		if (!parent::install())
		{
			return false;
		}

		// Uninstall DataBase
		if (!$this->installSQL())
		{
			return false;
		}

		// Install tabs
		if(!$this->installTabs())
		{
			return false;
		}

		// Registration hook
		if (!$this->registrationHook())
		{
			return false;
		}


		return true;
	}

	/**
	 * Upgrade if necessary
	 */
	public function upgrade()
	{
		// Configuration name
		$cfgName = strtoupper($this->name . '_version');
		// Get latest version upgraded
		$version = Configuration::get($cfgName);
		// If the first time OR the latest version upgrade is older than this one
		if ($version === false || version_compare($version, $this->version, '<'))
		{
			// Upgrade in DataBase the new version
			Configuration::set($cfgName, $this->version);
		}
	}

	/**
	 * Module uninstall
	 * @return boolean if uninstall was successfull
	 */
	public function uninstall()
	{

		// Uninstall DataBase
		if (!$this->uninstallSQL())
		{
			return false;
		}

		// Delete tabs
		if(!$this->uninstallTabs())
		{
			return false;
		}

		// Uninstall default
		if (!parent::uninstall())
		{
			return false;
		}

		return true;
	}

	############################################################################################################
	# Tabs
	############################################################################################################

	/**
	 * Initialisation to install / uninstall
	 */
	private function installTabs() 
	{
		<% if(useMenu){ %>
		$menu_id = <%= existingMenu %>;
		<% } else if(needMenu) { %>
		$menu_id = <%= modulePrefix %>TotAdminTabHelper::addAdminTab(array(
			'id_parent' => 0,
			'className' => 'Admin<%= moduleName %>',
			'default_name' => '<%= moduleName %>',
			'name' => '<%= newMenu %>',
			'position' => 10, 
			'active' => true,
			'module' => $this->name,
		));
		<% } else { %>
		$menu_id = -1;
		<% } %>


		/*
		 * Install All Tabs directly via controller's install function
		 */
		$controllers = scandir(dirname(__FILE__).'/controllers/admin');
		foreach ($controllers as $controller) {
			if(is_file(dirname(__FILE__).'/controllers/admin/'.$controller) && $controller != 'index.php')
			{
				require_once(dirname(__FILE__).'/controllers/admin/'.$controller);
				$controller_name = substr($controller, 0, -4);
				//Check if class_name is an existing Class or not
				if(class_exists($controller_name))
				{
					if(method_exists($controller_name, 'install'))
						call_user_func(array($controller_name, 'install'), $menu_id, $this->name);
				}
			}
		}

		return true;

	}



	/**
	 * Delete tab
	 * @return  boolean if successfull
	 */
	public function uninstallTabs()
	{
		<%= modulePrefix %>TotAdminTabHelper::deleteAdminTabs($this->name);
		return true;
	}

	############################################################################################################
	# SQL
	############################################################################################################
	
	/**
	 * Install DataBase table
	 * @return boolean if install was successfull
	 */
	private function installSQL()
	{
		/*
		 * Install All Object Model SQL via install function
		 */
		$classes = scandir(dirname(__FILE__).'/classes');
		foreach ($classes as $class) {
			if(is_file(dirname(__FILE__).'/classes/'.$class))
			{
				$class_name = substr($class, 0, -4);
				//Check if class_name is an existing Class or not
				if(class_exists($class_name))
				{
					if(method_exists($class_name, 'install'))
						call_user_func(array($class_name, 'install'));
				}
			}
		}
		
		// Example :
		
		// $sql = array();
		// $sql[] = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "table` (   
		// 		`id_table` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,    
		// 		`name` VARCHAR(255) NOT NULL
		// ) ENGINE = " . _MYSQL_ENGINE_ . " ";
		
		// foreach ($sql as $q)
		// {
		// 	if (!DB::getInstance()->execute($q)) {
		// 		return false;
		// 	}
		
		// }
		
	
		return true;
	}

	/**
	 * Uninstall DataBase table
	 * @return boolean if install was successfull
	 */
	private function uninstallSQL()
	{
		/*
		 * Uninstall All Object Model SQL via install function
		 */
		$classes = scandir(dirname(__FILE__).'/classes');
		foreach ($classes as $class) {
			if(is_file(dirname(__FILE__).'/classes/'.$class))
			{
				$class_name = substr($class, 0, -4);
				//Check if class_name is an existing Class or not
				if(class_exists($class_name))
				{
					if(method_exists($class_name, 'uninstall'))
						call_user_func(array($class_name, 'uninstall'));
				}
			}
		}
		
		
		// Example :
		// $sql = "DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "table`";
		// if (!DB::getInstance()->execute($sql))
		// 	return false;
	
		return true;
	}

	############################################################################################################
	# Hook
	############################################################################################################

	/**
	 * [registrationHook description]
	 * @return [type] [description]
	 */
	private function registrationHook()
	{
		
		// Example :
		// if (!$this->registerHook('Name_of_hook'))
		// 	return false;
		
		return true;
	}

	############################################################################################################
	# Administration
	############################################################################################################

	/**
	 * Admin display
	 * @return String Display admin content
	 */
	public function getContent()
	{

		// Suffix to link
		$suffixLink = '&configure=' . $this->name . '&token=' . Tools::getValue('token') . '&tab_module=' . $this->tab . '&module_name=' . $this->name;

		// Base
		if (version_compare(_PS_VERSION_, '1.5', '>'))
			$this->_link = 'index.php?controller=' . Tools::getValue('controller') . $suffixLink;
		else
			$this->_link = 'index.php?tab=' . Tools::getValue('tab') . $suffixLink;

		$_html = '';

		return $this->displayBanner() . $_html;
	}

	/**
	 * Display 202-banner
	 * @return string Templates
	 */
	private function displayBanner() {

		$translations = array(
			'by' => $this->l('By'),
			'web' => $this->l('Web agency specialized in ecommerce web sites'),
			'addons' => $this->l('Our modules on addons'),
			'blog' => $this->l('News & advice on our blog')
		);

		$module = array(
			'description'  => $this->description,
			'name'         => $this->name,
			'displayName'  => $this->displayName,
			'_path'        => $this->_path
		);

		$datas = array(
			'module'       => $module,
			'translations' => $translations,
			'module_link'         => $this->_link,
			'lang'         => $this->context->language
		);

		if (version_compare(_PS_VERSION_, '1.5', '>')) {
			$smarty = $this->context->smarty;
		}
		else {
			global $smarty;
		}

		$smarty->assign($datas);

		return $this->display(__FILE__, '/views/templates/hook/banner.tpl');
	}

	/**
	 * Processing post in BO
	 */
	public function postProcess()
	{
		// 
	}

}

?>