<?php

class Admin<%= modelName %>Controller extends ModuleAdminController
{
	public function __construct()
	{
		$this->table = '<%= table %>';
		$this->className = '<%= modelName %>';
		$this->lang = <%= isMultiLang %>;

		$this->_select = null; //If needed you can add informations to select issued from other databases
		$this->_join = null; //Join the databases here
						
		$this->fields_list = array(
			<% _.each(attributes, function(attribute){ %>
	            '<%= attribute.name %>' => array(
	            	'title' => $this->l('<%= attribute.label %>'),<% if(attribute.alignedCenter){ %>
	          		'align' => 'center',<% } %>
	            	'width' => <%= attribute.width %>,
	            ),
	        <% }); %>
		);

		parent::__construct();

		<% _.each(attributes, function(attribute){ if(attribute.formType == 'file'){ %>
	    if(!is_dir(_PS_IMG_DIR_.'modules/'.$this->module->name.'/<%= attribute.name %>'))
			mkdir(_PS_IMG_DIR_.'modules/'.$this->module->name.'/<%= attribute.name %>', 0777, true);<% } }); %>

		$this->fieldImageSettings = array(<% _.each(attributes, function(attribute){ if(attribute.formType == 'file'){ %>
	    	array(
 				'name' => '<%= attribute.name %>',
 				'dir' => 'modules/'.$this->module->name.'/<%= attribute.name %>',
 			),<% } }); %>
 		);

	}

	public static function install($menu_id, $module_name)
	{
		<%= modulePrefix %>TotAdminTabHelper::addAdminTab(array(
			'id_parent' => $menu_id,
			'className' => 'Admin<%= modelName %>',
			'default_name' => '<%= modelName %>',
			'name' => '<%= modelName %>',
			'position' => 0, 
			'active' => true,
			'module' => $module_name,
		));
	}

	public function renderList()
	{
		$this->addRowAction('edit');
		$this->addRowAction('delete');
		return parent::renderList();
	}



	public function renderForm()
	{
		$this->fields_form = array(
			'legend' => array(
				'title' => '<%= modelName %>',
				'image' => '../img/admin/home.gif'
			),
			'input' => array(
				<% _.each(attributes, function(attribute){ %>array(
	            	'type' => '<%= attribute.formType %>', 
	            	'label' => $this->l('<%= attribute.label %>'),
	            	'name' => '<%= attribute.name %>',
	            	<% if(attribute.isLang) { %>'lang' => true,<% } %>
	            	<% if(attribute.formType == 'radio'){ %>
		            	'class' => 't',	<% if(attribute.values !== false) { %>
	        			'values' => array(
	        				<% _.each(attribute.values, function(value){ %>
	        				array(
	        					'label' => $this->l('<%= value.label %>'),
	        					'value' => '<%= value.value %>',
	        					'id' => '<%= attribute.name %>_<%= value.value %>'
	        				),<% }); %>
	        			),<% } %>
	            	<% } if(attribute.formType == 'textarea') { %>
	            		<% if(attribute.autoload_rte){ %>'autoload_rte' => true,
		            	'rows' => 5,
						'cols' => 40,<% } %>	            		
	            	<% } if(attribute.formType == 'categories') { %>
					'values' => array(
						'trads' => array(
							 'Root' => $this->getRootCategory(),
							 'selected' => $this->l('Selected'),
							 'Collapse All' => $this->l('Collapse All'),
							 'Expand All' => $this->l('Expand All'),
							 'Check All' => $this->l('Check All'),
							 'Uncheck All' => $this->l('Uncheck All'),
							 'search' => $this->l('Search'),
						),
						'selected_cat' => $this->getSelectedCategories('<%= attribute.name %>'),
						'input_name' => '<%= attribute.name %>',
						'use_radio' => true,
						'use_search' => false,
						'disabled_categories' => array(),
						'top_category' => Category::getTopCategory(),
						'use_context' => true,
					), 
					<% } if(attribute.formType == 'select') { %>
						'class' => 't',
						'options' => array(
							'optiongroup' => array(
								'label' => 'name',
								'query' => array(
									'Value' => array(
										'name' => 'OptionGroup', 
										'query' => array(
											array(
												'id' => '1',
												'name' => 'Value 1',
											),
											array(
												'id' => '2',
												'name' => 'Value 2',
											),
										),
									),
								),
							),
							'options' => array(
								'id' => 'id',
								'name' => 'name',
								'query' => 'query',
							),
						),

					<% } %>
	            ),<% }); %> 
			),
			'submit' => array(
				'title' => $this->l('   Save   '),
				'class' => 'button'
			)
		);

		return parent::renderForm();
	}

	private function getSelectedCategories($name)
	{
		return array($this->object->{$name});
	}

	private function getRootCategory()
	{
		$root_category = Category::getRootCategory();
		$root_category = array('id_category' => $root_category->id, 'name' => $root_category->name);
		return $root_category;
	}

}