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

	public function save()
	{
		$this->fillDataFromAdapterToPermition();
		$this->permition->save();
		$this->trigger(ActiveRecord::EVENT_AFTER_INSERT);
	}

	public function update()
	{
		$this->fillDataFromAdapterToPermition();
		$this->permition->update();
		$this->trigger(ActiveRecord::EVENT_AFTER_UPDATE);
	}
	
	public function delete()
	{
		$this->fillDataFromAdapterToPermition();
		$this->trigger(ActiveRecord::EVENT_AFTER_DELETE);
		$this->permition->delete();
	}

	protected function fillDataFromAdapterToPermition()
	{
		foreach ($this->attributes as $attributeName => $value) {
			$this->permition->$attributeName = $value;
		}
	}

	public function __call($methodName, $params)
	{
		return call_user_func_array([$this->permition, $methodName], $params);
	}	
}
