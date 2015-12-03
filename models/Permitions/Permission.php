<?php

namespace Rbac\models\Permitions;

use yii\db\Query;
use yii\base\InlineAction;
use Rbac\models\action\Action;


/**
 * This Class is Facade of standart Yii2 auth permission
 *
 * @author Sirenko Vlad
 */
class Permission extends AbstractPermitionEntity {

	const TABLE_NAME = 'auth_item';
	
	/**
	 * Returns array of Rbac\models\Permitions\Permission by module identificator
	 * 
	 * @param string $identificator This string created in getIdentificator method 
	 * of Rbac\models\action\Action
	 * 
	 * @return array
	 */
	public static function getByModuleIdentificator($identificator)
	{
		$raw = self::getFindByModuleNameQuery($identificator)->queryAll();
		return self::hydrateArrayOfAssocArrays($raw);
	}

	/**
	 * Returns array of Rbac\models\Permitions\Permission by role name
	 * 
	 * @param string $roleName
	 * @return array
	 */
	public static function getByRole($roleName)
	{
		$roles = self::getAuthManager()->getPermissionsByRole($roleName);
		return self::hydrateArrayOfRbacItemsObjects($roles);
	}

	/**
	 * Returns one permit by name
	 * 
	 * @param string $permissionName
	 * @return  Rbac\models\Permitions\Permission
	 */
	public static function getByName($permissionName)
	{
		$yii_permission = self::getAuthManager()->getPermission($permissionName);
		if ($yii_permission) {
			return self::hydrateRbacItem($yii_permission);
		}
		return false;
	}

	/**
	 * Return all permissions in system as associative array 
	 * @return array
	 */
	public static function getAllAsAssoc()
	{
		$permissions = self::getAuthManager()->getPermissions();
		return self::convertRbacItemsArrayToAssoc($permissions);
	}

	/*
	 * Creates sql query to find module permissions
	 * 
	 * @param $moduleName string the name of moodule
	 * @return yii\db\Query;
	 */
	private static function getFindByModuleNameQuery($moduleName)
	{
		$query = new Query;

		return $query->select('*')
				->from("{{" . self::TABLE_NAME . "}}")
				->where('name LIKE :substr', array(':substr' => $moduleName . '%'))
				->createCommand();
	}
	
	/**
	 * Convert array of mysql qyery rows to array of Rbac\models\Permitions\Permission objects
	 * @param array $raw 
	 * @return array
	 */
	public static function hydrateArrayOfAssocArrays(array $raw)
	{
		$toReturn = [];
		foreach ($raw as $row) {
			if (is_array($row)) {
				$toReturn[$row['name']] = new self($row['name'], $row['description']);
			}
		}
		return $toReturn;
	}
	
	/**
	 * Convert array of yii/rbac/items to array of Rbac\models\Permitions\Permission objects
	 * @param array $raw array of yii/rbac/items
	 * @return array
	 */
	public static function hydrateArrayOfRbacItemsObjects(array $raw)
	{
		$toReturn = [];
		foreach ($raw as $row) {
			if (is_object($row) && ($row instanceof \yii\rbac\Item)) {
				$toReturn[$row->name] = self::hydrateRbacItem($row);
			}
		}
		return $toReturn;
	}
	
	/**
	 * @param InlineAction $action 
	 * @return string Identificator of permission
	 */
	public static function getPermissionNameViaAction(\yii\base\Action $action)
	{
		return Action::createIdentificator([
				$action->controller->module->id,
				array_pop(explode('\\', $action->controller->className())),
				str_replace('action', '', $action->id)
		]);
	}
	
	/**
	 * Checks is permission exists
	 * @param strng $permissionName
	 * @return bool
	 */
	public static function isExists($permissionName)
	{
		return self::getAuthManager()->getPermission($permissionName) !== null;
	}
	

	/**
	 * @inheritdoc
	 */
	public function save()
	{
		$permission = self::getAuthManager()->createPermission($this->name);
		$permission->description = $this->description;
		return self::getAuthManager()->add($permission);
	}
	
	/**
	 * @inheritdoc
	 */
	public function delete()
	{
		$permission = self::getAuthManager()->createPermission($this->name);
		return self::getAuthManager()->remove($permission);
	}
	
	/**
	 * @inheritdoc
	 */
	public function getRbacItem()
	{
		if (is_null($this->rbacItem)) {
			$this->rbacItem = self::getAuthManager()->getPermission($this->name);
		}

		return $this->rbacItem;
	}

	/**
	 * @todo 
	 * @throws \yii\base\NotSupportedException
	 */
	public function getAttributes()
	{
		throw new \yii\base\NotSupportedException();
	}

	/**
	 * @todo 
	 * @throws \yii\base\NotSupportedException
	 */
	public function getValidationRules()
	{
		throw new \yii\base\NotSupportedException();
	}

	/**
	 * @todo 
	 * @throws \yii\base\NotSupportedException
	 */
	public function getAttributesLabels()
	{
		throw new \yii\base\NotSupportedException();
	}

	/**
	 * @todo 
	 * @throws \yii\base\NotSupportedException
	 */
	public function update()
	{
		throw new \yii\base\NotSupportedException();
	}

}
