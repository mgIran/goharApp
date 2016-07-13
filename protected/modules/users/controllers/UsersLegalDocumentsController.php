<?php
class UsersLegalDocumentsController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'مدارک حقوقی',
            'index' => array(
//                'menu' => true,
//                'menu_name' => 'plan_user',
//                'url' => 'users/documents/index',
                'title' => 'ثبت و ویرایش مدارک حقوقی',
                'type' => 'user',
            ),
        );


    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

	public function actionIndex(){
        $model = $this->loadModel(Yii::app()->user->userID);
        $this->performAjaxValidation($model);

        if(isset($_POST['UsersLegalDocuments'])){
            $model->attributes = $_POST['UsersLegalDocuments'];
            if($model->save()){
                Yii::app()->user->setFlash('success','مدارک حقوقی شما با موفقیت ثبت شد.');
                $this->refresh();
            }
            else
                Yii::app()->user->setFlash('danger','خطا در هنگام ویرایش!');
        }

        $towns = UsersPlaces::model()->findAll("parent_id IS NULL");

        $towns = CHtml::listData($towns,"id","title");

        $this->render('update',array(
            'model' => $model,
            'towns' => $towns,
        ));
	}

    public function loadModel($id){
        $model=UsersLegalDocuments::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='users-legal-documents-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}