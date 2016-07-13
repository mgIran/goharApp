<?php
class ServicesController extends Controller {
    protected $_type = null;
	public $layout='//layouts/column2';
	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

    public function actionIndex(){
        $model = new SpecialServices('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['SpecialServices']))
            $model->attributes=$_GET['SpecialServices'];
        $model->type = $this->_type;

        $this->render('/specials/index',array(
            'model'=>$model,
        ));
    }

    public function actionCreate(){
        $model = new SpecialServices;
        if($this->_type == SpecialServices::TYPE_OVERALL)
            $overallModel =  new SpecialServicesOverallTime;

        $this->performAjaxValidation($model);

        if(isset($_POST['SpecialServices'])){
            $model->attributes = $_POST['SpecialServices'];
            $model->type = $this->_type;

            $transaction = Yii::app()->db->beginTransaction();
            try
            {
                $saveFlag = 0;
                $title = $this::$actionsArray;
                $title = $title['title'];
                $fields = $_POST['SpecialServices']['fields'];
                $fields[] = array('title'=>'failedSpecialService','value'=>uniqid());
                if($model->save()){
                    $saveFlag = 1;
                    if($this->_type == SpecialServices::TYPE_OVERALL){
                        $saveFlag--;
                        $overallModel->attributes = $_POST['SpecialServicesOverallTime'];

                        $date = explode('-',$_POST['SpecialServicesOverallTime']['start_time']);

                        $year = intval($date[0]);
                        $month = intval($date[1]);
                        $day = intval($date[2]);

                        $time = explode(' ',$_POST['SpecialServicesOverallTime']['start_time']);
                        $time = $time[1];

                        $date = Yii::app()->jdate->toGregorian($year,$month,$day);

                        $date = implode('-',$date);

                        $date = strtotime($date." ".$time);

                        $overallModel->start_time = $date;

                        $overallModel->service_id = $model->id;
                        if($overallModel->save())
                            $saveFlag++;

                    }
                    if($saveFlag == 1)
                        foreach($fields as $field){

                            $answersModel = new SpecialServicesAnswers;
                            $answersModel->service_id = $model->id;
                            $answersModel->answer_title = $field['title'];

                            if($answersModel->save()){

                                $saveFlag = 2;
                                foreach(preg_split('/,|،/',$field['value']) as $value){
                                    $keywordModel = new SpecialServicesAnswersKeywords;
                                    $keywordModel->answer_id = $answersModel->id;
                                    $keywordModel->keyword = $value;

                                    if($keywordModel->save())
                                        $saveFlag = 3;
                                }
                            }
                        }

                }
                $transaction->commit();
                if($saveFlag == 3){
                    Yii::app()->user->setFlash('success',$title .' با موفقیت ثبت گردید.');
                    $this->redirect('index');
                }

            }
            catch(Exception $e)
            {
                $transaction->rollback();
                Yii::app()->user->setFlash('failed','خطا در هنگام ثبت '.$title);
            }

        }

        Yii::import("application.modules.messages.models.MessagesTextsUsersNumbers");
        $numbers = MessagesTextsUsersNumbers::model()->findAll('user_id = :userId AND status = :status',array(
            ':userId' => Yii::app()->user->userID ,
            ':status' => MessagesTextsUsersNumbers::STATUS_ENABLE
        ));
        $numbers = CHtml::listData($numbers,'id','number');




        $this->render('/specials/create',array(
            'model' => $model,
            'numbers' => $numbers,
            'overallModel' => (isset($overallModel))?$overallModel:NULL
        ));

    }

    public function actionView($id){
        $model = $this->loadModel($id);

        if($model->user_id != Yii::app()->user->userID OR $model->type  != $this->_type)
            throw new HttpException('403','access denied');
        $this->render('/specials/view',array(
            'model' => $model
        ));
    }

    private function checkKeyword($user,$keyword){
        $findKeyword = SpecialServicesAnswersKeywords::model()->findByAttributes(array('keyword'=>$keyword));

        $model = new SpecialServicesSendedAnswers;
        $model->answer_id = (!is_null($findKeyword))?$findKeyword->answer_id:NULL;
        $model->user = $user;
        $model->save();
    }

    public function loadModel($id){
        $model=SpecialServices::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    public function actionDelete($id){
        $delete = $this->loadModel($id);
        if($delete->user_id == Yii::app()->user->userID)
            $delete->delete();

        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    protected function performAjaxValidation($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='special-services-form')
        {
            $validate = json_decode(CActiveForm::validate($model),true);
            if($this->_type == SpecialServices::TYPE_OVERALL)
            {
                $overallModel = new SpecialServicesOverallTime;
                $validate2 = json_decode(CActiveForm::validate($overallModel),true);
                $validate = array_merge($validate,$validate2);
            }
            echo json_encode($validate);
            Yii::app()->end();
        }
    }

}
