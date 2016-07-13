<div class="modal fade" id="from-webservice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-left" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title"><?= $title ?></h4>
            </div>
            <div class="modal-body">
                <div class="form" style="direction: rtl">
                    <div class="row">
                        <div class="col-md-12">
                            <?
                            $js =
                                'function(e,data){
                                    e.preventDefault();
                                    data.node.id
                                    if(selectNode_webserviceBanksTree === false){
                                        selectNode_webserviceBanksTree = true;
                                        $("#webserviceBanksTree").jstree(true).deselect_all();
                                        $("#webserviceBanksTree").jstree(true).select_node(data.node);
                                        selectNode_webserviceBanksTree = false;
                                        webserviceBanksValues.node = data.node.id;
                                        if(!isInArray(parseInt(data.node.id), [1,2,5,9,2500000,6,7,8]))
                                            dropDownCaption = $(data.node.text).text();
                                        $("#MessagesTextsSend_webserviceBanks").val(JSON.stringify(webserviceBanksValues));
                                    }
                                    return false;
                                }';
                            $this->widget('application.modules.messages.extensions.RolesJsTree.RolesJsTree', array(
                                'name' => 'MessagesTextsSend[webservice]',
                                'classes' => $webserviceCategories,
                                'id' => 'webserviceBanksTree',
                                'inputId' => 'MessagesTextsSend_webserviceBanks',
                                'itemsPrefixId' => 'webserviceBanks_text',
                                'ajax' =>
                                    "'data' : {
                                                'dataType' : 'JSON',
                                                'url' : function (node) {
                                                    return node.id === '#' ?
                                                        '" . Yii::app()->createAbsoluteUrl("messages/texts_send/getWebserviceBanks") . "' :
                                                '" . Yii::app()->createAbsoluteUrl("messages/texts_send/getWebserviceBanks?id=") . "' + node.id;
                                            }
                                        }
                                    ",
                                'onSelectNode' => $js,
                            )); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            ردیف
                            <input class="form-control seed" type="text">
                        </div>
                        <div class="col-md-6">
                            تعداد
                            <input class="form-control qty" type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            پیش شماره
                            <input class="form-control pre-number" type="text">
                        </div>
                        <div class="col-md-3">
                            سن از
                            <input class="form-control start-age-value" type="text">
                        </div>
                        <div class="col-md-3">
                            تا
                            <input class="form-control end-age-value" type="text">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 radio-list">
                            جنسیت
                            <br>
                            <input type="radio" name="gender" id="g_male_1" value="1">
                            <label for="g_male_1">مرد</label>
                            <input type="radio" name="gender" id="g_female_1" value="0">
                            <label for="g_female_1">زن</label>
                            <input type="radio" name="gender" id="g_both_1" value="2" checked="checked">
                            <label for="g_both_1">هر دو</label>
                        </div>
                        <div class="col-md-6 radio-list">
                            نوع شماره
                            <br>
                            <input type="radio" name="type" id="t_continual_1" value="1"><label
                                for="t_continual_1">دائمی</label>
                            <input type="radio" name="type" id="t_credit_1" value="0"><label
                                for="t_credit_1">اعتباری</label>
                            <input type="radio" name="type" id="t_both_1" value="2" checked="checked">
                            <label for="t_both_1">هر دو</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: left;padding-left: 5%">
                <button id="addPackage" type="button" class="btn btn-primary addPackage">اضافه کردن</button>
                <div class="pull-right">
                    <a data-trigget="manual" class="btn btn-info getCount-button" data-placement="left"
                       title="تعداد شماره" data-content="">بررسی تعداد</a>
                </div>
            </div>
        </div>
    </div>
</div>

<? Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/themes/gohar_panel/js/messages.js', CClientScript::POS_END); ?>