<?php

namespace Rbac\models\Permitions;

use Rbac\models\Validator\UniqueRoleValidator;

/**
 * Facade of standart yii2 rbac role
 *
 * @author Sirenko Vlad
 */
class Role extends AbstractPermitionEntity {

	const CREATE_SCENARIO = 'create';

	protected $permissions = [];

	/**
	 * Returns role by name
	 * @param type $roleName
	 * @return \self
	 */
	public static function getRoleByName($roleName)
	{
		$yiiRole = self::getAuthManager()->getRole($roleName);
		return new self($yiiRole->name, $yiiRole->description);
	}

	/**
	 * Returns all roles as array of yii\rbac\items
	 * @return array
	 */
	public static function getAllRoles()
	{
		return self::getAuthManager()->getRoles();
	}

	/**
	 * Returns array of Rbac\models\Permitions\Permissions which this role contains
	 * @return array
	 */
	public function getPermissions()
	{
		if (empty($this->permissions)) {
			$this->permissions = Permission::getByRole($this->name);
		}

		return $this->permissions;
	}
	
	/**
	 * Returns array of Rbac\models\Permitions\Permissions which this role contains
	 * @return array
	 */
	public function setPermissions($permissions)
	{
		$this->permissions = $permissions;
	}

	/**
	 * Returns array of permissions, represented as associative array, which this role contains
	 * @return array
	 */
	public function getPermissionsAsAssoc()
	{
		return self::convertRbacItemsArrayToAssoc(Permission::getByRole($this->name));
	}

	/**
	 * Returns array of permissions for yii2 dropdown
	 * @return array
	 */
	public function getPermitionsForDropdown()
	{
		$selected = array_keys($this->getPermissions());
		$toReturn = [];
		foreach ($selected as $permissionAlias) {
			$toReturn[$permissionAlias] = ['selected' => 'selected'];
		}
		return $toReturn;
	}

	/*
	 * @inheritdoc
	 */

	public function delete()
	{
		$role = self::getAuthManager()->createRole($this->name);
		self::getAuthManager()->remove($role);
	}

	/*
	 * @inheritdoc
	 */

	public function save()
	{
		$role = self::getAuthManager()->createRole($this->name);
		$role->description = $this->description;
		self::getAuthManager()->add($role);
		return true;
	}

	/*
	 * @inheritdoc
	 */

	public function update()
	{
		$role = self::getAuthManager()->createRole($this->name);
		$role->description = $this->description;
		self::getAuthManager()->update($this->name, $role);
	}

	public function addPermission(Permission $permission)
	{

		self::getAuthManager()->addChild($this->getRbacItem(), $permission->getRbacItem());
	}

	/*
	 * @inheritdoc
	 */

	public function getAttributes()
	{
		$toReturn = [];
		$attributes = array_keys($this->getAttributesLabels());
		foreach ($attributes as $atribute) {
			$toReturn[$atribute] = $this->$atribute;
		}
		return $toReturn;
	}

	/*
	 * @inheritdoc
	 */

	public function getAttributesLabels()
	{
		return [
			'name' => 'name',
			'description' => 'Название',
			'permissions' => 'Права группы'
		];
	}

	/*
	 * @inheritdoc
	 */

	public function getValidationRules()
	{
		return [
			[['description', 'name'], 'required'],
			['description', UniqueRoleValidator::className(), 'on' => self::CREATE_SCENARIO],
			['permissions', 'safe']
		];
	}

	/*
	 * @inheritdoc
	 */

	public function behaviours()
	{
		return [
			'TranslitBehaviour' => [
				'class' => 'Rbac\behaviours\SlugBehaviour',
				'in_field' => 'description',
				'out_field' => 'name',
				'rewrite' => false
			],
			'SavePermissionsBehaviour' => [
				'class' => 'Rbac\behaviours\SavePermissionsBehaviour'
			]
		];
	}

	/**
	 * Removes relations between current role and all permission which it has
	 * @return bool
	 */
	public function revokeAllPermissions()
	{
		return self::getAuthManager()->removeChildren($this->getRbacItem());
	}

	/**
	 * @inheritdoc
	 */
	public function getRbacItem()
	{
		if (is_null($this->rbacItem)) {
			$this->rbacItem = self::getAuthManager()->getRole($this->name);
		}

		return $this->rbacItem;
	}

}
