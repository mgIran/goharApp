<div class="col-md-6">
    <div class="plans-labels">
        <div class="col-md-12">
            <div class="factor-title">
                <span>
                ارجاع گروهی متقاضیان به بانک
                </span>
            </div>
            <form id="filters" class="factor-sections col-md-12" style="color:#fff;">
                <div class="row">
                    <div class="col-md-6">
                        فیلتر 1) از دورترین تاریخ گذشته تا مورخ
                    </div>
                    <div class="col-md-6" style="color: gray">
                        <?
                        $this->widget('ext.JalaliDatePicker.JalaliDatePicker',array('textField'=>'start_time',
                            'options'=>array(
                                'changeMonth'=>'true',
                                'changeYear'=>'true',
                                'showButtonPanel'=>'true',
                                'changeDate' => 'true',
                            ),
                            'model' => $model
                        ));

                        $this->widget('ext.timepicker.timepicker', array(
                            'model'=>$model,
                            'name'=>'req_date',
                            'skin' => 'new',
                            'options' => array(
                                'htmlOptions' => array('placeholder'=>'Request Time...')
                            )
                        ));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 unity" style="padding-right: 15px">
                        فیلتر 2) مجموع کل مبالغ متقاضیان داخل فایل از
                    </div>
                    <div class="col-md-3" style="color: gray">
                        <? $this->widget("ext.iWebFunctions.iWebFunctions");?>
                        <? echo CHtml::activeTextField($model,'price',array('class'=>'form-control direct-ltr','onKeyUp' => '$(this).val(iWebFunctions.splitNumber($(this).val()));'))?>
                    </div>
                    <div class="col-md-3 unity">
                        تومان بیشتر نشود.
                    </div>
                </div>
                <?/*
                <div class="row">
                    <div class="col-md-6">
                        <div class="unity" style="margin-right: -10px">
                            فیلتر 3) نوع خروجی:
                        </div>
                        <div class="col-md-12">
                            <? echo CHtml::radioButtonList('Checkouts[type]','parsian',array('parsian'=>'اکسل پارسیان','parsargad'=>'تکست پاسارگاد'))?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <br/><br/>
                        <? echo CHtml::ajaxButton('اکسپورت فایل',Yii::app()->createAbsoluteUrl('financial/checkouts/export'),array(
                            'data' => 'js:$("#filters").serialize()'
                        ),array('class'=>'form-control btn btn-default submit'))?>
                    </div>
                </div>
                */?>

                <div class="row">
                    <div class="col-md-6 pull-left">
                        <? echo CHtml::ajaxButton('اکسپورت فایل',Yii::app()->createAbsoluteUrl('financial/checkouts/export'),array(
                            'data' => 'js:$("#filters").serialize()',
                            'success' => 'js:function(){
                                $.fn.yiiGridView.update("checkouts-export-grid");
                                $.fn.yiiGridView.update("checkouts-grid");
                            }',
                        ),array('class'=>'form-control btn btn-default submit'))?>
                    </div>
                </div>
            </form>
        </div>
        <div class="factor-final"></div>
    </div>
</div>