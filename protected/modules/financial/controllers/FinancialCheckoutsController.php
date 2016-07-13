<?php
class FinancialCheckoutsController extends Controller{
    public static $actionsArray = array(
        'title' => 'تسویه حساب',
        'index' => array(
            'title' => 'تسویه حساب و افزایش  اعتبار نقدی',
            'type' => 'user',
            'menu' => TRUE,
            'menu_name' => 'checkouts_index',
            'menu_parent' => 'financial_manage',
            'url' => 'financial/checkouts'
        ),
        'buy' => array(
            'title' => 'افزایش  اعتبار نقدی',
            'type' => 'user',
        ),
        'bank' => array(
            'title' => 'پرداخت',
            'type' => 'user'
        ),
        'report' => array(
            'title' => 'گزارشات افزایش اعتبار نقدی',
            'type' => 'user',
            'menu' => TRUE,
            'menu_name' => 'checkouts_report',
            'menu_parent' => 'financial_manage',
            'url' => 'financial/checkouts/report',
            'otherActions' => 'graph'
        ),
        'request' => array(
            'title' => 'درخواست',
            'type' => 'user'
        ),
        'admin' => array(
            'title' => 'متقاضیان تسویه حساب نقدی',
            'type' => 'admin',
            'menu' => TRUE,
            'menu_name' => 'checkouts_index',
            'menu_parent' => 'financial_manage',
            'url' => 'financial/checkouts/admin',
            'otherActions' => 'delete,pay,export,download,export_download'
        ),
    );

    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function actionIndex(){
        // get checkouts count
        $lastCheckout = Checkouts::model()->find(array(
            'condition' => 'user_id = :userId',
            'params' => array(
                ":userId" => Yii::app()->user->userID
            ),
            'order' => 'id DESC'
        ));

        $bankDetails = Users::model()->findByPk(Yii::app()->user->userID);
        $bankDetails->scenario = 'changeValue';
        $this->performAjaxValidation($bankDetails);
        if(isset($_POST['Users']) AND (is_null($lastCheckout) OR $lastCheckout->status != Checkouts::STATUS_REQUESTED)){
            $bankDetails->attributes = $_POST['Users'];
            if($bankDetails->save()){
                Yii::app()->user->setFlash('success',"اطلاعات حساب بانکی با موفقیت ثبت شد.");
                $this->refresh();
            }
        }

        $creditModel = new CreditsTransactions;
        $creditModel->scenario = 'userChange';
        $creditModel->price = 1000;
        $this->performAjaxValidationCharge($creditModel);
        if(isset($_POST['CreditsTransactions'])){
            $creditModel->attributes = $_POST['CreditsTransactions'];

            if($creditModel->save()){
                $this->redirect(Yii::app()->createAbsoluteUrl('financial/checkouts/buy?buyId='.$creditModel->buy_id));
            }
        }
        $creditModel->price = number_format($creditModel->price);

        $checkoutsModel = NULL;
        $minimumCredit = SiteOptions::getOption('minimum_credit');
        if(
            // if credit charge was more than minimum
            $this->currentUser->credit_charge >= $minimumCredit AND
            // if current user is not deActive
            intval($this->currentUser->planStatus()) AND
            // if last checkout is not set
            (is_null($lastCheckout) OR $lastCheckout->status != Checkouts::STATUS_REQUESTED)
        ){
            $checkoutsModel = new Checkouts;
            $checkoutsModel->price = number_format($minimumCredit);


            $plan = $this->currentUser->activePlan->plansBuys->plan;
            $discountSections = json_decode($plan->extension_discount_sections,TRUE);
            if(isset($discountSections['credits_buy']) AND $discountSections['credits_buy']){
                $wage = floatval($plan->extension_discount);
            }
            else {
                $wage = 0;
            }
            $checkoutsModel->reqPrice = number_format(ceil($minimumCredit * 100 / floatval(100 + $wage)));
            $checkoutsModel->wage = $wage;

            $this->performAjaxValidationCheckouts($checkoutsModel);
            if(isset($_POST['Checkouts'])){
                $checkoutsModel->attributes = $_POST['Checkouts'];

                $transaction = Yii::app()->db->beginTransaction();
                try {
                    if(!$checkoutsModel->save())
                        $transaction->rollback();
                    else {
                        $this->currentUser->scenario = 'changeValue';
                        $this->currentUser->credit_charge -= $checkoutsModel->price;
                        if(!$this->currentUser->save())
                            $transaction->rollback();
                        else
                            Yii::app()->user->setFlash('success',"درخواست تسویه حساب شما با موفقیت ارسال شد.");
                    }
                    $transaction->commit();
                } catch (Exception $ex) {
                    Yii::app()->user->setFlash('danger','خطا در هنگام ثبت!');
                }
                $this->refresh();
            }
        }

        $this->render('index',array(
            'bankDetails' => $bankDetails,
            'creditModel' => $creditModel,
            'checkoutsModel' => $checkoutsModel,
            'lastCheckout' => $lastCheckout
        ));
    }

