<div class="col-md-12">
    <div class="plans-labels">
        <div class="col-md-4">
            <div class="factor-sections col-md-12" style="min-height: 162px;white-space: normal">
                <? echo Checkouts::$statusMessages[$lastCheckout->status]?>
            </div>
            <div class="factor-sections col-md-12" style="background:#ffae00">
                <div class="col-md-6">
                    اعتبار نقدی فعلی من
                </div>
                <div class="col-md-6">
                    <?=number_format($this->currentUser->credit_charge)?>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="factor-title">
                <span>
                گزارش آخرین درخواست تسویه حساب
                </span>
            </div>
            <div class="factor-sections col-md-12" style="color:#fff;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <b>
                                    مبلغ درخواستی
                                </b>
                            </div>
                            <div class="col-md-6">
                                <? echo number_format(ceil($lastCheckout->price * 100 / floatval(100 + $lastCheckout->wage))); ?>
                                تومان
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <b>
                                    کارمزد
                                </b>
                            </div>
                            <div class="col-md-6">
                                <? echo $lastCheckout->wage; ?>
                                درصد
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <b>
                                    مبلغ صورتحساب
                                </b>
                            </div>
                            <div class="col-md-6">
                                <? echo number_format($lastCheckout->price); ?>
                                تومان
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="row">
                            <b>
                                تاریخ درخواست
                            </b>
                        </div>
                        <div class="row">
                            <?=Yii::app()->jdate->date('Y/m/d',$lastCheckout->req_date)?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="row">
                            <b>
                                تاریخ تراکنش
                            </b>
                        </div>
                        <div class="row">
                            <?=(!is_null($lastCheckout->pay_date))?Yii::app()->jdate->date('Y/m/d',$lastCheckout->pay_date):'-'?>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="row">
                            <b>
                                شماره پیگیری
                            </b>
                        </div>
                        <div class="row">
                            <?=(!is_null($lastCheckout->tracking_no))?$lastCheckout->tracking_no:'-'?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 checkout-steps">
                        <span class="label label-<?=($lastCheckout->status==Checkouts::STATUS_REQUESTED)?'primary':'success'?>">
                            <?=Checkouts::$statusSteps[Checkouts::STATUS_REQUESTED]?>
                        </span>
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="label label-<?=(($lastCheckout->status < Checkouts::STATUS_DOING)?'default':(($lastCheckout->status == Checkouts::STATUS_DOING)?'primary':'success'))?>">
                            <?=Checkouts::$statusSteps[Checkouts::STATUS_DOING]?>
                        </span>
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="label label-<?=(($lastCheckout->status <= Checkouts::STATUS_DOING)?'default':(($lastCheckout->status == Checkouts::STATUS_DONE)?'success':'danger'))?>">
                            وضعیت واریز :
                            <?=($lastCheckout->status <= Checkouts::STATUS_DOING)?'-':Checkouts::$statusSteps[$lastCheckout->status]?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="factor-final"></div>
    </div>
</div>
<br/>