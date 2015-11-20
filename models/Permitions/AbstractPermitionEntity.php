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
	
	/**
	 * @return string classname without namespace
	 */
	public static function getCalledClassWithoutNamespace()
	{
		return end(explode('\\', get_called_class()));
	}
	
	/*
	 * Convert  yii\rbac\item to \self
	 * @param $object yii\rbac\Item
	 * @return \self
	 */
	protected static function hydrateRbacItem(Item $object)
	{
		$objectClass = get_called_class();
		return new $objectClass($object->name, $object->description);
	}
	
	
	public function __set($name, $value)
	{	
		$methodName = 'set' . ucfirst($name);
		if (method_exists($this, $methodName)) {
			return call_user_func_array([$this, $methodName], [$value]);
		}
	}
	
	/**
	 * Convert array of yii\rbac\items to array of \self items
	 * @param array $rbacItemsArray array of yii\rbac\item
	 * @return array array of \self items
	 */
	protected static function convertRbacItemsArrayToAssoc($rbacItemsArray){
		$toReturn = [];
		foreach ($rbacItemsArray as $permission) {

			$toReturn[$permission->name] = $permission->description ?
				$permission->description : $permission->name;
		}
		return $toReturn;
	}
	
	/**
	 * @return array standart validation rules representing as standart yii behaviours
	 */
	public function behaviours(){
		return [];
	}
	
	/**
	 * @return array standart validation rules representing as standart yii rules
	 */
	public function getValidationRules(){
		return [];
	}
	
	/**
	 * @return  yii\rbac\item  instance for manipulating by auth manager
	 */
	abstract function getRbacItem();
	
	/**
	 * @return array Attributes labels as array
	 */
	abstract public function getAttributesLabels();

	/**
	 * @return array Current items attributes
	 */
	abstract public function getAttributes();
	
	/**
	 * update item
	 * @return bool 
	 */
	abstract public function update();
	
	/**
	 * save item
	 * @return bool 
	 */
	abstract public function save();
	
	
	/**
	 * delete item
	 * @return bool 
	 */
	abstract public function delete();
}
