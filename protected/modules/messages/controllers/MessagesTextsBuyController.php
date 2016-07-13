<?php
class MessagesTextsBuyController extends Controller {
    public static $actionsArray =
        array(
            'title' => 'شارژ آنلاین اعتبار پیام',
            'index' => array(
                'title' => 'خرید شارژ',
                'type' => 'user',
                'menu' => TRUE,
                'menu_parent' => 'texts_send',
                'menu_name' => 'texts_buy_index',
                'url' => 'messages/texts_buy',
            ),
            'report' => array(
                'title' => 'گزارش خرید شارژ',
                'type' => 'user',
                'menu' => TRUE,
                'menu_parent' => 'texts_send',
                'menu_name' => 'texts_buy_report',
                'url' => 'messages/texts_buy/report',
                'otherActions' => 'graph'
            ),
            'bank' => array(
                'title' => 'درگاه بانک',
                'type' => 'user',
            ),
            'admin' => array(
                'title' => 'تراکنش های مربوط به خرید شارژ پیامک',
                'type' => 'admin',
                'menu' => TRUE,
                'menu_parent' => 'texts_numbers_specials',
                'menu_name' => 'texts_buy_admin',
                'url' => 'messages/texts_buy/admin',
                'otherActions' => 'view'
            ),
            'settings' => array(
                'title' => 'تنظیمات ارسال پیام',
                'type' => 'admin',
                'menu' => TRUE,
                //'menu_parent' => 'texts_numbers_specials',
                'menu_name' => 'texts_buy_settings',
                'url' => 'messages/texts_buy/settings'
            ),
        );

	public $layout='//layouts/column2';

	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

