<?php

namespace Rbac\behaviours;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use Rbac\models\Permitions\Permission;

/**
 * Description of SavePermittionsBehaviour
 *
 * @author vlad
 */
class SavePermissionsBehaviour extends Behavior {

	public function events()
	{
		return [
			ActiveRecord::EVENT_AFTER_INSERT => 'addPermissionsToRole',
			ActiveRecord::EVENT_AFTER_UPDATE => 'addPermissionsToRole',
			ActiveRecord::EVENT_BEFORE_DELETE => 'deleteRolePermissionsRelation'
		];
	}

	public function addPermissionsToRole($event)
	{
		$this->owner->revokeAllPermissions();
		foreach ($this->owner->permissions as $permissionName) {
			$permission = Permission::getByName($permissionName);
			
			if ($permission) {
				$this->owner->addPermission($permission);
			}
		}
	}

	public function deleteRolePermissionsRelation($event)
	{
		$this->owner->revokeAllPermissions();
	}

}
