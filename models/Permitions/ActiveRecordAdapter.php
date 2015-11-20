<?php

namespace Rbac\models\Permitions;

use yii\base\DynamicModel;
use yii\db\ActiveRecord;

/**
 * This adapter needs for emulate active record in forms
 * @author Sirenko Vlad
 */
class ActiveRecordAdapter extends DynamicModel {

	private $permition;

	public function __construct(AbstractPermitionEntity $permition)
	{
		parent::__construct($permition->getAttributes());
		$this->permition = $permition;
		$this->attachBehaviors($permition->behaviours());
	}

	public function attributeLabels()
	{
		return $this->permition->getAttributesLabels();
	}

	public function rules()
	{
		return $this->permition->getValidationRules();
	}

	/**
	 * Execute same permission method trigger events
	 * @return boolean
	 */
	public function save()
	{
		if ($this->fillDataFromAdapterToPermition()) {
			$this->trigger(ActiveRecord::EVENT_BEFORE_INSERT);
			try {
				$this->permition->save();
				$this->trigger(ActiveRecord::EVENT_AFTER_INSERT);
				return true;
			} catch (\Exception $e) {
				
			}
		}

		return false;
	}

	/**
	 * Execute same permission method trigger events
	 * @return boolean
	 */
	public function update()
	{
		if ($this->fillDataFromAdapterToPermition()) {
			$this->trigger(ActiveRecord::EVENT_BEFORE_UPDATE);
			try {
				$this->permition->update();
				$this->trigger(ActiveRecord::EVENT_AFTER_UPDATE);
				return true;
			} catch (\Exception $e) {
				
			}
		}
		return false;
	}

	/**
	 * Execute same permission method trigger events
	 * @return boolean
	 */
	public function delete()
	{
		$saveProcess = false;
		if ($this->fillDataFromAdapterToPermition()) {
			$this->trigger(ActiveRecord::EVENT_BEFORE_DELETE);
			try {
				$this->permition->delete();
				$this->trigger(ActiveRecord::EVENT_AFTER_DELETE);
				$saveProcess = true;
			} catch (Exception $e) {
				$saveProcess = false;
			}
		}
		return $saveProcess;
	}

	/**
	 * Transfer data from adapter to storaged permition. 
	 * This method is used before insert/save/update
	 * 
	 * @return bool
	 */
	protected function fillDataFromAdapterToPermition()
	{

		foreach ($this->attributes as $attributeName => $value) {
			$this->permition->$attributeName = $value;
		}

		return $this->attributes === $this->permition->getAttributes();
	}

	public function __call($methodName, $params)
	{
		return call_user_func_array([$this->permition, $methodName], $params);
	}

}
