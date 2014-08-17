<?php

class Admin<%= moduleName %>ConfigurationController extends ModuleAdminController{
	
	public $fields_lang, $fields_html, $config_prefix;

	public function __construct() 
	{
		parent::__construct();
		$this->fields_lang = array(
			'text_field_price',
			'title_field_price',
		);
		$this->fields_html = array(
			'text_field_price',
		);
		$this->config_prefix = '<%= modulePrefix %>';
	}

	public function renderList()
	{
	 	return $this->renderForm();
	}

	public function renderForm()
	{
		$this->multiple_fieldsets = true;
		$attributesGroupArray = $this->getAttributesGroupsArray();

		$this->fields_value = array(
			'text_field_price' => $this->getConfigurationLang($this->config_prefix.'TEXT_FIELD_PRICE') , 
			'title_field_price' => $this->getConfigurationLang($this->config_prefix.'TITLE_FIELD_PRICE'),
			'id_product_advert' => Configuration::get($this->config_prefix.'ID_PRODUCT_ADVERT')  ,
			'id_product_abonnement' => Configuration::get($this->config_prefix.'ID_PRODUCT_ABONNEMENT') , 
			'duration_before_disabled' => Configuration::get($this->config_prefix.'DURATION_BEFORE_DISABLED') ,
			'duration_before_deleted' => Configuration::get($this->config_prefix.'DURATION_BEFORE_DELETED') ,			
			'duration_pro_seller' => Configuration::get($this->config_prefix.'DURATION_PRO_SELLER'),
			'id_attribute_price_range' => Configuration::get($this->config_prefix.'ID_ATTRIBUTE_PRICE_RANGE'),
			'id_default_category' => Configuration::get($this->config_prefix.'ID_DEFAULT_CATEGORY'),
			'number_of_pictures' => Configuration::get($this->config_prefix.'NUMBER_OF_PICTURES'),
			'option_pictures_more' => Configuration::get($this->config_prefix.'OPTION_PICTURES_MORE'),
			'option_pictures_yes' => Configuration::get($this->config_prefix.'OPTION_PICTURES_YES'),
			'group_for_proseller' => Configuration::get($this->config_prefix.'GROUP_FOR_PROSELLER'),
			'id_country_feature' => Configuration::get($this->config_prefix.'ID_COUNTRY_FEATURE'),
			'id_region_feature' => Configuration::get($this->config_prefix.'ID_REGION_FEATURE'),
			'price_attribute_modify' => Configuration::get($this->config_prefix.'PRICE_ATTRIBUTE_MODIFY'),
		);

		$this->fields_form = array(
			array(
				'form' => array(
					'legend' => array(
						'title' => $this->l('Product option'),
						'image' => '../img/admin/tab-customers.gif'
					),
					'input' => array(
						array(
							'type' => 'text', 
							'name' => 'id_product_advert', 
							'label' => $this->l('Product for a single advert'),
						),
						array(
							'type' => 'text', 
							'name' => 'id_product_abonnement', 
							'label' => $this->l('Product for membership'),				
						),
						array(
							'type' => 'text', 
							'name' => 'id_default_category', 
							'label' => $this->l('Category of new advert'),				
						),
						array(
							'type' => 'text', 
							'name' => 'number_of_pictures',
							'label' => $this->l('Number of pictures by default'),
						),
						array(
							'type' => 'select', 
							'label' => $this->l('Country of object'),
							'name' => 'id_country_feature',
							'required' => true,
							'class' => 't',
							'options' => array(
								'optiongroup' => array(
									'label' => 'name',
									'query' => array(
										'Attributes' => array(
											'name' => $this->l('Features'),
											'query' => TotFormHelper::getFeaturesPossibility(),
										),
									),
								),
								'options' => array(
									'id' => 'id',
									'name' => 'name',
									'query' => 'query',
								),
							),
						),
						array(
							'type' => 'select', 
							'label' => $this->l('Region of object'),
							'name' => 'id_region_feature',
							'required' => true,
							'class' => 't',
							'options' => array(
								'optiongroup' => array(
									'label' => 'name',
									'query' => array(
										'Attributes' => array(
											'name' => $this->l('Features'),
											'query' => TotFormHelper::getFeaturesPossibility(),
										),
									),
								),
								'options' => array(
									'id' => 'id',
									'name' => 'name',
									'query' => 'query',
								),
							),
						),
					),
					'submit' => array(
						'title' => $this->l('   Save   '),
						'class' => 'button'
					)

				)
			),
			array(
				'form' => array(
					'legend' => array(
						'title' => $this->l('Listing Options'),
						'image' => '../img/admin/tab-customers.gif'
					),
					'input' => array(
						array(
							'type' => 'select', 
							'label' => $this->l('Attribute for picture option'),
							'name' => 'option_pictures_yes',
							'class' => 't',
							'options' => array(
								'optiongroup' => array(
									'label' => 'name',
									'query' => $this->getAttributesArray(),
								),
								'options' => array(
									'id' => 'id',
									'name' => 'attributes',
									'query' => 'query',
								),
							),
						),
						
						array(
							'type' => 'text', 
							'label' => $this->l('Number of picture more'),
							'name' => 'option_pictures_more',
						)				
					),
					'submit' => array(
						'title' => $this->l('   Save   '),
						'class' => 'button'
					)
				)
			),
			array(
				'form' => array(
					'legend' => array(
						'title' => $this->l('Pro sellers options'),
						'image' => '../img/admin/tab-customers.gif'
					),
					'input' => array(
						array(
							'type' => 'select', 
							'name' => 'group_for_proseller', 
							'label' => $this->l('Group to send the pro sellers'),
							'class' => 't',
							'options' => array(
								'optiongroup' => array(
									'label' => 'name',
									'query' => array(
										'Groups' => array(
											'name' => $this->l('Groups'),
											'query' => TotFormHelper::getGroupPossibility(),
										),
									),
								),
								'options' => array(
									'id' => 'id',
									'name' => 'name',
									'query' => 'query',
								),
							),
						),
					),
					'submit' => array(
						'title' => $this->l('   Save   '),
						'class' => 'button'
					)

				)
			),
			array(
				'form' => array(
					'legend' => array(
						'title' => $this->l('Durations '),
						'image' => '../img/admin/tab-customers.gif'
					),
					'input' => array(
						array(
							'type' => 'text', 
							'name' => 'duration_before_disabled', 
							'label' => $this->l('Duration before the advert is disabled'),
							'desc' => $this->l('in weeks')
						),
						array(
							'type' => 'text', 
							'name' => 'duration_before_deleted',
							'label' => $this->l('Duration before the advert cannot be reposted for free'),
							'desc' => $this->l('in weeks (-1 if infinity)')				
						),
						array(
							'type' => 'text', 
							'name' => 'duration_pro_seller', 
							'label' => $this->l('Duration of a pro seller subscription'),
							'desc' => $this->l('in months')
						),
					),
					'submit' => array(
						'title' => $this->l('   Save   '),
						'class' => 'button'
					)
				)
			),
			array(
				'form' => array(
					'legend' => array(
						'title' => $this->l('Price Informations'),
						'image' => '../img/admin/tab-customers.gif'
					),
					'input' => array(
						array(
							'type' => 'text', 
							'lang' => true,
							'name' => 'title_field_price', 
							'label' => $this->l('Title for field price'),				
						),
						array(
							'type' => 'select', 
							'label' => $this->l('Which information will be related'),
							'name' => 'id_attribute_price_range',
							'required' => true,
							'class' => 't',
							'options' => array(
								'optiongroup' => array(
									'label' => 'name',
									'query' => array(
										'Attributes' => array(
											'name' => $this->l('Attributes'),
											'query' => TotFormHelper::getAttributesPossibility(),
										),
									),
								),
								'options' => array(
									'id' => 'id',
									'name' => 'name',
									'query' => 'query',
								),
							),
						),
						array(
							'type' => 'textarea', 
							'lang' => true,
							'name' => 'text_field_price', 
							'autoload_rte' => true,
							'label' => $this->l('Text for price explanation'),				
						),
						array(
							'name' => 'price_attribute_modify',
							'type' => 'select', 
							'label' => $this->l('Which price attribute to use during modification'),
							'required' => true,
							'class' => 't',
							'options' => array(
								'optiongroup' => array(
									'label' => 'name',
									'query' => array(
										'Attributes' => array(
											'name' => $this->l('Attributes'),
											'query' => TotFormHelper::getAttributeValuePossibility(Configuration::get('MP_ID_ATTRIBUTE_PRICE_RANGE')),
										),
									),
								),
								'options' => array(
									'id' => 'id',
									'name' => 'name',
									'query' => 'query',
								),
							),

						),
					),
					'submit' => array(
						'title' => $this->l('   Save   '),
						'class' => 'button',
					)

				)
			),
		);

		$parentRender = parent::renderForm();
		return $parentRender;
	}



