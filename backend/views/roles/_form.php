<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use Rbac\models\Permitions\Permission;

/* @var $this yii\web\View */
/* @var $model Umcms\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
	
    <?= $form->field($model, 'description')->textInput() ?>
	<?php //var_dump($model->getPermitionsForDropdown()); die();?>
	
	 <?= $form->field($model, 'permissions')->listBox(Permission::getAllAsAssoc(), ['multiple' => true, 'size'=>50]);?>
	
    <div class="form-group">
        <?= Html::submitButton($newRecord ? 'Создать' : 'Обновить', ['class' => $newRecord  ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	
    <?php ActiveForm::end(); ?>

</div>
