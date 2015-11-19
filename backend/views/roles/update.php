<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Umcms\models\User */

$this->title = 'Редактирование группы пользователей: ' . ' ' . $model->getDescription();
$this->params['breadcrumbs'][] = ['label' => 'Группы пользователей', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getDescription(), 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="user-update">

    <?= $this->render('_form', [
        'model' => $model,
		'newRecord' => false
    ]) ?>

</div>