	/**
	 * Call the right method for creating or updating object
	 *
	 * @return mixed
	 */
	public function processSave()
	{
		$fields_lang = array();
		$specifics = array();
		foreach ($this->fields_lang as $field) {
			$fields_lang[$field] = array();
		}

		foreach ($_POST as $key => $value) 
		{	
			if(!$this->isLang($key))	//If the key is not specified as a lang input
				Configuration::updateValue($this->config_prefix.strtoupper($key), Tools::getValue($key), in_array($key, $this->fields_html));	
		
			else //If the key is a lang, then we save it into the fields_lang array
				$fields_lang[$this->getKeyForFieldLang($key)][$this->getIdLangForFieldLang($key)] = $value;

		}

		foreach ($fields_lang as $key => $value) {
			Configuration::updateValue($this->config_prefix.strtoupper($key), $value, in_array($key, $this->fields_html));
		}


		foreach ($specifics as $config => $function) {
			$this->{$function}($config);
		}
	}

	public function isLang($key)
	{
		foreach ($this->fields_lang as $field) {
			if(preg_match('/'.$field.'/', $key))
				return true;
		}
	}

	private function getAttributesArray()
	{
		$attributesArray = array();
		$attributesByGroup = array();
		$context = Context::getContext();
		$attributes = Attribute::getAttributes((int)$context->employee->id_lang);

		foreach ($attributes as $attribute) {
			$attributesByGroup[$attribute['attribute_group']][] = array(
				'id' => $attribute['id_attribute'],
				'attributes' => $attribute['name'],
			);
		}

		foreach ($attributesByGroup as $key => $value) {
			$attributesArray[$key] = array(
				'name' => $key,
				'query' => array(),
			);			
			
			foreach ($attributesByGroup[$key] as $attribute) {
				$attributesArray[$key]['query'][] = $attribute;
			}
		}
		return $attributesArray;

	}


