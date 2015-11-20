<?php

namespace Rbac\models\file;

use Rbac\models\action\Action;
use ReflectionClass;
use ReflectionMethod;
use Rbac\models\Permitions\Permission;
use Rbac\interfaces\ObservableRbacController;

/**
 * Representing Controller File
 *
 * @author vlad
 */
class ControllerFile extends AbstractFile {

	private $reflectionClass;
	private $moduleName;
	private $existsRulesStorage = [];
	private $actionsStorage;

	public function getActions()
	{
		if (is_null($this->actionsStorage)) {
			$allMethods = $this->getReflection()->getMethods(ReflectionMethod::IS_PUBLIC);
			$this->actionsStorage = $this->handleActions($allMethods);
		}
		return $this->actionsStorage;
	}

	private function getActionsPermittionsNamesArray()
	{
		$toReturn = [];
		foreach ($this->getActions() as $action) {
			$toReturn[] = $action->getIdentificator();
		}
		return $toReturn;
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
			$this->moduleName =$this->executeControllerMethod(ObservableRbacController::MODULE_NAME_METHOD);
		}
		return $this->moduleName;
	}

	public function getName()
	{
		return array_pop(explode('\\', $this->getReflection()->name));
	}

	public function getExistsPermissions()
	{
		if (empty($this->existsRulesStorage)) {
			$this->existsRulesStorage = Permission::getByModuleIdentificator($this->getIdentificator());
		}

		return $this->existsRulesStorage;
	}

	public function getIdentificator()
	{
		return Action::createIdentificator([$this->getModuleName(), $this->getName()]);
	}

	public function getNotUsedPermissions()
	{
		$actionsPermittionsNames = $this->getActionsPermittionsNamesArray();
		$existsPermitionsNames = array_keys($this->getExistsPermissions());
		$notUsedPermitionsNames = array_diff($existsPermitionsNames, $actionsPermittionsNames);

		$toReturn = [];
		foreach ($notUsedPermitionsNames as $name) {
			$toReturn[] = $this->getExistsPermissions()[$name];
		}
		return $toReturn;
	}

	public function executeControllerMethod($methodName)
	{
		return call_user_func([$this->getReflection()->name, $methodName]);
	}

}