    public function actionBuy($buyId){
        $buyModel = Buys::model()->findByPk($buyId);

        if($buyModel->status == Buys::STATUS_DOING)
            Yii::app()->user->setState('tracking_no',rand(100000, 999999));

        $creditsTransactionsDataProvider = new CActiveDataProvider('Buys',array(
            'criteria'=>array(
                'with' => 'credit',
                'limit' => 10,
                'order' => 'id DESC',
                'condition' => 'credit.user_id = :user AND type = :type AND status != :status AND gateway IS NOT NULL',
                'params' => array(
                    ':user' => Yii::app()->user->userID,
                    ':type' => Buys::TYPE_CREDIT_CHARGE,
                    ':status' => Buys::STATUS_DOING
                ),
            ),
            'pagination'=>array(
                'pageSize' => 10,
            ),
        ));

        $factorFields = json_decode($buyModel->details,true);
        $model = (object)array();

        if(isset($_POST['Buys'])){
            if(!isset($_POST['pass']) OR $_POST['pass'] != '12345')
                $this->redirect(Yii::app()->createAbsoluteUrl('financial/checkouts/bank/'.$buyModel->id));
            $buyModel->attributes = $_POST['Buys'];
            $transaction = Yii::app()->db->beginTransaction();
            try {
                if(!$buyModel->save()){
                    $transaction->rollback();
                }
                else {
                    $this->currentUser->scenario = 'changeValue';
                    $this->currentUser->credit_charge += $buyModel->credit->price;
                    $buyModel->credit->user_price = $this->currentUser->credit_charge;
                    $buyModel->credit->save();
                    $this->currentUser->save();
                }
                $transaction->commit();
            } catch (Exception $ex) {
                Yii::app()->user->setFlash('danger','خطا در هنگام ثبت!');
                $transaction->rollback();
            }
            $this->refresh();
        }

        if(isset($_GET['num']))
            $render = 'renderPartial';
        else
            $render = 'render';

        $this->$render('buy',array(
            'buyModel' => $buyModel,
            'creditsTransactionsDataProvider'=>$creditsTransactionsDataProvider,
            'factorFields' => $factorFields,
            'model' => $model,
        ));
    }

    public function actionBank($id){
        $buy = Buys::model()->findByPk($id);
        $this->render('bank',array(
            'buy' => $buy,
        ));
    }

    public function actionReport(){
        $criteria = Buys::model()->reportCriteria(FALSE);
        $criteria->compare('type',Buys::TYPE_CREDIT_CHARGE);
        $criteria->with[] = 'credit';
        $criteria->addCondition('gateway IS NOT NULL');
        $criteria->compare('credit.user_id',Yii::app()->user->userID);
        $criteria->group = 'credit.buy_id';

        $PlansBuysDataProvider = new CActiveDataProvider('Buys',array(
            'criteria'=> $criteria,
            'pagination'=>array(
                'pageSize' => 10,
            ),
        ));

        $this->render('//report/index',array(
            'title' => 'افزایش اعتبار نقدی',
            'PlansBuysDataProvider'=>$PlansBuysDataProvider,
            'graphUrl' => "financial/checkouts/graph"
        ));

    }

    public function actionGraph(){
        Buys::model()->chartMaker(Buys::TYPE_CREDIT_CHARGE);
    }

    protected function performAjaxValidation($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='bank-details-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected function performAjaxValidationCharge($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='credit-charge-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected function performAjaxValidationCheckouts($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='checkouts-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionRequest($price){
        $model = new Checkouts;
        $model->price = intval($price);
        $model->status = Checkouts::STATUS_REQUESTED;
        $model->save();
    }

    public function actionAdmin(){
        $model = new Checkouts('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Checkouts']))
            $model->attributes=$_GET['Checkouts'];

        $exportModel = new CheckoutsExports('search');
        $exportModel->unsetAttributes();  // clear any default values
        if(isset($_GET['CheckoutsExports']))
            $exportModel->attributes=$_GET['CheckoutsExports'];

