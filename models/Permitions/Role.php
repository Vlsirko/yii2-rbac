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

	public static function getRoleByAlias($alias)
	{
		$yiiRole = self::getAuthManager()->getRole($alias);
		return new self($yiiRole->name, $yiiRole->description);
	}

	public static function getAllRoles()
	{
		return self::getAuthManager()->getRoles();
	}
	

	public function getPermissions()
	{
		if (empty($this->permissions)) {
			$this->permissions = Permission::getByRole($this->name);
		}

		return $this->permissions;
	}

	public function getPermissionsAsAssoc()
	{
		return self::convertRbacItemsArrayToAssoc(Permission::getByRole($this->name));
	}

	public function getPermitionsForDropdown()
	{

		$selected = array_keys($this->getPermissions());
		$toReturn = [];
		foreach ($selected as $permissionAlias) {
			$toReturn[$permissionAlias] = ['selected' => 'selected'];
		}
		
		return $toReturn;
	}

	public function delete()
	{
		$role = self::getAuthManager()->createRole($this->name);
		self::getAuthManager()->remove($role);
	}

	public function save()
	{
		$role = self::getAuthManager()->createRole($this->name);
		$role->description = $this->description;
		self::getAuthManager()->add($role);
		return true;
	}

	public function update()
	{
		$role = self::getAuthManager()->createRole($this->name);
		$role->description = $this->description;
		self::getAuthManager()->update($this->name, $role);
	}

	public function addPermission(Permission $permission){
		
		self::getAuthManager()->addChild($this->getRbacItem(), $permission->getRbacItem());
	}
	
	
	public function getAttributes()
	{
		$toReturn = [];
		$attributes = array_keys($this->getAttributesLabels());
		foreach ($attributes as $atribute) {
			$toReturn[$atribute] = $this->$atribute;
		}
		return $toReturn;
	}
	
	public function getAttributesLabels()
	{
		return [
			'name' => 'name',
			'description' => 'Название',
			'permissions' => 'Права группы'
		];
	}

	public function getValidationRules()
	{
		return [
			[['description', 'name'], 'required'],
			['description', UniqueRoleValidator::className(), 'on' => self::CREATE_SCENARIO],
			['permissions', 'safe']
		];
	}

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
	
	
	public function revokeAllPermissions(){
		self::getAuthManager()->removeChildren($this->getRbacItem());
	}
	
	public function getRbacItem()
	{
		if(is_null($this->rbacItem)){
			$this->rbacItem = self::getAuthManager()->getRole($this->name);
		}
		
		return $this->rbacItem ;
	}
}
