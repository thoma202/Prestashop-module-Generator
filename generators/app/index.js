'use strict';
var util = require('util');
var path = require('path');
var yeoman = require('yeoman-generator');
var yosay = require('yosay');
var chalk = require('chalk');


var PrestashopModuleGenerator = yeoman.generators.Base.extend({
  init: function () {
    this.pkg = require('../../package.json');   
  },



  getModuleName: function() {
    var done  = this.async();
    console.log(yosay('Welcome to this PrestaShop\'s Module generator'));
    var prompts = [{
      type  : 'input',
      name  : 'moduleName',
      message : 'Please give me your module\'s name in Camel Case',
      default : this.appname
    }, 
    {
      type  : 'input',
      name  : 'moduleDisplayName',
      message : 'Please give me your module\'s display name',
      default : this.appname 
    },
    {
      type  : 'input',
      name  : 'moduleDescription',
      message : 'Please give me your module\'s Description',
      default : this.appname
    }, 
    {
      type  : 'list',
      name  : 'moduleTab',
      message : 'Which tab is the module associated with',
      choices: [
        {name: "Administration", value: "administration"},
        {name: "Advertising & Marketing", value: "advertising_marketing"},
        {name: "Analytics & Invoices", value: "analytics_invoices"},
        {name: "Checkout", value: "checkout"},
        {name: "Content Management", value: "content_management"},
        {name: "Export", value: "export"},
        {name: "Front Office Features", value: "front_office_features"},
        {name: "i18n & Localization", value: "i18n_localization"},
        {name: "Market Place", value: "market_place"},
        {name: "Merchandizing", value: "merchandizing"},
        {name: "Migration Tools", value: "migration_tools"},
        {name: "Other Modules", value: "other_modules"},
        {name: "Payment & Gateways", value: "payments_gateways"},
        {name: "Payment Security", value: "payment_security"},
        {name: "Pricing & Promotion", value: "pricing_promotion"},
        {name: "Quick / Bulk update", value: "quick_bulk_update"},
        {name: "Search & Filter", value: "search_filter"},
        {name: "SEO", value: "seo"},
        {name: "Shipping & Logistics", value: "shipping_logistics"},
        {name: "Slideshows", value: "slideshows"},
        {name: "Smart Shopping", value: "smart_shopping"},
        {name: "Social Networks", value: "social_networks"},
      ],
      default : 'other_modules'
    },
    {
      type : 'confirm', 
      name : 'needMenu',
      message : 'Do you want to create a specific menu for this module ? ',
    },
    {
      type : 'input', 
      name : 'newMenu', 
      message : 'What will be the menu name', 
      when : function(props){
        return props.needMenu;
      }
    },
    {
      when : function(props){
        return !props.needMenu;
      },
      type : 'confirm', 
      name : 'useMenu',
      message : 'Do you want to use a specific Menu for this  ? ',
    },
    {
      type : 'list', 
      name : 'existingMenu', 
      message : 'What will be the menu used', 
      choices : [
        {value:'9' , name:'Catalog' },
        {value:'10' , name:'Orders' },
        {value:'11' , name:'Customers' },
        {value:'12' , name:'Promotions' },
        {value:'13' , name:'Modules' },
        {value:'14' , name:'Shipping' },
        {value:'15' , name:'Localization' },
        {value:'16' , name:'Preferences' },
        {value:'17' , name:'Advanced Parameters' },
        {value:'18' , name:'Administration' },
        {value:'19' , name:'Stats' },
      ],
      when : function(props){
        return props.useMenu;
      }
    },
    {
      type  : 'input',
      name  : 'modulePrefix',
      message : 'Please give a unique prefix for the module',
      default : this.appname 
    }];
      
    this.prompt(prompts, function (props) {
      this.moduleName = props.moduleName;
      this.moduleDisplayName = props.moduleDisplayName;
      this.moduleDescription = props.moduleDescription;
      this.moduleTab = props.moduleTab;
      this.moduleNameLower = this.moduleName.toLowerCase();
      this.newMenu = props.newMenu;
      this.needMenu = props.needMenu;
      this.useMenu = props.useMenu;
      this.modulePrefix = props.modulePrefix;
      this.existingMenu = props.existingMenu;
      done();
    }.bind(this));  
  },

  app: function () {

    /* Creation of root folder */
    this.mkdir(this.moduleNameLower);
    /* Change root path */
    this.destinationRoot(path.join(this.destinationRoot(), '/' + this.moduleNameLower));

    /* Creation of structure */
    this.mkdir('classes');
    this.copy('_index.php', 'classes/index.php');
    this.mkdir('classes/helper');
    this.copy('_index.php', 'classes/helper/index.php');
    this.mkdir('ajax');
    this.copy('_index.php', 'ajax/index.php');
    this.mkdir('controllers');
    this.copy('_index.php', 'controllers/index.php');
    this.mkdir('controllers/admin');
    this.copy('_index.php', 'controllers/admin/index.php');
    this.mkdir('controllers/front');
    this.copy('_index.php', 'controllers/front/index.php');
    this.mkdir('views/templates/front');
    this.copy('_index.php', 'views/templates/front/index.php');
    this.mkdir('views/templates/admin');
    this.copy('_index.php', 'views/templates/admin/index.php');
    this.mkdir('views/templates/hook');
    this.copy('_index.php', 'views/templates/hook/index.php');
    this.mkdir('views/css');
    this.copy('_index.php', 'views/css/index.php');
    this.mkdir('views/img');
    this.copy('_index.php', 'views/img/index.php');
    this.mkdir('views/js');
    this.copy('_index.php', 'views/js/index.php');


    /* Creation of default files that does not need changes */
    this.copy('_banner.tpl', 'views/templates/hook/banner.tpl');
    this.copy('_banner.tpl', 'views/templates/hook/back.tpl');

    /* Creation of module file */
    this.template('_module.php', this.moduleNameLower+'.php');
    
    /* Creation of helpers */
    this.template('_totAdminTabHelper.php', 'classes/helper/'+this.modulePrefix+'TotAdminTabHelper.php' )
    
    this.config.set('moduleName', this.moduleNameLower);
    this.config.set('modulePrefix', this.modulePrefix);
    if(this.needMenu || this.useMenu)
    {
      if(this.needMenu)
      {// We create a new Menu and keep the menu name in the configuration
        this.config.set('newMenu');
      }
      else
      {// We use the existing Menu in the configuration
        this.config.set('existingMenu');
      }
    } 
  },

  projectfiles: function () {
    /*this.copy('editorconfig', '.editorconfig');
    this.copy('jshintrc', '.jshintrc');*/
  }
});

module.exports = PrestashopModuleGenerator;
