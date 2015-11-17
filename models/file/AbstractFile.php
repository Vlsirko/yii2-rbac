<?php

namespace RbacRuleManager\models\file;

/**
 * Representing of php class file
 *
 * @author vlad
 */
abstract class AbstractFile {

	protected $pathToFile;
	protected $class = null;

	public function __construct($pathToFile = null)
	{
		$this->pathToFile = $pathToFile;
	}

	public function setFilepath($pathToFile)
	{
		$this->pathToFile = $pathToFile;
	}

	public function getClass()
	{
		if (is_null($this->class)) {
			$code = file_get_contents($this->pathToFile);
			$pattern = '#namespace\s([^;]+)(.|\n)+class\s([\w]+)#mi';
			if (preg_match($pattern, $code, $matches)) {
				$this->class = implode('\\', [$matches[1], $matches[3]]);
			} else {
				throw new \Exception('Can\'t parse php file ' . $this->pathToFile);
			}
		}

		return $this->class;
	}

	public static function getPhpDocByTag($phpDoc, $tag)
	{
		if (empty($tag)) {
			return $phpDoc;
		}

		$matches = array();
		preg_match("/" . $tag . ":(.*)(\\r\\n|\\r|\\n)/U", $phpDoc, $matches);

		if (isset($matches[1])) {
			return trim($matches[1]);
		}

		return '';
	}

}
