<?php

namespace Rbac\console;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'Rbac\console\controllers';
	
	public $scan_path = [];
	
    public function init()
    {
		parent::init();
    }
}
