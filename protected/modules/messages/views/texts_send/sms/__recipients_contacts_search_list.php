<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <?php echo CHtml::radioButtonList('searchRadioWL','inContacts',array('inContacts'=>'جستجو در کل دفترچه مخاطبین','thisContacts'=>'جستجو در مخاطبین این پیام'));?>
    </div>
    <div class="col-md-2"></div>
</div>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <?php echo CHtml::label('قسمتی از شماره تلفن ، نام یا ایمیل','search_value_wl');?>
        <?php echo CHtml::textField('search_value_wl','',array('class'=>'form-control'));?>
    </div>
    <div class="col-md-2"></div>
</div>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <?php echo CHtml::button('جستجو',array('class'=>'btn btn-info','id'=>'searchWL'));?>
        <?php Yii::app()->clientScript->registerScript('searchWl',"
            $('#searchWL').click(function(){
                if($('#search_value_wl').val()!='')
                {
                    if($('input[name=\"searchRadioWL\"]:checked').val()=='inContacts')
                    {
                        $.fn.yiiGridView.update('contacts-search-list',{
                            data:{
                                search:{
                                    value:$('#search_value_wl').val(),
                                    in:$('input[type=\"radio\"][name=\"searchRadioWL\"]:checked').val(),
                                    sid:$('#sid').val()
                                }
                            },
                            complete: function(jqXHR, status) {
                                if(status=='success')
                                    $('.search-list').removeClass('hidden');
                            }
                        });
                    }
                    else
                    {
                        $.fn.yiiGridView.update('contacts-search-list',{
                            data:{
                                search:{
                                    value:$('#search_value_wl').val(),
                                    in:$('input[type=\"radio\"][name=\"searchRadioWL\"]:checked').val(),
                                    sid:$('#sid').val()
                                }
                            },
                            complete: function(jqXHR, status) {
                                if(status=='success')
                                    $('.search-list').removeClass('hidden');
                            }
                        });
                    }
                }
                else
                    alert('لطفا عبارتی برای جستجو وارد کنید.');
            });
        ");?>
    </div>
    <div class="col-md-2"></div>
</div>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="search-list hidden">
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'contacts-search-list',
                'dataProvider'=>(is_null($dataProvider))?Contacts::model()->search():$dataProvider,
                'ajaxUrl'=>$this->createUrl('/messages/texts_send/searchWhiteList'),
                'columns'=>array(
                    array(
                        'name'=>'id',
                        'header'=>'',
                        'value'=>'CHtml::checkBox($data["mobile"], Yii::app()->controller->selectSearchListCheckbox($data["id"],$data["cat_id"]), array(
                            "class"=>"select-searched-contact",
                            "value"=>$data["id"],
                            "data-gid"=>$data["cat_id"],
                            "data-scenario"=>Yii::app()->controller->getContactScenario($data["cat_id"])
                        ))',
                        'type'=>'raw',
                        'sortable'=>false
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
                    array(
                        'name'=>'cat_id',
                        'value'=>'$data->category->title',
                        'sortable'=>false
                    ),
                ),
                'template' => '{items}{pager}'
            ));?>
            <?php Yii::app()->clientScript->registerScript('selectSearchedContact', "
                $('body').on('change', '.select-searched-contact', function(){
                    var action='',that=$(this);
                    if($(this).is(':checked'))
                        action='checked';
                    else
                        action='unchecked';
                    $.ajax({
                        url:'".Yii::app()->baseUrl."/messages/texts_send/manageException',
                        type:'POST',
                        dataType:'JSON',
                        data:{contact_info:{cid:$(this).val(),action:action,scenario:$(this).data('scenario'),sid:$('#sid').val(),gid:$(this).data('gid')}},
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
            ");?>
            <?php echo CHtml::button('Select All',array('id'=>'select','class'=>'btn btn-info'));?>
            <?php echo CHtml::button('Deselect All',array('id'=>'deselect','class'=>'btn btn-danger'));?>
            <?php Yii::app()->clientScript->registerScript('deselect',"
                $('#deselect').click(function(){
                    var ids=Array();
                    $('#contacts-search-list').find('.select-searched-contact').each(function(){
                        if($(this).is(':checked'))
                            ids[ids.length]=[$(this).val(),$(this).data('scenario'),$(this).data('gid')];
                    });
                    if(ids.length!=0)
                    {
                        $.ajax({
                            url:'".$this->createUrl('/messages/texts_send/deselectWLSearchResult')."',
                            type:'POST',
                            dataType:'JSON',
                            data:{contacts_info:{ids:JSON.stringify(ids),sid:$('#sid').val()}},
                            success:function(data){
                                if(data.status=='success')
                                    $('#contacts-search-list').find('.select-searched-contact').prop('checked', false);
                                else
                                    alert('در انجام عملیات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
                            }
                        });
                    }
                });
            ");?>
            <?php Yii::app()->clientScript->registerScript('select',"
                $('#select').click(function(){
                    var ids=Array();
                    $('#contacts-search-list').find('.select-searched-contact').each(function(){
                        if(!$(this).is(':checked'))
                            ids[ids.length]=[$(this).val(),$(this).data('scenario'),$(this).data('gid')];
                    });
                    if(ids.length!=0)
                    {
                        $.ajax({
                            url:'".$this->createUrl('/messages/texts_send/selectWLSearchResult')."',
                            type:'POST',
                            dataType:'JSON',
                            data:{contacts_info:{ids:JSON.stringify(ids),sid:$('#sid').val()}},
                            success:function(data){
                                if(data.status=='success')
                                    $('#contacts-search-list').find('.select-searched-contact').prop('checked', true);
                                else
                                    alert('در انجام عملیات خطایی رخ داده است! لطفا مجددا تلاش کنید.');
                            }
                        });
                    }
                });
            ");?>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>