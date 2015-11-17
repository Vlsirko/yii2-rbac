<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RbacRuleManager\models\FileScanner;

use RbacRuleManager\models\FileScanner\strategy\FileScannerStrategyInterface;
use RbacRuleManager\models\file\AbstractFile;
use RbacRuleManager\models\Messager;

/**
 * Search files in sys by search_strategy
 *
 * @author Sirenko Vlad
 */
class FileScanner {

	protected $strategy;
	protected $prototype;
	protected $scanPathes = [];

	public function __construct(FileScannerStrategyInterface $strategy, AbstractFile $prototype)
	{
		$this->strategy = $strategy;
		$this->prototype = $prototype;
	}

	public function setScanPathes(array $pathes)
	{
		$this->scanPathes = $pathes;
		return $this;
	}

	public function getFiles()
	{
		
		$files = [];

		foreach ($this->scanPathes as $path) {
			$path = $this->handleYiiAlias($path);
			Messager::getInstance()->showMessage('Start scan: ' . $path, Messager::NOTE);
			$files = $this->recoursiveSearchFileInDir($path);
		}

		return $files;
	}

	protected function handleYiiAlias($path)
	{
		if (strpos($path, '@') === 0) {
			$path = \Yii::getAlias($path);
		}
		return $path;
	}

	protected function recoursiveSearchFileInDir($dir)
	{
		$returnArray = [];

		if (is_dir($dir)) {
			$files = array_diff(scandir($dir), ['.', '..']);
			foreach ($files as $file) {
				$filePath = implode(DIRECTORY_SEPARATOR, [$dir, $file]);
				$this->handleFile($filePath , $returnArray);
			}
		}

		return $returnArray;
	}

	protected function handleFile($pathFile, &$returnArray)
	{
		if (is_dir($pathFile)) {
			$returnArray = array_merge($returnArray, $this->recoursiveSearchFileInDir($pathFile));
		}

		else if (is_file($pathFile) && $this->strategy->isFileSuitable($pathFile)) {
			Messager::getInstance()->showMessage('Find file ' . $pathFile, Messager::SUCCSESS);
			$file = clone $this->prototype;
			$file->setFilepath($pathFile);
			$returnArray[] = $file;
		}
	}

}
