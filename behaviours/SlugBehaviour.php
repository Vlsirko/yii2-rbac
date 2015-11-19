<?php

namespace Rbac\behaviours;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use Rbac\models\Helpers\TranslitHelper;

/**
 * Description of SlugBehaviour
 *
 * @author vlad
 */
class SlugBehaviour extends Behavior {

	public $in_field;
	
	public $out_field;
	
	public $rewrite = true; 

	public function events()
	{
		return [
			ActiveRecord::EVENT_BEFORE_VALIDATE => 'translit'
		];
	}

	public function translit($event)
	{
		if($this->owner->{$this->out_field} && !$this->rewrite){
			return;
		}
		
		$this->owner->{$this->out_field} = TranslitHelper::t($this->owner->{$this->in_field});
	}

}
