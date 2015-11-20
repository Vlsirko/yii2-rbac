<?php

namespace Rbac\models\Validator;

use yii\validators\Validator;
use Rbac\models\Permitions\AbstractPermitionEntity;
use Rbac\models\Helpers\TranslitHelper;

/**
 * Validates role on uniqueness
 * @author vlad
 */
class UniqueRoleValidator extends Validator{
	
	
	public function validateAttribute($model, $attribute)
    {
		$roleToValidate = TranslitHelper::t($model->$attribute);
		if(AbstractPermitionEntity::getAuthManager()->getRole($roleToValidate)){
			$model->addError($attribute, 'Такая роль существует');
		}
    }
}
