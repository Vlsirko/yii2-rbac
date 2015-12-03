<?php

namespace Rbac\behaviours;

use yii\base\Behavior;
use Rbac\models\Permitions\Permission;
use yii\web\ForbiddenHttpException;
use yii\base\Module;

/**
 * Description of CheckAccessBehaviour
 *
 * @author Sirenko Vlad
 */
class CheckAccessBehaviour extends Behavior {

	public function events()
	{
		return [
			Module::EVENT_BEFORE_ACTION => 'checkAccess'
		];
	}

	public function checkAccess($event)
	{

		$permissionName = Permission::getPermissionNameViaAction($event->action);
		
		if(\yii::$app->user->getIsGuest() && \Yii::$app->controller->action->id !== 'login'){
			return \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
		}
		
		
		if(!Permission::isExists($permissionName) || \yii::$app->user->can($permissionName)){
			return;
		}
		
		throw new ForbiddenHttpException(\Yii::t('yii', 'You are not allowed to perform this action.'), 403);
	}

}
