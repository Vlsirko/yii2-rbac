<?php

namespace RbacRuleManager\controllers;

use yii\console\Controller;
use RbacRuleManager\models\command\CommandFactory;

class RbacController extends Controller {

	public function actionIndex($alias)
	{
		CommandFactory::getCommandByAlias($alias)->run();
	}
	
	public function actionDrop($alias)
	{
		CommandFactory::getCommandByAlias($alias)->rollback();
	}

}
