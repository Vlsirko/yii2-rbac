<?php

namespace RbacRuleManager\models\command;

use RbacRuleManager\models\command\CommandInterface;
use RbacRuleManager\models\FileScanner\factories\ScanerAbstractFactory;
use RbacRuleManager\models\Messager;
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

		foreach ($controllerFiles as $controller) {
			$notUsedPermissions = $controller->getNotUsedPermissions();
			
			foreach($notUsedPermissions as $permition){
				$permition->remove();
				Messager::getInstance()->showMessage('Permission ' . $permition->getName(). ' removed', Messager::FAILURE);
			}
		
			$actions = $controller->getActions();
			foreach ($actions as $action) {
				if (!$action->isNew()) {
					Messager::getInstance()->showMessage('Permission ' . $action->getRuleName(). ' exists', Messager::WARNING);
					continue;
				}
				$action->getPermission()->save();
				Messager::getInstance()->showMessage('Permission ' . $action->getRuleName(). ' created', Messager::SUCCSESS);
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
