<div class="modal fade" id="from-webservice-postalcodes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
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
                <div class="row">
                    <div class="col-md-6">
                        سه تا پنج رقم اول کد پستی
                        <input class="form-control" name="postal_code" id="postal_code" type="text">
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