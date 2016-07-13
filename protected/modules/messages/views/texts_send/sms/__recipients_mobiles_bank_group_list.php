<div class="container-fluid">
    <h4>گروه های بانک موبایل</h4>
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'messages-mobiles-bank',
        'dataProvider'=>$mobileBank,
        'columns'=>array(
            array(
                'name'=>'id',
                'header'=>'',
                'value'=>'CHtml::checkBox("select-group", false, array(
                    "class"=>"select-group mobiles-bank '.$dest.'",
                    "value"=>$data["id"]
                ))',
                'type'=>'raw'
            ),
            array(
                'name'=>'title',
                'header'=>'نام گروه',
            ),
            array(
                'name'=>'mobiles_count',
                'header'=>'تعداد اعضاء',
            ),
        ),
        'template' => '{items}{pager}'
    ));?>
    <?php Yii::app()->clientScript->registerScript('selectMobilesBankGroup', "
        $('.select-group.mobiles-bank').on('change', function(){
            var action='',that=$(this),dest='';
            if($(this).is(':checked'))
                action='add';
            else
                action='remove';

            if($(this).hasClass('wl'))
                dest='wl';
            else
                dest='bl';

            $.ajax({
                url:'".Yii::app()->baseUrl."/messages/texts_send/manageRecipientsGroup',
                type:'POST',
                dataType:'JSON',
                data:{recipients_group_info:{gid:$(this).val(),action:action,sid:$('#sid').val(),from:'mobiles_bank',dest:dest}},
                success:function(data){
                    if(data.status=='fail')
                    {
                        alert('در انجام عملیات خطایی رخ داده است لطفا دوباره امتحان کنید!');
                        if(action=='add')
                            that.prop('checked', false);
                        else
                            that.prop('checked', true);
                    }
                }
            });
        });
    ");?>
</div>