<?php

namespace RbacRuleManager\models\command;

use RbacRuleManager\models\command\CommandInterface;
use RbacRuleManager\models\FileScanner\factories\ScanerAbstractFactory;
use Yii;
/**
 * This command is responsible for creating rbac rules
 * and rollback all changes
 *
 * @author Sirenko Vlad
 */
class RuleCommand implements CommandInterface {

	public function run()
	{
		$scaner = ScanerAbstractFactory::getFactoryByCode(ScanerAbstractFactory::CONTROLLER_FACTORY)
				->getScanner()
				->setScanPathes($this->getScanPathes());
		
		$controllerFiles = $scaner->getFiles();
		
		foreach($controllerFiles as $controller){
			$actions = $controller->getActions();
			foreach($actions as $action){
				$permission = Yii::$app->authManager->createPermission($action->getRuleName());
				$permission->description = $action->getDescription();
				Yii::$app->authManager->add($permission);
			}
		}
	}

	public function rollback()
	{
		
	}
	
	protected function getScanPathes()
	{
		$pathArray = \Yii::$app->controller->module->scan_path;
		if (!$pathArray) {
			throw new \Exception('There is no scan pathes');
		}
		return $pathArray;
	}


	

}
