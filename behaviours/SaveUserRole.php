<?php

namespace Rbac\behaviours;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii;

/**
 * This behavior is responsible for saving user roles and vali
 * @author Sirenko Vlad
 */
class SaveUserRole extends Behavior {

	public $owner;
	
	public function events()
	{
		return [
			ActiveRecord::EVENT_AFTER_INSERT => 'saveUserRole',
			ActiveRecord::EVENT_AFTER_UPDATE => 'saveUserRole'
		];
	}

	/**
	 * Set role to user after insert. 
	 */
	public function saveUserRole()
	{
		$userDataArray = yii::$app->request->post('User');
		
		if(isset($userDataArray['role'])){
			$this->owner->setUserRole($userDataArray['role']);
		}
	}

}
