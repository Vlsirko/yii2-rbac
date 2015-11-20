<?php

namespace Rbac\behaviours;

use yii\base\Behavior;
use yii\base\Controller;
use Rbac\models\action\Action;
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
		$permissionName = Action::getPermissionNameViaAction($event->action);
		
		if(\yii::$app->user->can($permissionName)){
			return;
		}
		
		throw new ForbiddenHttpException(\Yii::t('yii', 'You are not allowed to perform this action.'), 403);
	}

}
