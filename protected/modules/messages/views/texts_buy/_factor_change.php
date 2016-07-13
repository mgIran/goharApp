<?if($model->isNewRecord OR is_null($model->details)):?>
    <div class="plans-labels">
        <div class="col-md-12 pull-right">
            <div class="factor-sections col-md-12" style="padding-top: 15px;padding-bottom: 14px;overflow: visible">
                <div class="row">
                    <div class="title col-md-2" style="padding:5px 15px;text-align: center">تعداد صفحات</div>
                    <div class="value select-plan col-md-6 pull-right">
                        <div class="col-md-6 pull-right">
                            <? $this->widget("ext.iWebFunctions.iWebFunctions");?>
                            <?php echo CHtml::textField("pages",number_format($model->qty),array(
                                    'ajax'=> array(
                                        'beforeSend'=>'js:function(){
                                                    $("#factor-loading,#factor-details").addClass("active");
                                                }',
                                        'data' => 'js:{num:parseInt($(this).val().replace(new RegExp("(,)", "ig"),""))}',
                                        'url' => Yii::app()->createAbsoluteUrl('messages/texts_buy'),
                                        'replace' => '#factor-refresh',
                                        'complete' => 'js:function(){
                                                    $("#factor-loading").removeClass("active");
                                                    setTimeout(function(){
                                                        $("#factor-details").removeClass("active");
                                                    },300);
                                                }'
                                    ),
                                    'id' => 'pages-num',
                                    'class' => 'form-control direct-ltr',
                                    'onKeyUp' => '$(this).val(iWebFunctions.splitNumber($(this).val()));'
                                )
                            );?>
                        </div>
                        <div class="col-md-6 pull-right">
                            <?php echo CHtml::button('بروز رسانی فاکتور',array(
                                'class' => 'btn btn-primary',
                                'onClick' => '$("#pages-num").trigger("change");',
                            ));
                            ?>
                        </div>
                    </div>
                    <div id="factor-loading" class="title col-md-2" style="padding:5px 15px">در حال بروز رسانی</div>
                </div>
            </div>
        </div>
        <div class="factor-final"></div>
    </div>

    <div class="clearfix"></div>
    <br/>
    <div class="clearfix"></div>
<?endif;?>