<?php

namespace RbacRuleManager;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'RbacRuleManager\controllers';
	
	public $scan_path = [];
	
    public function init()
    {
		parent::init();
    }
}
