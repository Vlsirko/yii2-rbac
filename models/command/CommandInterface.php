<?php

namespace Rbac\models\command;

/**
 * Description of CommandInterface
 *
 * @author Sirenko Vlad
 */
interface CommandInterface {

	public function run();
	
	public function rollback();
}
