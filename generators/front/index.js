'use strict';
var util = require('util');
var path = require('path');
var yeoman = require('yeoman-generator');
var yosay = require('yosay');
var chalk = require('chalk');


var PrestashopModuleGenerator = yeoman.generators.Base.extend({
  init: function () {
    this.pkg = require('../../package.json');   
    this.moduleName = this.config.get('moduleName');
  },

  getFrontControllerInformations: function() {
    var done  = this.async();
    console.log(yosay('You wanna generate a front controller for your module ? '));
    var prompts = [{
      type  : 'input',
      name  : 'controllerName',
      message : 'What\'s the controller\'s name (lowercase) ?' ,
      default : this.appname
    }];
    
    this.prompt(prompts, function (props) {
      this.controllerName = props.controllerName;
      done();
    }.bind(this));  
  },


  app: function () {
    /* Structure has already been set, we only add files */
    /* Creation of front file */
    this.template('_frontcontroller.php', 'controllers/front/'+this.controllerName+'.php');
    this.template('_frontcontroller.tpl', 'views/templates/front/'+this.controllerName+'.tpl');
    this.template('_frontcontroller.css', 'views/css/'+this.controllerName+'.css');
  },
});

module.exports = PrestashopModuleGenerator;
