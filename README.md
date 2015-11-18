
**yii2-rbac-rule-generator**
=======

> *This is beta version, and may contain some bugs

This extention allows you to create rbac permitions from Controller Files

----------

**Instalation via composer:**
-----------------------------

 * Add to yours *composer.json* in "require" field:
```
	"Vlsirko/yii2-rbac-rule-generator" : "dev-master"
```
 * Add to yours *composer.json* in "repositories" field:
```
{
	"url" : "https://github.com/Vlsirko/yii2-rbac-rule-generator.git",
	"type" : "git"
}
```
 * Run `*./composer.phar update*` in shell
 * Change your configuration file:


Add this module to console configuration

```  
    'rbac_rule' => [
		'class' => 'RbacRuleManager\Module',
		'scan_path' => [
			'path to directory with controller files (yii alias is accepted)'
		]
	]
```	


----------

**Usage:**
----------

**Generating permitions:**
* Every observable controller must implement RbacRuleManager\controllers\ObservableRbacController interface

* You must redifine 2 methods: "getModuleName" and "getActionsAliasArray"
```
	public function getModuleName()
	{
		return 'UserPermitions';
	}
	
	public function getActionsAliasArray()
	{
		return [
			'index' => 'Просмотр пользовательских ролей'
		];
	}
```
If controller doesn't belongs to module, you can just return empty string in "getModuleName" method

* execute module in shell `$ ./yii rbac_rule/rbac rules`
 