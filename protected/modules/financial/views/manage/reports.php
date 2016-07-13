<div class="row">
    <div class="col-md-3">
        دریافتی امروز :
        <?=number_format(Buys::getPrice())?>
        تومان
    </div>

    <div class="col-md-3">
        دریافتی دیروز :
        <?=number_format(Buys::getPrice('SUBDATE(CURRENT_DATE, 1)'))?>
        تومان
    </div>

    <div class="col-md-3">
        دریافتی 3 روز قبل :
        <?=number_format(Buys::getPrice('SUBDATE(CURRENT_DATE, 3)'))?>
        تومان
    </div>

    <div class="col-md-3">
        دریافتی 4 روز قبل :
        <?=number_format(Buys::getPrice('SUBDATE(CURRENT_DATE, 4)'))?>
        تومان
    </div>
</div>
<br/><br/><br/>
<div class="row">
    <div class="col-md-4">
        <div>
میانگین دریافتی 7 روز اخیر :
            <?=number_format($daysValues['avg'][0])?>
            تومان
        </div>
        <br/><div>
            میانگین دریافتی 15 روز اخیر :
            <?=number_format($daysValues['avg'][1])?>
            تومان
        </div>
        <br/><div>
            میانگین دریافتی 30 روز اخیر :
            <?=number_format($daysValues['avg'][2])?>
            تومان
        </div>
        <br/><div>
            میانگین دریافتی 45 روز اخیر :
            <?=number_format($daysValues['avg'][3])?>
            تومان
        </div>
        <br/><div>
            میانگین دریافتی 60 روز اخیر :
            <?=number_format($daysValues['avg'][4])?>
            تومان
        </div>
    </div>

    <div class="col-md-4" id="days_graph_container">
        <?
        Yii::app()->clientScript->registerScript("days_graph","
            $.ajax({
                url: '".Yii::app()->createAbsoluteUrl('financial/manage/days')."',
                data: {values:".CJavaScript::encode($daysValues).",width:$('#days_graph_container').width()},
                type: 'POST',
                success:function(){
                    $('#days_graph_container').html('<img src=\"".Yii::app()->createAbsoluteUrl('test.png')."\">');
                }
            });
        ");
        ?>
    </div>

    <div class="col-md-4">
        <div>
            مجموع مبالغ دریافتی 7 روز اخیر :
            <?=number_format($daysValues['sum'][0])?>
            تومان
        </div>
        <br/><div>
            مجموع مبالغ دریافتی 15 روز اخیر :
            <?=number_format($daysValues['sum'][1])?>
            تومان
        </div>
        <br/><div>
            مجموع مبالغ دریافتی 30 روز اخیر :
            <?=number_format($daysValues['sum'][2])?>
            تومان
        </div>
        <br/><div>
            مجموع مبالغ دریافتی 45 روز اخیر :
            <?=number_format($daysValues['sum'][3])?>
            تومان
        </div>
        <br/><div>
            مجموع مبالغ دریافتی 60 روز اخیر :
            <?=number_format($daysValues['sum'][4])?>
            تومان
        </div>
    </div>
</div>

<br/><br/><br/>
<div class="row">
    <div class="col-md-4">
        <div>
            میانگین دریافتی 3 ماه اخیر :
            <?=number_format($monthsValues['avg'][0])?>
            تومان
        </div>
        <br/><div>
            میانگین دریافتی 6 ماه اخیر :
            <?=number_format($monthsValues['avg'][1])?>
            تومان
        </div>
        <br/><div>
            میانگین دریافتی 9 ماه اخیر :
            <?=number_format($monthsValues['avg'][2])?>
            تومان
        </div>
        <br/><div>
            میانگین دریافتی 12 ماه اخیر :
            <?=number_format($monthsValues['avg'][3])?>
            تومان
        </div>
    </div>

    <div class="col-md-4" id="months_graph_container">
        <?
        Yii::app()->clientScript->registerScript("months_graph","
            $.ajax({
                url: '".Yii::app()->createAbsoluteUrl('financial/manage/months')."',
                data: {values:".CJavaScript::encode($monthsValues).",width:$('#months_graph_container').width()},
                type: 'POST',
                success:function(){
                    $('#months_graph_container').html('<img src=\"".Yii::app()->createAbsoluteUrl('test2.png')."\">');
                }
            });
        ");
        ?>
    </div>

    <div class="col-md-4">
        <div>
            مجموع مبالغ دریافتی 3 ماه اخیر :
            <?=number_format($monthsValues['sum'][0])?>
            تومان
        </div>
        <br/><div>
            مجموع مبالغ دریافتی 6 ماه اخیر :
            <?=number_format($monthsValues['sum'][1])?>
            تومان
        </div>
        <br/><div>
            مجموع مبالغ دریافتی 9 ماه اخیر :
            <?=number_format($monthsValues['sum'][2])?>
            تومان
        </div>
        <br/><div>
            مجموع مبالغ دریافتی 12 ماه اخیر :
            <?=number_format($monthsValues['sum'][3])?>
            تومان
        </div>
    </div>
</div>