'use strict';
var util = require('util');
var path = require('path');
var yeoman = require('yeoman-generator');
var yosay = require('yosay');
var chalk = require('chalk');


var PrestashopModuleGenerator = yeoman.generators.Base.extend({
  init: function () {
    this.pkg = require('../../package.json');   
    
    this.modulePrefix = this.config.get('modulePrefix');
  },



  getModelInformations: function() {
    var done  = this.async();
    console.log(yosay('You wanna generate a model for your module ? '));
    var prompts = [{
      type  : 'input',
      name  : 'modelName',
      message : 'What\'s the model\'s name (CameCase) ?' ,
      default : this.appname
    }, 
    {
      type  : 'confirm',
      name  : 'isMultiLang',
      message : 'Is your model multilang ?',
      default : false
    }, 
    {
      type : 'input', 
      name : 'table', 
      message : 'What will be the table associated to your model ?',
      default : 'Table name',
    },
    {
      type : 'confirm', 
      name : 'needsAdminController',
      message : 'Will this model need an Admin Controller to help you manage the object ?',
      default : true,
    }];
      
    this.prompt(prompts, function (props) {
      this.modelName = props.modelName;
      this.isMultiLang = props.isMultiLang;
      this.table = props.table;
      this.needsAdminController = props.needsAdminController;
      done();
    }.bind(this));  
  },

  
  explainFields: function(){
    console.log(yosay('Ok let\s get to business and describe me the best way you can the different fields contained in your model'))
    this.attributes = {};
  },

  getFields: function(){
    this.done = this.async();

    var validationAssociation = {
      'TYPE_INT' : 'isInt', 
      'TYPE_BOOL' : 'isBool', 
      'TYPE_STRING' : 'isString', 
      'TYPE_FLOAT' : 'isFloat',
      'TYPE_DATE' : 'isDateFormat',
      'TYPE_HTML' : 'isCleanHTML',
    };

    var sqlAssociation = {
      'TYPE_INT' : 'int(16) unsigned NOT NULL', 
      'TYPE_BOOL' : 'tinyint(1) NOT NULL', 
      'TYPE_STRING' : 'varchar(255) NOT NULL', 
      'TYPE_FLOAT' : 'float unsigned NOT NULL',
      'TYPE_DATE' : 'datetime NOT NULL',
      'TYPE_HTML' : 'text NOT NULL',
    };

    var alignedAssociation = {
      'TYPE_INT' : true, 
      'TYPE_BOOL' : false, 
      'TYPE_STRING' : false, 
      'TYPE_FLOAT' : true,
      'TYPE_DATE' : false,
      'TYPE_HTML' : false,
    };

    var widthAssociation = {
      'TYPE_INT' : 20, 
      'TYPE_BOOL' : 20, 
      'TYPE_STRING' : 120, 
      'TYPE_FLOAT' : 20,
      'TYPE_DATE' : 50,
      'TYPE_HTML' : 120,
    };


    var prompts = [{
      type : 'input', 
      name : 'name',
      message : 'Field name (lowercase) : ',
    }, 
    {
      type : 'list',
      name : 'type',
      message : 'What\'s the type of your field ',
      choices : [
        {name:'Bool', value:'TYPE_BOOL'},
        {name:'Date', value:'TYPE_DATE'},
        {name:'Float', value:'TYPE_FLOAT'},
        {name:'Html', value:'TYPE_HTML'},
        {name:'Int', value:'TYPE_INT'},
        {name:'String', value:'TYPE_STRING'},
      ],
    },
    {
      type : 'confirm', 
      name : 'isLang', 
      message : 'Need the field to be translated ?',
      default : false
    }];

    if(this.needsAdminController)
    {
      prompts.push({
        type : 'input', 
        name : 'label', 
        message : '* Label for the admin controller'
      });
      prompts.push({
        type : 'list',
        name : 'formType',
        message : '* What is the type of the field', 
        choices : [
          {name:'Category', value:'categories'},
          {name:'Radio', value:'radio'},
          {name:'Select (needs a little work from you)', value:'select'},
          {name:'Text', value:'text'},
          {name:'Textarea', value:'textarea'},
          {name:'Gallerie (not finished yet)', value:'gallery'},
          {name:'File', value:'file'}
        ]
      });
    }


    this.prompt(prompts, function(props){
      this.attributes[props.name] = {
        name: props.name,
        type: props.type, 
        isLang: props.isLang,
        validationRule: validationAssociation[props.type],
        sqltype: sqlAssociation[props.type],
        width: widthAssociation[props.type],
        formType: props.formType,
        label: props.label,
      }
      if(this.needsAdminController)
        this._getFieldsDetails(props.name);
      else
        this._nextField();
    }.bind(this));
  },

  _getFieldsDetails: function(name)
  {
    
    switch(this.attributes[name].formType)
    {
      case 'categories':
        this._nextField();
        break;
      case 'radio':
        this.attributes[name].values = {};
        this._getFieldsDetailsRadio(name);
        break;
      case 'select':
        this._nextField();
        break;
      case 'text':
        this._nextField();
        break;
      case 'textarea':
        this._getFieldsDetailsTextarea(name);
        break;
      case 'gallery':
        this._nextField();
        break;
      case 'file':
        this._nextField();
        break;
    }
  },

  _getFieldsDetailsTextarea: function(name)
  {
    var prompts = [{
      type: 'confirm',
      name: 'autoload_rte',
      message: 'Use TinyMCE ? '
    }];
    this.prompt(prompts, function (props) {
      this.attributes[name].autoload_rte = props.autoload_rte;
      this._nextField();
    }.bind(this)); 
  },

  _getFieldsDetailsRadio: function(name)
  {
    var prompts = [{
        type : 'input', 
        name : 'label', 
        message : 'Label for the radio field' + name
    }, {
      type : 'input', 
      name : 'value', 
      message : 'Value for the radio' + name
    }];
    this.prompt(prompts, function (props) {
      this.attributes[name].values[props.value] = {'value' : props.value, 'label' : props.label};
      this._getNextRadioDetails(name);
    }.bind(this));
  },

  _getNextRadioDetails: function(name)
  {
    var prompts = [{
      type  : 'confirm',
      name  : 'repeat',
      message : 'Add another value for '+ name + ' ?' ,
    }];
      
    this.prompt(prompts, function (props) {
      if(props.repeat)
        this._getFieldsDetailsRadio(name);
      else
        this._nextField();
    }.bind(this)); 
  },

  _nextField: function(){
    var prompts = [{
      type  : 'confirm',
      name  : 'repeat',
      message : 'Another field ?' ,
    } 
    ];
      
    this.prompt(prompts, function (props) {
      if(props.repeat)
        this.getFields();
      else
        this.done();
      }.bind(this));  
  },

  app: function () {
    /* Structure has already been set, we only add files */
    /* Creation of module file */
    this.template('_objectmodel.php', 'classes/'+this.modelName+'.php');
    if(this.needsAdminController)
      this.template('_admincontroller.php', 'controllers/admin/Admin'+this.modelName+'Controller.php');
  },

  projectfiles: function () {
    /*this.copy('editorconfig', '.editorconfig');
    this.copy('jshintrc', '.jshintrc');*/
  }
});

module.exports = PrestashopModuleGenerator;