	/**
	 * Get the configuration name from the key for a field which is lang
	 * @param  [type] $key configname_idlang
	 * @return [type]      configname
	 */
	public function getKeyForFieldLang($key)
	{
		$data = explode('_', $key);
		$config_name = '';
		for ($i=0; $i < count($data) - 1; $i++) { 
			$config_name .= $data[$i];
			if($i < count($data) - 2)
				$config_name .= '_';
		}
		return $config_name;
	}

	public function getConfigurationLang($key)
	{
		foreach (Language::getLanguages() as $lang) {
			$value[$lang['id_lang']] = Configuration::get($key, $lang['id_lang']);
		}
		return $value;
	}

	/**
	 * Get the configuration name from the key for a field which is lang
	 * @param  [type] $key configname_idlang
	 * @return [type]      id_lang
	 */
	public function getIdLangForFieldLang($key)
	{
		$data = explode('_', $key);
		return $data[count($data) - 1];
	}

	private function getValuesForCheckboxes($config_name ,$config)
	{
		$returnValues = array();
		$configs = Tools::unserialize($config);
		foreach ($configs as $key => $value) {
			$returnValues[$config_name.'_'.$value] = true;
		}
		return $returnValues;
	}

	private function updateArrayConfigurationFromCheckbox($configName)
	{
		$config = array();
		foreach ($_POST as $key => $value) {
			if($id_checkbox = preg_replace('/^'. $configName . '_/', '', $key))
				if(is_numeric($id_checkbox))
					$config[] = $id_checkbox;
		}
		Configuration::updateValue($this->config_prefix.strtoupper($configName), serialize($config));
	}

	private function getAttributesGroupsArray()
	{
		$context = Context::getContext();
		$returnAttributeGroup = array();
		$attributesGroup = AttributeGroup::getAttributesGroups((int)$context->employee->id_lang);
        foreach ($attributesGroup as $attributeGroup) {
        	$returnAttributeGroup[$attributeGroup['id_attribute_group']] = array(
        		'id' => $attributeGroup['id_attribute_group'],
        		'name' => $attributeGroup['name']
        	);
        }

		return $returnAttributeGroup;

	}


}