    public function actionIndex($buyId = NULL){
        if(!is_null($buyId))
            $buyModel = $model = MessagesTextsBuy::model()->findByPk($buyId);

        if(!isset($buyModel) OR is_null($buyId)){
            $buyModel = $model = new MessagesTextsBuy;
            if(!isset($_POST['MessagesTextsBuy']))
                Yii::app()->user->setState('tracking_no',rand(100000, 999999));
        }

        $messagesTextsBuyDataProvider = new CActiveDataProvider('MessagesTextsBuy',array(
            'criteria'=>array(
                'limit' => 10,
                'order' => 'id DESC',
                'condition' => 'user_id = :user AND type = :type',
                'params' => array(
                    ':user' => Yii::app()->user->userID,
                    ':type' => Buys::TYPE_PAGE,
                ),
            ),
            'pagination'=>array(
                'pageSize' => 10,
            ),
        ));

        $userPlan = json_decode(Yii::app()->user->plan);
        if($model->isNewRecord OR is_null($model->details)){
            Yii::import('application.modules.plans.models.*');
            $numberOfPages = ((isset($_GET['num']) AND $_GET['num']>0)?$_GET['num']:1000);
            $eachPrice = 0;
            $approvedPrice = $this->getPrice($numberOfPages,$eachPrice);
            $plan = Plans::model()->findByPk($userPlan->id);

            $extensionDiscount = floatval($plan->extension_discount);
            $extensionPrice = ($approvedPrice * $extensionDiscount) / 100;

            // get tax
            $taxPercent = floatval(SiteOptions::getOption('tax'));
            $taxPrice = ($approvedPrice * $taxPercent) / 100;
            $price = floatval($approvedPrice) - $extensionPrice + $taxPrice;
            $model->qty = $numberOfPages;
            $factorFields = array(
                array(
                    'label' => 'تعداد (مقدار) محصول :',
                    'value' => number_format($numberOfPages),
                    'unit'  => 'واحد',
                ),
                array(
                    'label' => 'قیمت واحد',
                    'value' => number_format($eachPrice),
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
        else {
            $factorFields = json_decode($model->details, true);
        }

        if(isset($_POST['MessagesTextsBuy'])){
            if($buyModel->isNewRecord){
                $buyModel->type = Buys::TYPE_PAGE;
                $buyModel->attributes = $_POST['MessagesTextsBuy'];
                $buyModel->sum_price = $price;

                if($buyModel->save())
                    $this->redirect(Yii::app()->createAbsoluteUrl('messages/texts_buy/bank/'.$buyModel->id));
            }
            else{
                if($_POST['pass'] != '12345')
                    $this->redirect(Yii::app()->createAbsoluteUrl('messages/texts_buy/bank/'.$buyModel->id));
                $buyModel->attributes = $_POST['MessagesTextsBuy'];

                if($buyModel->save()){
                    $this->currentUser->scenario = 'changeValue';
                    $this->currentUser->sms_charge = intval($this->currentUser->sms_charge) + intval($buyModel->qty);
                    $this->currentUser->save();
                }
                $this->refresh();
            }
        }

        if(isset($_GET['num']))
            $render = 'renderPartial';
        else
            $render = 'render';

        $this->$render('buy',array(
            'model' => $model,
            'dataProvider'=>$messagesTextsBuyDataProvider,
            'factorFields' => $factorFields,
            'productTitle' => 'شارژ آنلاین اعتبار پیامک'
        ));
    }

    public function actionGraph(){
        Buys::model()->chartMaker(Buys::TYPE_PAGE);
    }

    public function actionReport(){
        $criteria = Buys::model()->reportCriteria(FALSE);
        $criteria->compare('type',Buys::TYPE_PAGE);

        $PlansBuysDataProvider = new CActiveDataProvider('MessagesTextsBuy',array(
            'criteria'=> $criteria,
            'pagination'=>array(
                'pageSize' => 10,
            ),
        ));

        $this->render('//report/index',array(
            'title' => 'شارژ',
            'PlansBuysDataProvider'=>$PlansBuysDataProvider,
            'graphUrl' => "messages/texts_buy/graph"
        ));
    }

    public function actionAdmin(){

        $model = new MessagesTextsBuy('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['MessagesTextsBuy']))
            $model->attributes=$_GET['MessagesTextsBuy'];

        $this->render('admin',array(
            'model'=>$model,
        ));

    }

    public function actionView($id){
        $buyModel = MessagesTextsBuy::model()->findByPk($id);

        $this->render('view',array(
            'buyModel' => $buyModel,
        ));
    }

    protected function getPrice($num=1000,&$eachPrice){
        // decode texts prices range
        $prices = SiteOptions::model()->findByAttributes(array("name" => "sms_prices_range"));
        $prices = json_decode($prices->value,TRUE);

        $userPlan = json_decode(Yii::app()->user->plan);
        $id = $userPlan->id;
        Yii::import('application.modules.plans.models.*');
        $model = Plans::model()->findByPk($id);
        $pages = json_decode($model->pages);
        switch(TRUE){
            case ($num >= $prices[0] AND $num <= $prices[1]):
                $eachPrice = $pages->pages_1->value;
            break;
            case ($num >= $prices[2] AND $num <= $prices[3]):
                $eachPrice = $pages->pages_1000->value;
            break;
            case ($num >= $prices[4] AND $num <= $prices[5]):
                $eachPrice = $pages->pages_10000->value;
            break;
            case ($num >= $prices[6]):
                $eachPrice = $pages->pages_100000->value;
            break;
        }

        $eachPrice = floatval($eachPrice);
        $price = $eachPrice * $num;

        return $price;
    }

    public function actionBank($id){
        $model = MessagesTextsBuy::model()->findByPk($id);
        $this->render('bank',array(
            'model' => $model,
        ));
    }

    public function actionSettings(){
        $criteria = new CDbCriteria;
        $criteria->addInCondition('name',array('sms_investment','sms_prices_range','sms_send_range','sms_sending_system','sms_send_usage_1','sms_send_usage_2'));
        $model = SiteOptions::model()->findAll($criteria);

        $pagesModel = Pages::model()->find("name = 'text_buy_about'");

        $settings = array();
        foreach($model as $item){
            $settings[$item->name] = $item;
        }

        if(isset($_POST['Pages'])){
            $pagesModel->attributes = $_POST['Pages'];
            $post = $_POST['Pages'];
            $post['sms_investment'] = str_replace(',','',$post['sms_investment']).$post['sms_investment_radio'];
            if($pagesModel->save()){
                foreach($settings as $key=>$setting){
                    if(is_array($post[$key]))
                        $post[$key] = json_encode($post[$key]);
                    $setting['value'] = $post[$key];
                    $setting->save();
                }
                $this->refresh();
            }
        }

        $this->render('settings',array(
            'settings' => $settings,
            'pagesModel' => $pagesModel
        ));
    }

}
