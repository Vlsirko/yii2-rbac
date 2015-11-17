<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RbacRuleManager\models\command;

/**
 * Description of commandFactory
 *
 * @author vlad
 */
class CommandFactory {

	const CREATE_COMMAND = 'rules';

	public static function getCommandByAlias($alias)
	{
		switch($alias){
			case self::CREATE_COMMAND:
				return new RuleCommand();
		}
		
		throw new \Exception("Undefined command '{$alias}'");
	}

}
