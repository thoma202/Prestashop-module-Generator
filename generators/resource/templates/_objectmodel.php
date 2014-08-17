<?php

class <%= modelName %> extends ObjectModel
{

	public <% _.each(attributes, function(attribute, key, list){ %> $<%= attribute.name %>,<% }); %> $date_add, $date_upd;

	static public $definition = array(
		'table' => '<%= table %>',
		'primary' => 'id_<%= table %>', 
		'multilang' => <%= isMultiLang %>,
		'fields' => array(<% _.each(attributes, function(attribute){ %>
		 	'<%= attribute.name %>' => array('type' => '<%= attribute.type %>', 'validate' => '<%= attribute.validationRule%>'<% if(attribute.isLang){%>, 'lang' => true<%}%>),<% }); %>
		 	'date_add' => array('type' => 'TYPE_DATE', 'validate' => 'isDateFormat'),
		 	'date_upd' => array('type' => 'TYPE_DATE', 'validate' => 'isDateFormat'),
		),
	);

	static public function getIds()
	{
		$sql = "SELECT `".self::$definition['primary']."` FROM " ._DB_PREFIX_.self::$definition['table']."";
		$objsIDs = Db::getInstance()->ExecuteS($sql);
		return $objsIDs;
	}

	static public function install()
	{
		// Create Category Table in Database
		$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'` (
				  	`'.self::$definition['primary'].'` int(16) NOT NULL AUTO_INCREMENT,
				 	<% _.each(attributes, function(attribute){ if(!attribute.isLang) { %>`<%= attribute.name %>` <%= attribute.sqltype %>,
				 	<% } }); %>date_add datetime NOT NULL,
					date_upd datetime NOT NULL,
					UNIQUE(`'.self::$definition['primary'].'`),
				  	PRIMARY KEY  ('.self::$definition['primary'].')
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
		<% if (isMultiLang){ %>
		$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$definition['table'].'_lang` (
			  	`'.self::$definition['primary'].'` int(16) NOT NULL,
			  	`id_lang` int(16) NOT NULL,
			 	<% _.each(attributes, function(attribute){ %> <% if(attribute.isLang) { %> `<%= attribute.name %>` <%= attribute.sqltype %>,
	            <% } %><% }); %>PRIMARY KEY  ('.self::$definition['primary'].', id_lang)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
		<% } %>

		foreach ($sql as $q) 
			Db::getInstance()->Execute($q);	
	}

	static public function uninstall()
	{
		// Create Category Table in Database
		$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.self::$definition['table'].'`';
		<% if (isMultiLang){ %>
			$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.self::$definition['table'].'_lang`';
		<% } %>

		foreach ($sql as $q) 
			Db::getInstance()->Execute($q);
	}

}