<div class="modal fade" id="exception-recipient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-left" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">استثناء</h4>
            </div>
            <div class="modal-body">
                <h5></h5>
                <?php echo CHtml::hiddenField('gid','');?>
                <?php echo CHtml::hiddenField('dest','');?>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'group-contacts',
                    'dataProvider'=>$contacts,
                    'columns'=>array(
                        array(
                            'name'=>'id',
                            'header'=>'',
                            'value'=>'CHtml::checkBox($data["mobile"], false, array(
                                "class"=>"select-contact",
                                "value"=>$data["id"],
                            ))',
                            'type'=>'raw'
                        ),
                        array(
                            'name'=>'name',
                            'header'=>'نام و نام خانوادگی',
                            'value'=>'$data->first_name." ".$data->last_name',
                            'sortable'=>false
                        ),
                        array(
                            'name'=>'mobile',
                            'header'=>'شماره موبایل',
                            'sortable'=>false
                        ),
                        array(
                            'name'=>'email',
                            'sortable'=>false
                        ),
                    ),
                    'template' => '{items}{pager}'
                ));?>
                <input type="button" value="ثبت" class="btn btn-info" id="submit-contacts">
                <?php
                Yii::app()->clientScript->registerScript('selectContact', "
                    $('body').on('change', '.select-contact', function(){
                        var action='',that=$(this);
                        if($(this).is(':checked'))
                            action='checked';
                        else
                            action='unchecked';
                        $.ajax({
                            url:'".Yii::app()->baseUrl."/messages/texts_send/manageException',
                            type:'POST',
                            dataType:'JSON',
                            data:{contact_info:{cid:$(this).val(),action:action,scenario:exceptionScenario,sid:$('#sid').val(),gid:$('#gid').val(),dest:$('#dest').val()}},
                            success:function(data){
                                if(data.status=='fail')
                                {
                                    alert('در انجام عملیات خطایی رخ داده است لطفا دوباره امتحان کنید!');
                                    if(action=='add')
                                        that.prop('checked', false);
                                    else
                                        that.prop('checked', true);
                                }
                                else
                                    $('#recipients-count').text(data.recipients_count);
                            }
                        });
                    });
                ");
                Yii::app()->clientScript->registerScript('modalBtnClick', "
                    $('#submit-contacts').click(function(){
                        $('.modal-header .close').trigger('click');
                    });
                ");?>
            </div>
        </div>
    </div>
</div>