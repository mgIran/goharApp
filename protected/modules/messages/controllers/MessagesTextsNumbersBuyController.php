<?php
class MessagesTextsNumbersBuyController extends Controller {
    public static $actionsArray =
        array(
            'title' => 'خرید خط اختصاصی',
            'index' => array(
                'title' => 'نمایش خطوط',
                'type' => 'user',
                'menu' => TRUE,
                'menu_parent' => 'texts_send',
                'menu_name' => 'numbers_buy_index',
                'url' => 'messages/numbers_buy',
            ),
            'buy' => array(
                'title' => 'خرید خط',
                'type' => 'user',
            ),
            'report' => array(
                'title' => 'گزارش خرید خط',
                'type' => 'user',
                'menu' => TRUE,
                'menu_parent' => 'texts_send',
                'menu_name' => 'numbers_buy_report',
                'url' => 'messages/numbers_buy/report',
                'otherAction' => 'graph'
            ),
            'bank' => array(
                'title' => 'درگاه بانک',
                'type' => 'user',
            ),
            'admin' => array(
                'title' => 'تراکنش های مربوط به خرید خط',
                'type' => 'admin',
                'menu' => TRUE,
                'menu_parent' => 'texts_numbers_specials',
                'menu_name' => 'numbers_buy_admin',
                'url' => 'messages/numbers_buy/admin',
                'otherActions' => 'view'
            ),
        );

	public $layout='//layouts/column2';

	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

    public function actionIndex(){
        $prefixes = MessagesTextsNumbersPrefix::model()->findAllByAttributes(array(
            'status' => MessagesTextsNumbersPrefix::STATUS_ENABLE
        ));

        $model = new MessagesTextsNumbersCheck;

        $prefixes = CHtml::listData($prefixes,'id','number');
        $model->prefix_id = key($prefixes);

        $this->performAjaxValidation($model);

        $specialsModel = new MessagesTextsNumbersSpecials('search');
        $specialsModel->unsetAttributes();  // clear any default values
        $specialsModel->status = MessagesTextsNumbersSpecials::STATUS_AVAILABLE;

        if(isset($_GET['MessagesTextsNumbersSpecials']))
            $specialsModel->attributes=$_GET['MessagesTextsNumbersSpecials'];


        $this->render('index',array(
            'model' => $model,
            'prefixes' => $prefixes,
            'specialsModel' => $specialsModel,
        ));
    }

