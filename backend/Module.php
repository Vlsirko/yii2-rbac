<?php

namespace Rbac\backend;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'Rbac\backend\controllers';
	
    public function init()
    {
		parent::init();
    }
}
