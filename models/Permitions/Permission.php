<?php

namespace Rbac\models\Permitions;

use yii\db\Query;

/**
 * This Class is Facade of standart Yii2 auth permission
 *
 * @author Sirenko Vlad
 */
class Permission extends AbstractPermitionEntity {

	const TABLE_NAME = 'auth_item';

	public function save()
	{
		$permission = self::getAuthManager()->createPermission($this->name);
		$permission->description = $this->description;
		self::getAuthManager()->add($permission);
	}

	public function delete()
	{
		$permission = self::getAuthManager()->createPermission($this->name);
		self::getAuthManager()->remove($permission);
	}

	public static function getByIdentificator($identificator)
	{
		$raw = self::getFindCommand($identificator)->queryAll();
		return self::hydrateArray($raw);
	}

	public static function getByRole($roleName)
	{
		$roles = self::getAuthManager()->getPermissionsByRole($roleName);
		return self::hydrateObjectsArray($roles);
	}

	public static function getAllAsAssoc()
	{

		$permissions = self::getAuthManager()->getPermissions();
		return self::convertRbacItemsArrayToAssoc($permissions);
	}

	public static function getByName($permissionName)
	{
		$yii_permission = self::getAuthManager()->getPermission($permissionName);
		if ($yii_permission) {
			return self::hydrateRbacItem($yii_permission);
		}
	}

	private static function getFindCommand($name)
	{
		$query = new Query;

		return $query->select('*')
				->from("{{" . self::TABLE_NAME . "}}")
				->where('name LIKE :substr', array(':substr' => $name . '%'))
				->createCommand();
	}

	private static function hydrateArray(array $raw)
	{
		$toReturn = [];
		foreach ($raw as $row) {
			if (is_array($row)) {
				$toReturn[$row['name']] = new self($row['name'], $row['description']);
			}
		}
		return $toReturn;
	}

	public static function hydrateObjectsArray(array $raw)
	{
		$toReturn = [];
		foreach ($raw as $row) {
			if (is_object($row) && ($row instanceof \yii\rbac\Item)) {
				$toReturn[$row->name] = self::hydrateRbacItem($row);
			}
		}
		return $toReturn;
	}

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