    public function actionBuy($buyId = NULL,$prefix=NULL,$number=NULL,$special_id=NULL){
        $specialModel = NULL;
        if(!is_null($buyId))
        {
            $buyModel = MessagesTextsNumbersBuy::model()->findByPk($buyId);
            $buy = $buyModel->buy;
        }
        elseif((!is_null($prefix) AND !is_null($number)) OR !is_null($special_id)){
            if(is_null($special_id)){
                $buyModel = new MessagesTextsNumbersCheck;
                $buyModel->prefix_id = $prefix;
                $buyModel->number = $number;
                $buyModel->validate(false);
                if($buyModel->checkError(false) != 1)
                    throw new CHttpException(404,'The specified post cannot be found.');
            }
            else{
                $specialModel = MessagesTextsNumbersSpecials::model()->findByPk($special_id,"status = :status",array(
                    ":status" => MessagesTextsNumbersSpecials::STATUS_AVAILABLE
                ));
                if(is_null($specialModel))
                    throw new CHttpException(404,'The specified post cannot be found.');

                $prefix = $specialModel->prefix_id;
                $number = $specialModel->number;

            }

            $buyModel = new MessagesTextsNumbersBuy;
            $buyModel->prefix_id = $prefix;
            $buyModel->number = $number;

            $buy = new Buys;

            if(!isset($_POST['MessagesTextsNumbersBuy']))
                Yii::app()->user->setState('tracking_no',rand(100000, 999999));
        }


        $messagesTextsNumbersBuyDataProvider = new CActiveDataProvider('MessagesTextsNumbersBuy',array(
            'criteria'=>array(
                'with' => 'buy',
                'limit' => 10,
                'order' => 'buy_id DESC',
                'condition' => 'user_id = :user',
                'params' => array(
                    ':user' => Yii::app()->user->userID
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        $price = 0;
        $factorFields = $this->getFactorFields($buyModel,$specialModel,$price);


        if(isset($_POST['MessagesTextsNumbersBuy'])){
            if($buyModel->isNewRecord){
                $buy->type = Buys::TYPE_NUMBER;
                $buy->status = Buys::STATUS_DOING;
                $buy->qty = 1;
                $buy->details = json_encode($factorFields);
                $buy->sum_price = $price;

                $buyModel->attributes = $_POST['MessagesTextsNumbersBuy'];
                if($buyModel->special == 1){
                    $specialModel->status = MessagesTextsNumbersSpecials::STATUS_SOLD;
                    $specialModel->save();
                }

                if($buy->save()){
                    $buyModel->buy_id = $buy->id;
                    if($buyModel->save())
                        $this->redirect(Yii::app()->createAbsoluteUrl('messages/numbers_buy/bank/'.$buyModel->buy_id));
                }
            }
            else{
                if($_POST['pass'] != '12345')
                    $this->redirect(Yii::app()->createAbsoluteUrl('messages/numbers_buy/bank/'.$buyModel->buy_id));
                $buyModel->attributes = $_POST['MessagesTextsNumbersBuy'];

                $buy = $buyModel->buy;
                $buy->attributes = $_POST['Buys'];
                $buy->save();

                $buyModel->save();

                $this->refresh();
            }
        }


        $this->render('buy',array(
            'factorFields' => $factorFields,
            'buy' => $buy,
            'buyModel' => $buyModel,
            'messagesTextsNumbersBuyDataProvider'=>$messagesTextsNumbersBuyDataProvider,
            'specialModel' => ((isset($specialModel))?$specialModel:NULL),
        ));
    }

    public function actionGraph(){
        Buys::model()->chartMaker(Buys::TYPE_NUMBER);
    }

    public function actionReport(){

        $criteria = Buys::model()->reportCriteria(FALSE);
        $criteria->compare('type',Buys::TYPE_NUMBER);

        $PlansBuysDataProvider = new CActiveDataProvider('MessagesTextsBuy',array(
            'criteria'=> $criteria,
            'pagination'=>array(
                'pageSize' => 10,
            ),
        ));

        $this->render('//report/index',array(
            'title' => 'شماره اختصاصی',
            'PlansBuysDataProvider'=>$PlansBuysDataProvider,
            'graphUrl' => "messages/numbers_buy/graph"
        ));
    }

    public function actionAdmin(){


        $model = new MessagesTextsNumbersBuy('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['MessagesTextsNumbersBuy']))
        {
            $model->attributes=$_GET['MessagesTextsNumbersBuy'];

            foreach($_GET['MessagesTextsNumbersBuy'] as $key=>$value){

                if(property_exists ( $model, $key )){

                    $model->$key = $value;
                }
            }

        }

        //var_dump($model->attributes);exit;

        $this->render('admin',array(
            'model'=>$model,
        ));

    }

    public function actionView($id){
        $buyModel = MessagesTextsNumbersBuy::model()->findByPk($id);


        $this->render('view',array(
            'buyModel' => $buyModel,
            'specialModel' => ((isset($specialModel))?$specialModel:NULL),
        ));
    }

    public function actionBank($id){
        $model = MessagesTextsNumbersBuy::model()->findByPk($id);
        $this->render('bank',array(
            'model' => $model,
        ));
    }

    protected function performAjaxValidation($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='messages-texts-numbers-check-form'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    private function getFactorFields($buyModel,$specialModel,&$price){
        $userPlan = json_decode(Yii::app()->user->plan);
        if($buyModel->isNewRecord OR (isset($buyModel->buy->details) AND is_null($buyModel->buy->details))){
            Yii::import('application.modules.plans.models.*');

            if(!is_null($specialModel))
                $approvedPrice = $specialModel->price;
            else
                $approvedPrice = MessagesTextsNumbersCheck::getPrice($buyModel->prefix->number,$buyModel->number);

            if(!$approvedPrice)
                throw new CHttpException(404,'The specified post cannot be found.');

            $model = Plans::model()->findByPk($userPlan->id);

            $extensionDiscount = floatval($model->extension_discount);
            $extensionPrice = ($approvedPrice * $extensionDiscount) / 100;

            // get tax
            $taxPercent = SiteOptions::model()->findByAttributes(array('name'=>'tax'));
            $taxPercent = floatval($taxPercent->value);
            $taxPrice = ($approvedPrice * $taxPercent) / 100;
            $price = floatval($approvedPrice) - $extensionPrice + $taxPrice;
            $factorFields = array(
                array(
                    'label' => 'تعداد (مقدار) محصول :',
                    'value' => '1',
                    'unit'  => 'واحد',
                ),
                array(
                    'label' => 'قیمت واحد',
                    'value' => number_format($approvedPrice),
                    'unit'  => 'تومان',
                ),
                array(
                    'label' => '+ قیمت کل',
                    'value' => number_format($approvedPrice),
                    'unit'  => 'تومان',
                ),
                'border',
                array(
                    'label' => 'مقدار درصد تخفیفی پلن',
                    'value' => $extensionDiscount,
                    'unit'  => 'درصد',
                ),
                array(
                    'label' => '- مبلغ تخفیف پلن',
                    'value' => number_format($extensionPrice) ,
                    'unit'  => 'تومان',
                ),
                'border',
                array(
                    'label' => 'درصد مالیات بر ارزش افزوده',
                    'value' => $taxPercent,
                    'unit'  => 'درصد',
                ),
                array(
                    'label' => '+ مبلغ مالیات بر ارزش افزوده',
                    'value' => $taxPrice,
                    'unit'  => 'تومان',

                ),
                'final' => array(
                    'label' => 'مبلغ صورت حساب',
                    'value' => $price,
                    'unit'  => 'تومان',
                )
            );
        }
        else{
            $factorFields = json_decode($buyModel->buy->details,true);
        }
        return $factorFields;
    }

}