        $this->render('admin',array(
            'model'=>$model,
            'exportModel' => $exportModel
        ));
    }

    public function actionDelete($id){
        $model = $this->loadModel($id);
        $model->scenario = 'failed';
        $model->status = Checkouts::STATUS_FAILED;

        if($model->save()){
            $model->user->scenario = 'changeValue';
            $model->user->credit_charge += $model->price;
            if(!$model->user->save()){
                $model->status = Checkouts::STATUS_REQUESTED;
                $model->save();
            }
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function actionPay($id){

        $model = $this->loadModel($id);

        $this->performAjaxValidation($model);

        if(isset($_POST['Checkouts'])) {
            $model->scenario = 'update';

            $model->attributes = $_POST['Checkouts'];

            $model->status = Checkouts::STATUS_DONE;

            $date = explode('-',$_POST['Checkouts']['pay_date']);

            $year = intval($date[0]);
            $month = intval($date[1]);
            $day = intval($date[2]);

            $time = explode(' ',$_POST['Checkouts']['pay_date']);
            $time = $time[1];

            $date = Yii::app()->jdate->toGregorian($year,$month,$day);

            $date = implode('-',$date);

            $date = strtotime($date." ".$time);

            $model->pay_date = $date;

            if($model->save()){
                Yii::app()->user->setFlash('success','تسویه حساب با موفقیت انجام شد.');
                $this->redirect('admin');
            }
            else{
                Yii::app()->user->setFlash('success','خطا در هنگام ثبت!');
            }
        }
        //$model->price = number_format($model->price);
        $this->render("pay",array(
            'model' => $model
        ));
    }

    public function actionExport(){
        $model = new Checkouts('search');
        $model->unsetAttributes();
        $model->attributes = $_GET['Checkouts'];

        $date = explode('-',$_GET['Checkouts']['req_date']);

        $year = intval($date[0]);
        $month = intval($date[1]);
        $day = intval($date[2]);

        $time = explode(' ',$_GET['Checkouts']['req_date']);
        $time = $time[1];

        $date = Yii::app()->jdate->toGregorian($year,$month,$day);

        $date = implode('-',$date);

        $date = strtotime($date." ".$time);

        $model->req_date = $date;

        $exportIds = $model->getExportIds();

        $export = new CheckoutsExports;

        if($exportIds AND $export->save()) {
            Checkouts::model()->updateAll(array('status' => Checkouts::STATUS_DOING, 'export_id' => $export->id),"id IN ($exportIds)");
            $criteria = new CDbCriteria;
            $criteria->compare("export_id",$export->id);
            $dataProvider = new CActiveDataProvider('Checkouts', array(
                'criteria' => $criteria
            ));

            $criteriaSum = new CDbCriteria;
            $criteriaSum->select = 'SUM(price) AS price';
            $criteriaSum->compare("export_id",$export->id);
            $criteriaSum->group = 'export_id';
            $checkouts = Checkouts::model()->find($criteriaSum);
            $export->price = $checkouts->price;
            $export->save();

            $this->renderPartial('_export_grid',array(
                'dataProvider' => $dataProvider,
                'export' => $export,
            ));

            echo json_encode(array(
                'result' => TRUE
            ));
        }
        else
            echo json_encode(array(
                'result' => FALSE
            ));

    }

    public function actionDownload($id,$type='export'){
        if($type == 'export')
            $ext = '.xlsx';
        elseif($type == 'import')
            $ext = '.html';
        $model = CheckoutsExports::model()->findByPk($id);
        $downloadType = $type.'_file';
        $file = $model->$downloadType;
        if(file_exists('protected/checkouts_'.$type.'/'.$file.$ext))
            Yii::app()->getRequest()->sendFile(time(). $ext, file_get_contents('protected/checkouts_'.$type.'/'.$file.$ext));
        else
            Yii::app()->user->setFlash('danger', 'متاسفانه خطائی رخ داد، لطفا دوباره امتحان کنید.');
    }

    public function actionExport_delete($id){
        $check = Checkouts::model()->updateAll(array('status' => Checkouts::STATUS_FAILED),array(
            'condition' => 'export_id = :exportId',
            'params' => array(':exportId' => $id)
        ));
        if($check){
            $export = CheckoutsExports::model()->findByPk($id);
            @unlink('protected/checkouts_export/'.$export->export_file.'.xlsx');
            @unlink('protected/checkouts_import/'.$export->import_file.'.html');
            $export->delete();
        }
    }

    public function actionImport(){
        $id =$_POST['id'];
        $model = CheckoutsExports::model()->findByPk($id);

        if(is_null($model)){
            echo json_encode(array(
                'result'=>false,
                'message'=> 'شناسه خروجی صحیح نمی باشد.'
            ));
            Yii::app()->end();
        }

        $file = uniqid();

        $uploadedFile = $_FILES['userFile']['tmp_name'];

        $ibanArray = array();

        $handle = fopen($uploadedFile, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $match = "";
                if( preg_match('/^IR\d{24}$/',trim($line),$match)){
                    $iban = substr($match[0],2);
                    if(trim($iban) === "" OR !in_array($iban,$ibanArray)) {
                        $ibanArray[] = $iban;
                        $checkout = Checkouts::model()->find(array(
                            'with' => 'user',
                            'condition' => 'user.iban = :iban AND t.status = :status',
                            'params' => array(':iban' => $iban,':status' => Checkouts::STATUS_DOING)
                        ));

                        //@todo change after kashani speak about it
                        if (!is_null($checkout)) {
                            $lastId = Checkouts::model()->find(array(
                                'select' => 'MAX(id) AS id'
                            ));
                            $lastId = $lastId->id;
                            $checkout->status = Checkouts::STATUS_DONE;
                            $checkout->tracking_no = 412345 + $lastId;
                            $checkout->gateway = 'نامشخص';
                            $checkout->pay_date = time();

                            $checkout->save();
                        }
                    }
                }

            }

            fclose($handle);
        } else {
            echo json_encode(array(
                'result'=>false,
                'message'=>'فایل ایمپورت شده صحیح نمی باشد.'
            ));
        }


        // others maybe their IBAN is incorrect
        $incorrect = Checkouts::model()->findAll('export_id = :exportId AND status = :status',array(
            ':exportId' => $id,
            ':status' => Checkouts::STATUS_DOING
        ));

        foreach($incorrect as $other) {
            $other->scenario = 'incorrect_iban';
            $other->status = Checkouts::STATUS_INCORRECT_IBAN;

            if ($other->save()) {
                $other->user->scenario = 'changeValue';
                $other->user->credit_charge += $other->price;
                if (!$other->user->save()) {
                    $other->status = Checkouts::STATUS_REQUESTED;
                    $other->save();
                }
            }
        }

        /*Checkouts::model()->updateAll(array('status'=>Checkouts::STATUS_INCORRECT_IBAN),'export_id = :exportId AND status = :status',array(
            ':exportId' => $id,
            ':status' => Checkouts::STATUS_DOING
        ));*/

        if(move_uploaded_file($uploadedFile,'protected/checkouts_import/'.$file.'.txt')){
            $model->import_file = $file;
            if($model->save()){
                echo json_encode(array(
                    'result'=>true,
                    'fileName'=>$file,
                    'ID'=>'0'
                ));
            }
        }
    }

    private function _htmlImport($uploadedFile,$id){
        $contents = file_get_contents($uploadedFile);
        $purifier = new CHtmlPurifier();
        $contents = $purifier->purify($contents);

        $doc = new DOMDocument();
        $doc->encoding = 'UTF-8';
        $doc->loadHTML($contents);

        $body = $doc->getElementsByTagName('tbody');
        // check tbody
        if($body->length !== 1){
            echo json_encode(array(
                'result'=>false,
                'message'=>'فایل ایمپورت شده صحیح نمی باشد.'
            ));
            Yii::app()->end();
        }

        $rows = $body->item(0)->getElementsByTagName('tr');
        $row = $rows->item(0)->getElementsByTagName('td');
        //check columns length
        if($row->length !== 11){
            echo json_encode(array(
                'result'=>false,
                'message'=>'فایل ایمپورت شده صحیح نمی باشد.'
            ));
            Yii::app()->end();
        }

        $importId = $row->item(10)->textContent;
        if(intval($id) !== intval($importId)){
            echo json_encode(array(
                'result'=>false,
                'message'=>'فایل ایمپورت شده مطابقت ندارد.'
            ));
            Yii::app()->end();
        }

        foreach($rows as $row){
            $columns = $row->getElementsByTagName('td');

            $checkoutId = $columns->item(8)->textContent;
            $checkout = Checkouts::model()->findByPk($checkoutId);
            if(is_null($checkout))
                continue;

            $checkoutStatus = trim($columns->item(7)->textContent);
            if($checkout->user->iban == trim($columns->item(4)->textContent)) {
                if($checkoutStatus == 'انتقال داده شده')
                    $checkout->status = Checkouts::STATUS_DONE;
                elseif($checkoutStatus == 'ناموفق')
                    $checkout->status = Checkouts::STATUS_FAILED;
                else
                    $checkout->status = Checkouts::STATUS_CANCELED;

                $checkout->tracking_no = trim($columns->item(4)->textContent);
                $checkout->gateway = trim($columns->item(9)->textContent);
                $checkout->pay_date = iWebHelper::jalaliToTime(trim($columns->item(6)->textContent));

                $checkout->save();
            }
        }
    }

    public function loadModel($id){
        $model = Checkouts::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
}