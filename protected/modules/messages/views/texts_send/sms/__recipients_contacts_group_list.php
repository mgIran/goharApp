<div class="container-fluid">
    <h4>گروه های دفترچه مخاطبین</h4>
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'contacts-list',
        'dataProvider'=>$contactGroups,
        'columns'=>array(
            array(
                'name'=>'id',
                'header'=>'',
                'value'=>'CHtml::checkBox("select-group", false, array(
                    "class"=>"select-group contacts '.$dest.'",
                    "value"=>$data["id"]
                ))',
                'type'=>'raw'
            ),
            array(
                'name'=>'title',
                'header'=>'نام گروه',
            ),
            array(
                'name'=>'contacts_count',
                'header'=>'تعداد اعضاء',
            ),
            array(
                'name'=>'exception',
                'header'=>'استثناء',
                'type'=>'raw',
                'value'=>'CHtml::link("", "#", array("class"=>"list exception '.$dest.'", "data-title"=>$data["title"], "data-gid"=>$data["id"], "data-target"=>"#exception-recipient", "data-toggle"=>"modal", "data-backdrop"=>"static"))',
                'htmlOptions'=>array(
                    'class'=>'button-column',
                ),
            ),
        ),
        'template' => '{items}{pager}'
    ));?>
    <?php Yii::app()->clientScript->registerScript('selectContactsGroup', "
        $('.select-group.contacts').on('change', function(){
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
                data:{recipients_group_info:{gid:$(this).val(),action:action,sid:$('#sid').val(),from:'contacts',dest:dest}},
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
    <?php Yii::app()->clientScript->registerScript('exception', "
        var exceptionScenario='';
        $('.button-column .list.exception').click(function(){
            $('.modal-body h5').text('مخاطبین گروه '+$(this).data('title'));
            var that=$(this),dest='';

            if($(this).hasClass('wl'))
                dest='wl';
            else
                dest='bl';

            $('.modal-body #dest').val(dest);

            $.fn.yiiGridView.update('group-contacts',{
                data:{gid:$(this).data('gid'),sid:$('#sid').val()},
                complete: function(jqXHR, status) {
                    if(status=='success')
                    {
                        $('#gid').val(that.data('gid'));
                        $.ajax({
                            url:'".Yii::app()->baseUrl."/messages/texts_send/getRecipientsException',
                            type:'POST',
                            dataType:'JSON',
                            data:{recipients_info:{gid:that.data('gid'),sid:$('#sid').val(),dest:dest}},
                            success:function(data){
                                if(data.status=='empty' || data.exception==null)
                                {
                                    if(that.parents('tr').find('.select-group.contacts').is(':checked'))
                                        $('#group-contacts').find('.select-contact').prop('checked',true);
                                    else
                                        $('#group-contacts').find('.select-contact').prop('checked',false);
                                }
                                else
                                {
                                    if(data.exception.ignore!==undefined)
                                    {
                                        $('#group-contacts').find('.select-contact').each(function(){
                                            if($.inArray($(this).val(),data.exception.ignore)==-1)
                                                $(this).prop('checked',true);
                                        });
                                    }
                                    else if(data.exception.accept!==undefined)
                                    {
                                        $('#group-contacts').find('.select-contact').each(function(){
                                            if($.inArray($(this).val(),data.exception.accept)!=-1)
                                                $(this).prop('checked',true);
                                        });
                                    }
                                }
                            }
                        });

                        if(that.parents('tr').find('.select-group.contacts').is(':checked'))
                            exceptionScenario='ignore';
                        else
                            exceptionScenario='accept';
                    }
                }
            });
        });
    ");?>
</div>