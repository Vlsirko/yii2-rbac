
**yii2-rbac-rule-generator**
=======

> *This is beta version, and may contain some bugs

This extention allows you to create rbac permitions from Controller Files

----------

**Instalation via composer:**

 * Add to yours *composer.json* in "require" field:
```
	"vlsirko/yii2-rbac" : "dev-master"
```
 * Add to yours *composer.json* in "repositories" field:
```
{
	"url" : "https://github.com/Vlsirko/yii2-rbac.git",
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


 * Change the class of authManager in your config file:
```
return [
    // ...
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        // ...
    ],
];
```

* Run yii2 rbac migrations by command: `yii migrate --migrationPath=@yii/rbac/migrations`

* Run modules migrations by command: `yii migrate --migrationPath=@vendor/vlsirko/rbac/migrations`
	
	This command will create permissions wich are responsible for managing roles and permissions. 
	Also, this migration adds recently created permissions to all users.


* Finally, you need to add Rbac module to backend application. Add to your backend modules config next code:
```
	'rbac' => [
		'class' => 'Rbac\backend\Module',
		'defaultRoute' => 'roles/index'
	]
```



----------

**Usage:**
----------

**Generating permitions:**
* Every observable controller must implement **_RbacRuleManager\controllers\ObservableRbacController_** interface

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
 

**Managing roles and permissions, checking access**

* Every controller which needs in limiting access must use rbac_controll behaviour. Add to your controller this behavior: 
```
	public function behaviors()
    {
        return [
            ///
			'rbac_controll' =>[
				'class' => 'Rbac\behaviours\CheckAccessBehaviour',
			],
			///
        ];
    }
```
Standart access control can be removed

* Add To your User Form next input:

```
	<?=$form->field($model, 'role')->dropDownList(Rbac\models\Permitions\Role::getAllRolesForDropdown()) ?>
```
* User class must extends Rbac\models\RbacAbstractUserActiveRecord abstract class instead ActiveRecord class:
```
class User extends RbacAbstractUserActiveRecord implements IdentityInterface {

	const STATUS_DISABLED = 0;
	const STATUS_ENABLED = 1;
///
//
```
* Add to user model following behaviour:

```
	public function behaviors()
	{
		return [
			TimestampBehavior::className(),
			SaveUserRole::className(),
			///
		];
	}
```
