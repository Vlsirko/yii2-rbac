<?php

namespace RbacRuleManager\models\file;

use RbacRuleManager\models\action\Action;
use ReflectionClass;
use ReflectionMethod;

/**
 * Representing Controller File
 *
 * @author vlad
 */
class ControllerFile extends AbstractFile {

	private $reflectionClass;
	private $moduleName;

	public function getActions()
	{
		$allMethods = $this->getReflection()->getMethods(ReflectionMethod::IS_PUBLIC);
		return $this->handleActions($allMethods);
	}

	private function getReflection()
	{
		if (is_null($this->reflectionClass)) {
			$this->reflectionClass = new ReflectionClass($this->getClass());
		}
		return $this->reflectionClass;
	}

	private function handleActions($reflectionMethodsArray)
	{
		$returnArray = [];
		foreach ($reflectionMethodsArray as $method) {
			if ($this->isAction($method) && !$this->isInherited($method)) {
				$returnArray[] = new Action($method, $this);
			}
		}
		return $returnArray;
	}

	private function isAction(ReflectionMethod $method)
	{
		return preg_match('#^action#', $method->name);
	}

	private function isInherited(ReflectionMethod $method)
	{
		return $this->getReflection()->getname() !== $method->class;
	}

	public function getModuleName()
	{
		if (is_null($this->moduleName)) {
			$comment = $this->getReflection()->getDocComment();
			$this->moduleName = AbstractFile::getPhpDocByTag($comment, '@module');
		}
		
		return $this->moduleName;
	}
	
	public function getName(){
		return array_pop(explode('\\',$this->getReflection()->name));
	}

}
