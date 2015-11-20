<?php

namespace Rbac\backend\controllers;

use yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use Rbac\models\Permitions\Role;
use Rbac\models\Permitions\ActiveRecordAdapter;


class RolesController extends Controller
{

	public function actionIndex()
    {
       $dataProvider = new ArrayDataProvider([
            'allModels' => Role::getAllRoles(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
	

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => Role::getRoleByName($id),
        ]);
    }
	
	public function actionCreate()
    {
        $model = new ActiveRecordAdapter(new Role());
		$model->setScenario(Role::CREATE_SCENARIO);
        if ($model->load(yii::$app->request->post()) && $model->validate()) {
			$model->save();
            return $this->redirect(['index', 'id' => $model->name]);
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }
	
	public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->update();
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('update', [
                'model' => $model
            ]);
        }
    }
	
	public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	
	protected function findModel($id)
    {
        if (($model = Role::getRoleByName($id)) !== null) {
            return new ActiveRecordAdapter($model);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
}
