<?php

namespace Rbac\interfaces;

/**
 *
 * @author Sirenko Vlad
 */
interface ObservableRbacController {

	const MODULE_NAME_METHOD = 'getModuleName';
	const ACTIONS_ALIAS_METHOD = 'getActionsAliasArray';

	/**
	 * @return string module name of current controller 
	 */
	public function getModuleName();
	
	/**
	 * @return array assoc array with action method name as key and
	 * alias as value
	 */
	public function getActionsAliasArray();
}
