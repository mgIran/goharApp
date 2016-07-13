<?php
class UsersBankDetailsController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'مشخصات حساب بانکی',
            'index' => array(
                'title' => 'ثبت و ویرایش مشخصات حساب بانک',
                'type' => 'user',
            ),
        );


    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

	public function actionIndex($id = NULL){
        if(is_null($id))
            $id = Yii::app()->user->userID;
        elseif(Yii::app()->user->type == 'user')
            throw new CHttpException(404,'The requested page does not exist.');

        $model = $this->loadModel($id);
        $this->performAjaxValidation($model);

        if(isset($_POST['UsersBankDetails'])){
            $model->attributes = $_POST['UsersBankDetails'];
            if($model->save()){
                Yii::app()->user->setFlash('success','مشخصات حساب بانکی شما با موفقیت ثبت شد.');
                $this->refresh();
            }
            else
                Yii::app()->user->setFlash('danger','خطا در هنگام ویرایش!');
        }

        $this->render('update',array(
            'model' => $model,
        ));
	}

    public function loadModel($id){
        $model=UsersBankDetails::model()->findByPk($id);
        if($model===null)
        {
            if($id == Yii::app()->user->userID AND Yii::app()->user->type == 'user'){
                $model = new UsersBankDetails;
                $model->user_id = $id;
                $model->save();
            }
            else
                throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }

    protected function performAjaxValidation($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='users-bank-details-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}