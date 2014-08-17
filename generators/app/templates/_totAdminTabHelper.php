<?php 

class <%= modulePrefix %>TotAdminTabHelper
{
	/**
	 * Function to delete admin tabs from a menu with the module name
	 * @param  string $name name of the module to delete
	 * @return void       
	 */
	public static function deleteAdminTabs($name)
	{
		Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . 'tab_lang WHERE id_tab IN (SELECT id_tab FROM ' . _DB_PREFIX_ . 'tab WHERE module = "'.pSQL($name).'")');
		Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . 'tab WHERE module = "' .pSQL($name).'"');
	}

	/**
	 * Add admin tabs in the menu
	 * @param Array $tabs 
	 *        Array[
	 *        	Array[
	 *        		id_parent => 0 || void
	 *        		className => Controller to link to
	 *        		module => modulename to easily delete when uninstalling
	 *        		name => name to display
	 *        		position => position
	 *        	]
	 *        ]
	 */
	public static function addAdminTab($tab)
	{
		$id_parent = isset($tab['id_parent']) ? $tab['id_parent'] : self::getAdminTabIDByClassName($tab['classNameParent']);

		/* define data array for the tab  */
		$data = array(
					  'id_tab' => '', 
					  'id_parent' => $id_parent, 
					  'class_name' => $tab['className'], 
					  'module' => $tab['module'], 
					  'position' => $tab['position'],  
					  'active' => 1 
					 );

		/* Insert the data to the tab table*/
		$res = Db::getInstance()->insert('tab', $data);
		//Get last insert id from db which will be the new tab id
		$id_tab = Db::getInstance()->Insert_ID();

	   //Define tab multi language data
		$data_lang = array(
						 'id_tab' => $id_tab, 
						 'id_lang' => Configuration::get('PS_LANG_DEFAULT'),
						 'name' => $tab['name'], 
						 );

		// Now insert the tab lang data
		$res = Db::getInstance()->insert('tab_lang', $data_lang);

		return $id_tab;		
	}



	/**
	 * Get the Id of a tab by its class name
	 * @param  string $name classname to get the ID
	 * @return int       id_tab
	 */
	public static function getAdminTabIDByClassName($name)
	{
		return Db::getInstance()->getValue('SELECT id_tab FROM ' . _DB_PREFIX_ . 'tab WHERE class_name = "'.pSQL($name).'"');
	}
}