<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RbacRuleManager\models\action;

use RbacRuleManager\models\file\ControllerFile;
use RbacRuleManager\models\file\AbstractFile;
use ReflectionMethod;

/**
 * Representing Controler Actions
 *
 * @author vlad
 */
class Action {

	public $module = null;
	private $reflectionMethod;
	private $controllerFile;

	public function __construct(ReflectionMethod $method, ControllerFile $controller)
	{
		$this->reflectionMethod = $method;
		$this->controllerFile = $controller;
	}

	public function getClass()
	{
		return $this->reflectionMethod->class;
	}

	public function getDescription()
	{
		return AbstractFile::getPhpDocByTag($this->reflectionMethod->getDocComment(), '@title');
	}
	
	
	public function getName()
	{
		return $this->reflectionMethod->name;
	}

	public function getRuleName()
	{
		return implode('\\', [$this->getClass(), $this->getName()]);
	}

}
