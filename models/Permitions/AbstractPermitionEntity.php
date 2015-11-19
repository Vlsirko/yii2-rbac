<?php

namespace Rbac\models\Permitions;

use yii\rbac\Item;
use yii;

/**
 * Description of AbstractPermition
 *
 * @author vlad
 */
abstract class AbstractPermitionEntity {

	protected $name;
	protected $description;
	protected $rbacItem;

	public function __construct($name = null, $description = null)
	{
		$this->name = $name;
		$this->description = $description;
	}

	public static function getAuthManager()
	{
		return yii::$app->authManager;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getDescription()
	{
		return $this->description ? $this->description : '';
	}

	public function setDescription($description)
	{
		return $this->description = $description;
	}

	public static function getCalledClassWithoutNamespace()
	{
		return end(explode('\\', get_called_class()));
	}

	protected static function hydrateRbacItem(Item $object)
	{
		$objectClass = get_called_class();
		return new $objectClass($object->name, $object->description);
	}

	public static function gePropertiesNamesArray()
	{
		return ['name', 'description'];
	}

	public function __set($name, $value)
	{
		if (method_exists($this, 'set' . ucfirst($name))) {
			$this->$name = $value;
		}
	}

	public static function findByName($name)
	{
		$role = self::getAuthManager()->getRole($name);
		if (!$role) {
			return;
		}
		return self::hydrateRbacItem($role);
	}
	
	protected static function convertRbacItemsArrayToAssoc($rbacItemsArray){
		$toReturn = [];
		foreach ($rbacItemsArray as $permission) {

			$toReturn[$permission->name] = $permission->description ?
				$permission->description : $permission->name;
		}
		return $toReturn;
	}
	
	public function behaviours(){
		return [];
	}
	
	public function getValidationRules(){
		return [];
	}
	
	abstract function getRbacItem();

	abstract public function getAttributesLabels();

	abstract public function getAttributes();

	abstract public function update();
	
	abstract public function save();
	
	abstract public function delete();
}
