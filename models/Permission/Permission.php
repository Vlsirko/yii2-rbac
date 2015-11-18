<?php

namespace RbacRuleManager\models\Permission;

use Yii;
use yii\db\Query;

/**
 * This Class is Facade of standart Yii2 auth permission
 *
 * @author Sirenko Vlad
 */
class Permission {

	private $name;
	private $description;
	private $authManager;

	const TABLE_NAME = 'auth_item';

	public function __construct($name, $description)
	{
		$this->name = $name;
		$this->description = $description;
		$this->authManager = Yii::$app->authManager;
	}

	public function save()
	{
		$permission = $this->authManager->createPermission($this->name);
		$permission->description = $this->description;
		$this->authManager->add($permission);
	}

	public function remove()
	{
		$permission = $this->authManager->createPermission($this->name);
		$this->authManager->remove($permission);
	}
	
	public function getName(){
		return $this->name;
	}

	public static function getByIdentificator($identificator)
	{
		$raw = self::getFindCommand($identificator)->queryAll();
		return self::hydrate($raw);
	}

	private static function getFindCommand($name)
	{
		$query = new Query;

		return $query->select('*')
				->from("{{" . self::TABLE_NAME . "}}")
				->where('name LIKE :substr', array(':substr' => $name . '%'))
				->createCommand();
	}

	private static function hydrate(array $raw)
	{
		$toReturn = [];
		foreach ($raw as $row) {
			$toReturn[$row['name']] = new self($row['name'], $row['description']);
		}
		return $toReturn;
	}

}
