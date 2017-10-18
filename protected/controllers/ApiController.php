<?php
Yii::import('admins.models.*');
class ApiController extends Controller
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'RestAccessControl + getLastVer,downloadApp,checkNumber',
            'RestUserAccessControl + changes, getList, getFilteredList, create, update, upload, payment, inquiryPayment',
//			'RestAdminAccessControl +'
        );
    }

    public function actionChanges()
    {
        $allModules = [
            'Ceremony',
            'Place',
            'Notification',
            'Ticket',
            'Filter',
        ];

        $criteria = new CDbCriteria();
        $criteria->distinct = true;
        // Set Last ID
        if (isset($_POST['lastId']) && !empty($_POST['lastId']) && $lastId = (int)$_POST['lastId']) {
            $criteria->addCondition('t.id > :last_id');
            $criteria->params[':last_id'] = $lastId;
        }
        //
        // Set Function
        $validQueries = array(
            'findAll',
            'count',
        );
        if (isset($_POST['query']) && !empty($_POST['query'])) {
            $query = strtolower(trim($_POST['query']));
            if (in_array($query, $validQueries))
                $func = $query;
            else
                $func = 'findAll';
        } else
            $func = 'findAll';
        //
        $entity = isset($_POST['entity']) ? $_POST['entity'] : false;
        if ($entity && trim($entity))
            $criteria->compare("module", $entity);
        else
            $criteria->addInCondition('module', $allModules);

        $list = Log::model()->{$func}($criteria);
        if ($list) {
            $this->_sendResponse(200, CJSON::encode(['status' => true, 'list' => $list]), 'application/json');
        } else
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'اطلاعاتی برای دریافت موجود نیست.']), 'application/json');
    }

    public function actionGetList()
    {
        if (isset($_POST['entity']) && $entity = $_POST['entity']) {
            $criteria = new CDbCriteria();
            // Set Last ID
            if (isset($_POST['lastId']) && !empty($_POST['lastId']) && $lastId = (int)$_POST['lastId']) {
                $criteria->addCondition('t.id > :last_id');
                $criteria->params[':last_id'] = $lastId;
            }
            // set LIMIT and OFFSET in Query
            if (isset($_POST['limit']) && !empty($_POST['limit']) && $limit = (int)$_POST['limit']) {
                $criteria->limit = $limit;
                if (isset($_POST['offset']) && !empty($_POST['offset']) && $offset = (int)$_POST['offset'])
                    $criteria->offset = $offset;
            }
            //
            // Set Function
            $validQueries = array(
                'find',
                'findAll',
                'count',
                'delete',
                'deleteAll',
            );

            //
            if (isset($_POST['query']) && !empty($_POST['query'])) {
                $query = strtolower(trim($_POST['query']));
                if (in_array($query, $validQueries))
                    $func = $query;
                else
                    $func = 'findAll';
            } else
                $func = 'findAll';
            // Set Pk
            if (isset($_POST['pk']) && !empty($_POST['pk']) && $pk = (int)$_POST['pk']) {
                $criteria->params[':pk'] = $pk;
                $func = 'find';
                $criteria->limit = 1;
                $criteria->offset = 0;
            }
            //
            switch (trim($entity)) {
                case 'Place':
                    if ($func != 'delete' && $func != 'deleteAll') {
                        if (isset($pk) && !empty($pk))
                            $criteria->addCondition(UsersPlaces::model()->tableSchema->primaryKey . ' = :pk');
                        $list = UsersPlaces::model()->{$func}($criteria);
                    } else
                        $list = null;
                    break;
                case 'Ceremony':
                    if (isset($pk) && !empty($pk))
                        $criteria->addCondition(Events::model()->tableSchema->primaryKey . ' = :pk');
                    if (isset($_POST['ceremony_public']) && $_POST['ceremony_public'] == true)
                        $criteria->compare('ceremony_public', 1);
                    if ((isset($_POST['myself']) && $_POST['myself'] == true) || $func == 'delete' || $func == 'deleteAll') {
                        $criteria->addCondition('user_id = :user_id');
                        $criteria->params[':user_id'] = $this->loginArray['userID'];
                    }
                    $list = Events::model()->{$func}($criteria);
                    break;
                case 'Ticket':
                    Yii::app()->getModule('tickets');
                    if (isset($pk) && !empty($pk))
                        $criteria->addCondition(Tickets::model()->tableSchema->primaryKey . ' = :pk');
                    $criteria->addCondition('user_id = :user_id');
                    $criteria->params[':user_id'] = $this->loginArray['userID'];
                    $list = Tickets::model()->with('ticketsContents')->{$func}($criteria);
                    break;
                case 'Notification':
                    if ($func != 'delete' && $func != 'deleteAll') {

                        Yii::app()->getModule('notifications');
                        if (isset($pk) && !empty($pk))
                            $criteria->addCondition(Notifications::model()->tableSchema->primaryKey . ' = :pk');
                        $criteria->addCondition('send_date < :time AND  expire_date > :time');
                        $criteria->params[':time'] = time();
                        $list = Notifications::model()->{$func}($criteria);
                    } else
                        $list = null;
                    break;
                case 'Filter':
                    if (isset($pk) && !empty($pk))
                        $criteria->addCondition(EventFilters::model()->tableSchema->primaryKey . ' = :pk');
                    $criteria->addCondition('user_id = :user_id');
                    $criteria->params[':user_id'] = $this->loginArray['userID'];
                    $list = EventFilters::model()->{$func}($criteria);
                    break;
                case 'Transaction':
                    AppTransactions::model()->deleteAll('date < :deleteDate', array(':deleteDate' => (time() - 60 * 24 * 60 * 60)));
                    if (isset($pk) && !empty($pk))
                        $criteria->addCondition(AppTransactions::model()->tableSchema->primaryKey . ' = :pk');
                    $criteria->addCondition('user_id = :user_id');
                    $criteria->params[':user_id'] = $this->loginArray['userID'];
                    $list = AppTransactions::model()->{$func}($criteria);
                    break;
                default:
                    $list = array();
                    break;
            }
            if ($list) {
                $this->_sendResponse(200, CJSON::encode(['status' => true, 'list' => $list]), 'application/json');
            } else
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'اطلاعاتی برای دریافت موجود نیست.']), 'application/json');
        }
    }

    public function actionGetFilteredList()
    {
        $filter = new EventFilters();
        $criteria = new CDbCriteria();
        // set LIMIT and OFFSET in Query
        if (isset($_POST['limit']) && !empty($_POST['limit']) && $limit = (int)$_POST['limit']) {
            $criteria->limit = $limit;
            if (isset($_POST['offset']) && !empty($_POST['offset']) && $offset = (int)$_POST['offset'])
                $criteria->offset = $offset;
        }
        //
        $func = 'findAll';
        // set Filter
        if (isset($_POST['filterId']) && !empty($_POST['filterId'])) {
            $filter = EventFilters::model()->findByAttributes(array(
                'user_id' => $this->loginArray['userID'],
                'id' => $_POST['filterId']
            ));
            if ($filter === null)
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'فیلتر موردنظر موجود نیست.']), 'application/json');
            if ($filter->filter_type == 'public') {
                $criteria->addCondition('ceremony_public = 1 OR (creator_type = :creator_type AND creator_id = :user_id)');
                $criteria->params[':creator_type'] = "user";
                $criteria->params[':user_id'] = $this->loginArray['userID'];
            } elseif ($filter->filter_type == 'favorite') {
                $criteria->addCondition('ceremony_public = 1 OR (creator_type = :creator_type AND creator_id = :user_id)');
                $criteria->params[':creator_type'] = "user";
                $criteria->params[':user_id'] = $this->loginArray['userID'];
                // @todo مراسم های خصوصی هم نشون بده
            }
            $filter->loadSearchFields();
        } elseif (isset($_POST['Filter'])) {
            $_POST['Filter'] = CJSON::decode($_POST['Filter']) ? CJSON::decode($_POST['Filter']) : $_POST['Filter'];
            $filter = new EventFilters();
            $filter->attributes = $_POST['Filter'];
//			$filter->user_id = $this->loginArray['userID'];
            $filter->loadSearchFields();
            $criteria->addCondition('ceremony_public = 1 OR (creator_type = :creator_type AND creator_id = :user_id)');
            $criteria->params[':creator_type'] = "user";
            $criteria->params[':user_id'] = $this->loginArray['userID'];
        }
        $filter->searchCriteria($criteria);
        $list = Events::model()->{$func}($criteria);
        if ($list) {
            $this->_sendResponse(200, CJSON::encode(['status' => true, 'list' => $list]), 'application/json');
        } else
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'اطلاعاتی برای دریافت موجود نیست.']), 'application/json');
    }

    /**
     * Create Model from Entity
     */
    public function actionCreate()
    {
        if (isset($_POST['entity']) && $entity = ucfirst(strtolower(trim($_POST['entity'])))) {
            if (isset($_POST[$entity])) {
                if (!is_array($_POST[$entity]))
                    $_POST[$entity] = CJSON::decode($_POST[$entity]);
                switch ($entity) {
                    case 'Ceremony':
                        $model = new Events();
                        $model->attributes = $_POST[$entity];
                        $model->creator_type = $this->loginArray['type'];
                        $model->creator_id = $this->loginArray['userID'];
                        if ($model->save()) {
                            $results = $model->calculatePrice($model->user->activePlan->plansBuys->plan->extension_discount);
                            $results['maxShowMoreThanDefault'] = SiteOptions::getOption('show_event_more_than_default');
                            $results['showStartDate'] = $model->start_date_run;
                            $results['longDaysRun'] = $model->long_days_run;
                            $results['showStartTime'] = $model->showStartTime;
                            $results['showEndTime'] = $model->showEndTime;
                            $results['moreDays'] = $model->more_days;
                            $this->_sendResponse(200, CJSON::encode(['status' => true, 'entityId' => $model->id,
                                'invoice' => $results,
                                'message' => 'پیش فاکتور محاسبه گردید.']), 'application/json');
                        }
                        break;
                    case 'Filter':
                        $model = new EventFilters();
                        if (!$model->checkFilterCreatePermission($this->loginArray['userID']))
                            $this->_sendResponse(200, CJSON::encode([
                                'status' => false,
                                'message' => 'تعداد فیلتر ثبت شده بیشتر از حد رایگان است. جهت ثبت فیلتر جدید باید هزینه پرداخت کنید.',
                                'payApi' => Yii::app()->createAbsoluteUrl('/api/payment/?entity=Filter')
                            ]), 'application/json');
                        $model->attributes = $_POST[$entity];
                        $model->user_id = $this->loginArray['userID'];
                        if ($model->save())
                            $this->_sendResponse(200, CJSON::encode(['status' => true, 'entityId' => $model->id, 'message' => 'فیلتر با موفقیت ثبت شد.']), 'application/json');
                        break;
                    case 'Ticket':
                        Yii::app()->getModule('tickets');
                        $model = new Tickets();
                        if ($this->loginArray['type'] == 'admin')
                            $model->scenario = 'admin_insert';
                        $model->attributes = $_POST[$entity];
                        $model->status = Tickets::STATUS_NO_REPLY;
                        $model->user_id = $this->loginArray['userID'];
                        if ($model->save()) {
                            $ticketsContentModel = new TicketsContent;
                            $ticketsContentModel->attributes = $_POST[$entity];
                            $ticketsContentModel->ticket_id = $model->id;
                            $ticketsContentModel->text = $model->text;
                            $ticketsContentModel->file = $model->file;
                            $ticketsContentModel->save();
                            $this->_sendResponse(200, CJSON::encode(['status' => true, 'entityId' => $model->id, 'message' => 'تیکت با موفقیت ارسال شد.']), 'application/json');
                        }
                        break;
                    default:
                        $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'موجودیت مورد نظر وجود ندارد.']), 'application/json');
                        break;
                }
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'متاسفانه در ثبت اطلاعات خطایی رخ داده است.', 'errors' => $this->implodeErrors($model)]), 'application/json');
            }
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'اطلاعات ثبت ارسال نشده است.']), 'application/json');
        }
        $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'مقدار entity نمی تواند خالی باشد.']), 'application/json');
    }

    /**
     * Update Model from Entity
     */
    public function actionUpdate()
    {
        if (isset($_POST['entity']) && $entity = ucfirst(strtolower(trim($_POST['entity'])))) {
            if (isset($_POST[$entity])) {
                if ($entity != "User" && !isset($_POST['entityId']))
                    $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'مقدار entityId ارسال نشده است.']), 'application/json');

                if (!is_array($_POST[$entity]))
                    $_POST[$entity] = CJSON::decode($_POST[$entity]);
                switch ($entity) {
                    case 'Ceremony':
                        $model = Events::model()->findByPk($_POST['entityId']);
                        if ($model === null)
                            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'مراسم مورد نظر وجود ندارد.']), 'application/json');
                        $currentPoster = $model->ceremony_poster;
                        $model->unsetInvalidAttributes($_POST[$entity]);
                        $model->attributes = $_POST[$entity];
                        $model->creator_type = $this->loginArray['type'];
                        $model->creator_id = $this->loginArray['userID'];
                        $model->status = Events::STATUS_PENDING;
                        if ($model->ceremony_poster != $currentPoster)
                            $model->deletePoster($currentPoster);
                        if ($model->save()) {
                            $model->plan_off = $model->user->activePlan->plansBuys->plan->extension_discount;
                            $results = $model->calculatePrice($model->plan_off);
                            $results['maxShowMoreThanDefault'] = SiteOptions::getOption('show_event_more_than_default');
                            $results['showStartDate'] = $model->start_date_run;
                            $results['longDaysRun'] = $model->long_days_run;
                            $results['showStartTime'] = $model->showStartTime;
                            $results['showEndTime'] = $model->showEndTime;
                            $results['moreDays'] = $model->more_days;
                            $model->default_show_price = $results['defaultPrice'];
                            $model->more_than_default_show_price = $results['showMoreThanDefaultPrice'];
                            $model->more_than_default_show_price = $results['showMoreThanDefaultPrice'];
                            $model->tax = $results['thisEventTax'];
                            @$model->save(false);
                            $this->_sendResponse(200, CJSON::encode(['status' => true, 'entityId' => $model->id,
                                'invoice' => $results, 'message' => 'پیش فاکتور محاسبه گردید.']), 'application/json');
                        }
                        break;
                    case 'Filter':
                        $model = EventFilters::model()->findByPk($_POST['entityId']);
                        if ($model === null)
                            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'مراسم مورد نظر وجود ندارد.']), 'application/json');
                        $model->unsetInvalidAttributes($_POST[$entity]);
                        $model->attributes = $_POST[$entity];
                        $model->user_id = $this->loginArray['userID'];
                        if ($model->save())
                            $this->_sendResponse(200, CJSON::encode(['status' => true, 'entityId' => $model->id, 'message' => 'فیلتر با موفقیت به روزرسانی شد.']), 'application/json');
                        break;
                    case 'User':
                        Yii::app()->getModule('users');
                        $model = Users::model()->findByPk($this->loginArray['userID']);
                        if ($model === null)
                            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'کاربر مورد نظر وجود ندارد.']), 'application/json');
                        $currentAvatar = $model->avatar;
                        $model->unsetInvalidAttributes($_POST[$entity]);
                        $model->attributes = $_POST[$entity];
                        if ($model->avatar != $currentAvatar)
                            $model->deleteFile('avatar', $currentAvatar);
                        if ($model->save())
                            $this->_sendResponse(200, CJSON::encode(['status' => true, 'entityId' => $model->id, 'message' => 'اطلاعات با موفقیت به روزرسانی شد.', 'model' => $model]), 'application/json');
                        break;
                    default:
                        $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'موجودیت مورد نظر وجود ندارد.']), 'application/json');
                        break;
                }
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'متاسفانه در به روزرسانی اطلاعات خطایی رخ داده است.', 'errors' => $this->implodeErrors($model)]), 'application/json');
            }
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'اطلاعات به روزرسانی ارسال نشده است.']), 'application/json');
        }
        $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'مقدار entity نمی تواند خالی باشد.']), 'application/json');
    }

    public function actionUpload()
    {
        if (isset($_POST['entity']) && $entity = ucfirst(strtolower(trim($_POST['entity'])))) {
            $entityUploadClass = CUploadedFile::getInstanceByName($entity);
            if ($entityUploadClass->getHasError())
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'درآپلود فایل خطایی رخ داده است.',
                    'errors' => $entityUploadClass->getError()]), 'application/json');
            switch ($entity) {
                case 'Poster':
                    $path = Yii::getPathOfAlias('webroot') . Events::$path;
                    $link = Yii::app()->baseUrl . Events::$path . $entityUploadClass->getName();
                    break;
                case 'Profile':
                    Yii::import('users.models.Users');
                    $path = realpath(dirname(Yii::app()->request->scriptFile) . '/..' . Users::$avatarPath);
                    $link = Yii::app()->getBaseUrl(true) . Users::$avatarPath . $entityUploadClass->getName();
                    break;
                default:
                    $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'موجودیت مورد نظر وجود ندارد.']), 'application/json');
                    break;
            }
            if (!is_dir($path))
                mkdir($path);
            if (!$entityUploadClass->getHasError() && $entityUploadClass->saveAs($path . $entityUploadClass->getName()))
                $this->_sendResponse(200, CJSON::encode(['status' => true,
                    'filename' => $entityUploadClass->getName(),
                    'link' => $link,
                    'message' => 'فایل با موفقیت آپلود شد.']), 'application/json');
        }
        $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'مقدار entity نمی تواند خالی باشد.']), 'application/json');
    }

    public function actionPayment()
    {
        if (isset($_GET['entity']) && $entity = ucfirst(strtolower(trim($_GET['entity'])))) { // pay open
            switch ($entity) {
                case 'Filter':
                    $transaction = new AppTransactions();
                    $transaction->user_id = $this->loginArray['userID'];
                    $transaction->amount = SiteOptions::getOption('additional_filter_cost');
                    $transaction->description = 'پرداخت هزینه جهت ثبت فیلتر اضافه';
                    $transaction->date = time();
                    $transaction->model_name = 'EventFilters';
                    $transaction->model_id = null;
                    $transaction->bank_name = "ملت";
                    $transaction->newOrderId();
                    if ($transaction->save()) {
                        $Amount = doubleval($transaction->amount) * 10;
                        $CallbackURL = Yii::app()->getBaseUrl(true) . '/api/verifyPayment';
                        $result = Yii::app()->Payment->PayRequest($Amount, $transaction->order_id, $CallbackURL);
                        if (!$result['error']) {
                            $transaction->ref_id = $result['responseCode'];
                            $transaction->save();
                            $ReferenceId = $result['responseCode'];
                            $this->_sendResponse(200, CJSON::encode(['status' => true,
                                'urlPay' => Yii::app()->createAbsoluteUrl("/api/paymentPage?ref={$ReferenceId}"),
                                'transactionId' => $transaction->id,
                            ]), 'application/json');
                        } else {
                            $this->_sendResponse(200, CJSON::encode(['status' => false,
                                'transactionId' => $transaction->id,
                                'message' => Yii::app()->Payment->getResponseText($result['responseCode']),
                            ]), 'application/json');
                        }
                    }
                    $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'متاسفانه مشکلی در ثبت اطلاعات تراکنش بوجود آمده است! لطفا مجددا تلاش فرمایید.']), 'application/json');
                    break;
                default:
                    $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'موجودیت مورد نظر وجود ندارد.']), 'application/json');
                    break;
            }
        } else if (isset($_POST['entity']) && $entity = ucfirst(strtolower(trim($_POST['entity'])))) {
            if (!isset($_POST['entityId']) && (int)$_POST['entityId'])
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'مقدار entityId ارسال نشده است.']), 'application/json');
            $entityId = $_POST['entityId'];
            switch ($entity) {
                case 'Ceremony':
                    $model = Events::model()->findByPk($_POST['entityId']);
                    if ($model === null)
                        $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'موجودیت مورد نظر وجود ندارد.']), 'application/json');
                    if ($model->status == Events::STATUS_ACCEPTED)
                        $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'تراکنش مربوط به این موجودیت قبلا پرداخت شده و تایید گردیده است.']), 'application/json');
                    $transaction = new AppTransactions();
                    $transaction->user_id = $this->loginArray['userID'];
                    $transaction->amount = $model->getPrice();
                    $transaction->description = 'پرداخت هزینه فاکتور مراسم با شناسه #' . $entityId;
                    $transaction->date = time();
                    $transaction->model_name = 'Events';
                    $transaction->model_id = $entityId;
                    $transaction->bank_name = "ملت";
                    $transaction->newOrderId();
                    if ($transaction->save()) {
                        $Amount = doubleval($transaction->amount) * 10;
                        $CallbackURL = Yii::app()->getBaseUrl(true) . '/api/verifyPayment?model=Events&id=' . $model->id;
                        $result = Yii::app()->Payment->PayRequest($Amount, $transaction->order_id, $CallbackURL);
                        if (!$result['error']) {
                            $transaction->ref_id = $result['responseCode'];
                            $transaction->save();
                            $ReferenceId = $result['responseCode'];
                            $this->_sendResponse(200, CJSON::encode(['status' => true,
                                'urlPay' => Yii::app()->createAbsoluteUrl("/api/paymentPage?ref={$ReferenceId}"),
                                'transactionId' => $transaction->id,
                            ]), 'application/json');
                        } else {
                            $this->_sendResponse(200, CJSON::encode(['status' => false,
                                'transactionId' => $transaction->id,
                                'message' => Yii::app()->Payment->getResponseText($result['responseCode']),
                            ]), 'application/json');
                        }
                    }
                    $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'متاسفانه مشکلی در ثبت اطلاعات تراکنش بوجود آمده است! لطفا مجددا تلاش فرمایید.']), 'application/json');
                    break;
                default:
                    $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'موجودیت مورد نظر وجود ندارد.']), 'application/json');
                    break;
            }
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'متاسفانه مشکلی در ثبت اطلاعات تراکنش بوجود آمده است! لطفا مجددا تلاش فرمایید.']), 'application/json');
        }
        $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'مقدار entity نمی تواند خالی باشد.']), 'application/json');
    }

    public function actionPaymentPage()
    {
        $this->layout = '//layouts/public';
        if (isset($_GET['ref']) && !empty($_GET['ref'])) {
            $refId = $_GET['ref'];
            $transaction = AppTransactions::model()->findByAttributes(array('ref_id' => $refId));
            if ($transaction === null)
                throw new CHttpException(404, 'تراکنش موردنظر موجود نمی باشد.');
            $this->render('ext.MellatPayment.views._redirect', array(
                'status' => $transaction->status,
                'ReferenceId' => $refId
            ));
        }
    }

    public function actionVerifyPayment()
    {
        $this->layout = '//layouts/public';
        $transaction = null;
        $result = null;
        if (isset($_POST['RefId']))
            $transaction = AppTransactions::model()->findByAttributes(array('ref_id' => $_POST['RefId']));
        else if (isset($_GET['id']) && isset($_GET['model']))
            $transaction = AppTransactions::model()->findByAttributes(array('model_name' => $_GET['model'], 'model_id' => $_GET['id']));

        if ($transaction === null)
            throw new CHttpException(404, 'تراکنش مورد نظر موجود نمی باشد.');
        if ($transaction->status != AppTransactions::TRANSACTION_PAID) {
            if ($_POST['ResCode'] == 0) {
                $result = Yii::app()->Payment->VerifyRequest($transaction->order_id, $_POST['SaleOrderId'], $_POST['SaleReferenceId']);
            }
            if ($result != NULL) {
                $RecourceCode = (!is_array($result) ? $result : $result['responseCode']);
                if ($RecourceCode == 0) {
                    $transaction->status = AppTransactions::TRANSACTION_PAID;
                    // Settle Payment
                    $settle = Yii::app()->Payment->SettleRequest($transaction->order_id, $_POST['SaleOrderId'], $_POST['SaleReferenceId']);
                    if ($settle)
                        $transaction->settle = 1;
                }
            } else {
                $RecourceCode = $_POST['ResCode'];
            }
            $transaction->res_code = $RecourceCode;
            $transaction->sale_reference_id = isset($_POST['SaleReferenceId']) ? $_POST['SaleReferenceId'] : null;
            if ($transaction->save()) {
                switch ($transaction->model_name) {
                    case 'Events':
                        $model = Events::model()->findByPk($transaction->model_id);
                        $model->status = Events::STATUS_ACCEPTED;
                        $model->confirm_date = time();
                        $model->show_start_time = $model->showStartTime;
                        $model->show_end_time = $model->showEndTime;
                        $model->save(false);
                        break;
                    default:
                        break;
                }
                $msg = 'پرداخت با موفقیت انجام شد.';
            } else
                $msg = 'در فرآیند پرداخت مشکلی بوجود آمده است. لطفا با بخش پشتیبانی تماس بگیرید.';
        } else
            $msg = 'پرداخت با موفقیت انجام شد.';
        $this->render('verify', array(
            'status' => $transaction->status,
            'message' => $msg
        ));
    }

    public function actionInquiryPayment()
    {
        if (isset($_POST['id']) && $id = $_POST['id']) {
            $details = null;
            $message = null;
            $transaction = AppTransactions::model()->findByPk((int)$id);
            if ($transaction === null)
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'تراکنش موردنظر یافت نشد.']), 'application/json');
            $entity = $transaction->model_name;
            $entityId = $transaction->model_id;
            switch ($entity) {
                case 'Events':
                    $model = Events::model()->findByPk($entityId);
                    $message = $transaction->status == AppTransactions::TRANSACTION_PAID ?
                        'مراسم شما با موفقیت در گُهر ثبت شد و در زمان مقرر، خودکار نمایش داده میشود.' :
                        'مراسم شما به علت عدم پرداخت وجه در گُهر ثبت نشد و به عنوان پیش نویس ذخیره شد.';
                    $details = [
                        'status' => $transaction->status,
                        'statusLabel' => $transaction->getStatusLabel(),
                        'trackingCode' => $transaction->sale_reference_id,
                        'transactionId' => $transaction->id,
                        'bankName' => $transaction->bank_name,
                        'paymentAmount' => $transaction->amount,
                        'date' => $transaction->date,
                        'startDateRun' => $model->start_date_run,
                        'longDaysRun' => $model->long_days_run,
                        'showStartTime' => $model->show_start_time,
                        'showEndTime' => $model->show_end_time,
                        'moreDays' => $model->more_days,
                        'subject1' => $model->subject1,
                        'subject2' => $model->subject2,
                        'conductor1' => $model->conductor1,
                        'conductor2' => $model->conductor2
                    ];
                    break;
                default:
                    $transaction = null;
                    $model = null;
                    break;
            }
            if ($model === null)
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'موجودیت موردنظر یافت نشد.']), 'application/json');
            $this->_sendResponse(200, CJSON::encode([
                'status' => $transaction->status == AppTransactions::TRANSACTION_PAID ? true : false,
                'transactionDetail' => $details,
                'message' => $message
            ]), 'application/json');
        } else
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'پارامتر های مورد نیاز ارسال نشده است.']), 'application/json');
    }

    /**************************************************** Base Actions ***********************************************************/
    public function actionGetLastVer()
    {
        if (isset($_POST['version']) && isset($_POST['sim'])) {
            $baseLine = SiteOptions::model()->findByAttributes(['name' => 'base_line']);
            $lastVer = SiteOptions::model()->findByAttributes(['name' => 'app_version']);
            if ($_POST['version'] == $lastVer->value)
                $this->_sendResponse(200, CJSON::encode(['status' => true, 'message' => 'نسخه نرم افزار به روز می باشد.',
                    'serverTime' => time(),
                    'baseLine' => $baseLine ? $baseLine->value : false]), 'application/json');
            else {
                $fileName = 'gohar-v' . $lastVer->value . '.apk';
                $downloadToken = DownloadTokens::model()->findByAttributes(['app_version' => $lastVer->value, 'sim' => $_POST['sim']]);
                if (!$downloadToken) {
                    $downloadToken = new DownloadTokens();
                    $downloadToken->app_version = $lastVer->value;
                    $downloadToken->sim = $_POST['sim'];
                    $downloadToken->request_time = time();
                    $downloadToken->token = sha1(md5($downloadToken->app_version . $downloadToken->sim . time()));
                    $downloadToken->save();
                    $copyFileName = 'gohar-v' . $lastVer->value . '-' . $downloadToken->request_time . '.apk';
                    if (file_exists(Yii::getPathOfAlias('webroot') . '/uploads/app/' . $fileName))
                        @copy(Yii::getPathOfAlias('webroot') . '/uploads/app/' . $fileName, Yii::getPathOfAlias('webroot') . '/temp/' . $copyFileName);
                } elseif ($downloadToken && $downloadToken->request_time < (time() - (24 * 60 * 60))) {
                    $copyFileName = 'gohar-v' . $lastVer->value . '-' . $downloadToken->request_time . '.apk';
                    @unlink(Yii::getPathOfAlias('webroot') . '/temp/' . $copyFileName);
                    $downloadToken->delete();
                    $downloadToken = new DownloadTokens();
                    $downloadToken->app_version = $lastVer->value;
                    $downloadToken->sim = $_POST['sim'];
                    $downloadToken->request_time = time();
                    $downloadToken->token = sha1(md5($downloadToken->app_version . $downloadToken->sim . time()));
                    $downloadToken->save();
                    $copyFileName = 'gohar-v' . $lastVer->value . '-' . $downloadToken->request_time . '.apk';
                    if (file_exists(Yii::getPathOfAlias('webroot') . '/uploads/app/' . $fileName))
                        @copy(Yii::getPathOfAlias('webroot') . '/uploads/app/' . $fileName, Yii::getPathOfAlias('webroot') . '/temp/' . $copyFileName);
                }
                $fileLink = Yii::app()->createAbsoluteUrl('/api/downloadApp/' . $downloadToken->token);
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'newVersionLink' => $fileLink,
                    'serverTime' => time(), 'versionName' => $lastVer->value,
                    'baseLine' => $baseLine ? $baseLine->value : false]), 'application/json');
            }
        } elseif (!isset($_POST['version']))
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'مقدار نسخه فعلی ارسال نشده است.']), 'application/json');
        elseif (!isset($_POST['sim']))
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'شماره سیم کارت ارسال نشده است.']), 'application/json');
    }

    public function actionDownloadApp($token)
    {
        $downloadToken = DownloadTokens::model()->findByAttributes(['token' => $token]);
        if ($downloadToken && $downloadToken->request_time > (time() - (26 * 60 * 60))) {
            $copyFileName = 'gohar-v' . $downloadToken->app_version . '-' . $downloadToken->request_time . '.apk';
            if (!file_exists(Yii::getPathOfAlias('webroot') . '/temp/' . $copyFileName)) {
                $downloadToken->delete();
                $this->_sendResponse(200, CJSON::encode(['getBaseLine' => [['status' => false, 'message' => 'نسخه جدید برنامه در سرور موجود نیست.لطفا مجددا درخواست کنید.']]]), 'application/json');
            }
            $fileLink = Yii::app()->createAbsoluteUrl('/temp/' . $copyFileName);
            $this->_sendResponse(200, CJSON::encode(['status' => true, 'directLink' => $fileLink]), 'application/json');
        }
        if ($downloadToken)
            $downloadToken->delete();
        $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'لینک منقضی شده است.']), 'application/json');
    }

    public function actionCheckNumber()
    {
        if (isset($_POST['token']) && isset($_POST['sim']) && isset($_POST['activateCode'])) {
            $baseLine = SiteOptions::model()->findByAttributes(['name' => 'base_line']);
            Yii::import('users.models.*');
//			$_POST['sim'] = strpos($_POST['sim'], '0') === 0?$_POST['sim']:'0' . $_POST['sim'];
//			$sim = strpos($_POST['sim'], '0') === 0?substr($_POST['sim'], 1):$_POST['sim'];
            $sim = $_POST['sim'];
            // Delete Old Activate messages
            $criteria = new CDbCriteria();
            $criteria->addCondition('date <= :date');
            $criteria->params[':date'] = time() - 10 * 60;
            $criteria->order = 'date DESC';
            TextMessagesReceive::model()->deleteAll($criteria);
            //
            $criteria = new CDbCriteria();
            $criteria->compare('text', 'GoharActivate', true);
            $criteria->compare('sender', $sim, true);
            if ($baseLine)
                $criteria->compare('t.to', $baseLine->value);
            $criteria->addCondition('date >= :date');
            $criteria->params[':date'] = time() - 10 * 60;
            $criteria->order = 'date DESC';
            $messages = TextMessagesReceive::model()->findAll($criteria);
            $flag = false;
            if ($messages) {
                foreach ($messages as $message)
                    if ($message->text === $_POST['activateCode'])
                        $flag = true;
            }
            if (!$flag)
                $this->_sendResponse(200, CJSON::encode(['status' => false, 'smsSend' => false, 'message' => 'پیامک کد فعالسازی ارسال نشده است.']), 'application/json');
            $model = Users::model()->findByAttributes(array('mobile' => $_POST['sim']));
            if ($model) {
                $model->password = null;
                if (!$model->app_token || empty($model->app_token)) {
                    $model->scenario = 'app_update';
                    if ($model->createAppToken()->save())
                        $this->_sendResponse(200, CJSON::encode(['status' => true,
                            'isUser' => 1, 'newUser' => 0, 'userToken' => $model->app_token,
                            'user' => $model]), 'application/json');
                } else
                    $this->_sendResponse(200, CJSON::encode(['status' => true,
                        'isUser' => 1, 'newUser' => 0, 'userToken' => $model->app_token,
                        'user' => $model]), 'application/json');
            } else {
                $signUpStatus = SiteOptions::model()->findByAttributes(['name' => 'signup_status']);
                if ($signUpStatus->value == 1) {
                    $model = new Users('app_insert');
                    $model->mobile = $_POST['sim'];
                    if ($model->createAppToken()->save()) {
                        if ($model->setDefaultPlan()) {
                            $this->_sendResponse(200, CJSON::encode(['status' => true,
                                'isUser' => 1, 'newUser' => 1, 'userToken' => $model->app_token,
                                'user' => $model]), 'application/json');
                        } else {
                            $model->delete();
                            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'خطا در هنگام ثبت!']), 'application/json');
                        }
                    } else
                        $this->_sendResponse(200, CJSON::encode(['status' => false, 'isUser' => 0, 'message' => 'در ثبت نام مشکلی ایجاد شده است، لطفا مجددا تلاش کنید.', 'errors' => $model->errors]), 'application/json');
                } else
                    $this->_sendResponse(200, CJSON::encode(['status' => true, 'isUser' => 0, 'signupStatus' => false, 'message' => 'متاسفانه در حال حاضر امکان عضویت جدید وجود ندارد، لطفا بعدا اقدام فرمایید.']), 'application/json');
            }
        } else
            $this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'شماره سیم کارت  یا کد فعالسازی ارسال نشده است.']), 'application/json');
    }

    public function actionTest()
    {
        var_dump(1);
        exit;
    }
}