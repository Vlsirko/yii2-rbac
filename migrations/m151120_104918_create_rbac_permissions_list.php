<?php

use yii\db\Schema;
use yii\db\Migration;

class m151120_104918_create_rbac_permissions_list extends Migration
{
    public function up()
    {
		$createPermittion = \yii::$app->authManager->createPermission('rbac\roles\create');
		$createPermittion->description = 'Создание групп пользователей';
		\yii::$app->authManager->add($createPermittion);
		
		$indexPermittion = \yii::$app->authManager->createPermission('rbac\roles\index');
		$indexPermittion->description = 'Просмотр листинга групп пользователей';
		\yii::$app->authManager->add($indexPermittion);
		
		$viewPermittion = \yii::$app->authManager->createPermission('rbac\roles\view');
		$viewPermittion->description = 'Просмотр группы пользователей';
		\yii::$app->authManager->add($viewPermittion);
		
		$deletePermittion = \yii::$app->authManager->createPermission('rbac\roles\delete');
		$deletePermittion->description = 'Удаление группы пользователей';
		\yii::$app->authManager->add($deletePermittion);
		
		$updatePermittion = \yii::$app->authManager->createPermission('rbac\roles\update');
		$updatePermittion->description = 'Редактирование группы пользователей';
		\yii::$app->authManager->add($updatePermittion);
		
		$roles = \yii::$app->authManager->getRoles();
		foreach($roles as $role){
			\yii::$app->authManager->addChild($role, $createPermittion);
			\yii::$app->authManager->addChild($role, $indexPermittion);
			\yii::$app->authManager->addChild($role, $viewPermittion);
			\yii::$app->authManager->addChild($role, $deletePermittion);
			\yii::$app->authManager->addChild($role, $updatePermittion);
		}
    }

    public function down()
    {
       
    }
}
