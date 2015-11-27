<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rbac\models;
use yii\db\ActiveRecord;
use yii\rbac\Role;

/**
 * Description of AbstractUser
 *
 * @author vlad
 */
abstract class RbacAbstractUserActiveRecord extends ActiveRecord {

	public function getRole()
	{
		if ($this->loadRole()) {
			return $this->roleObject->name;
		}
	}

	public function getRoleForView()
	{
		if ($this->loadRole()) {
			return $this->roleObject->description;
		}
	}

	public function setUserRole($roleName)
	{
		$roleManager = \Yii::$app->authManager;
		$roleObject = $roleManager->getRole($roleName);

		if ($roleObject) {
			$this->assignRole($roleObject);
		}
	}

	protected function assignRole(Role $role)
	{
		if (!$this->roleExists($role)) {
			$this->removePreviousRoles();
			\Yii::$app->authManager->assign($role, $this->id);
		}
	}

	protected function roleExists(Role $role)
	{
		$userRoles = \Yii::$app->authManager->getRolesByUser($this->id);
		return array_key_exists($role->name, $userRoles);
	}

	protected function removePreviousRoles()
	{
		\Yii::$app->authManager->revokeAll($this->id);
	}

	protected function loadRole()
	{
		if (is_null($this->roleObject)) {
			$rolesArray = \Yii::$app->authManager->getRolesByUser($this->id);
			if (empty($rolesArray)) {
				return false;
			}
			$this->roleObject = $rolesArray[array_keys($rolesArray)[0]];
		}

		return true;
	}

}
