<div class="modal fade" id="from-webservice-cities" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
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
                            براساس تقسیمات کشوری
                            <?
                            $this->widget('application.extensions.iWebDropDown.iWebDropDown', array(
                                'icon' => '<div class="glyphicon glyphicon-chevron-down"></div>',
                                'name' => 'by_zones',
                                'id' => 'by_zones_id',
                                'label' => 'انتخاب کنید',
                                'list' => $this->getWebserviceBanksByCities(),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            استان
                            <? $this->widget('application.extensions.iWebDropDown.iWebDropDown', array(
                                'icon' => '<div class="glyphicon glyphicon-chevron-down"></div>',
                                'name' => 'towns',
                                'id' => 'towns',
                                'label' => 'انتخاب کنید',
                            ));
                            ?>
                        </div>
                        <div class="col-md-6">
                            شهر
                            <? $this->widget('application.extensions.iWebDropDown.iWebDropDown', array(
                                'icon' => '<div class="glyphicon glyphicon-chevron-down"></div>',
                                'name' => 'cities',
                                'id' => 'cities',
                                'label' => 'انتخاب کنید',
                            ));
                            ?>
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
                            <input class="form-control start-age-value" type="text"
                                   disabled>
                        </div>
                        <div class="col-md-3">
                            تا
                            <input class="form-control end-age-value" type="text" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 radio-list">
                            جنسیت
                            <br>
                            <input type="radio" name="gender" id="g_male_2" value="1" disabled><label
                                for="g_male_2">مرد</label>
                            <input type="radio" name="gender" id="g_female_2" value="0" disabled><label
                                for="g_female_2">زن</label>
                            <input type="radio" name="gender" id="g_both_2" value="2" checked="checked" disabled>
                            <label for="g_both_2">هر دو</label>
                        </div>
                        <div class="col-md-6 radio-list">
                            نوع شماره
                            <br>
                            <input type="radio" name="type" id="t_continual_2" value="1" disabled>
                            <label for="t_continual_2">دائمی</label>
                            <input type="radio" name="type" id="t_credit_2" value="0" disabled>
                            <label for="t_credit_2">اعتباری</label>
                            <input type="radio" name="type" id="t_both_2" value="2" checked="checked" disabled>
                            <label for="t_both_2">هر دو</label>
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