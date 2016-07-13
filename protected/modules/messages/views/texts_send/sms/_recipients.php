<?php Yii::app()->clientScript->registerCss('general','.iwsfu-files-item-container{position:relative;float:none;}');?>
<div class="recipients">
    <?php echo CHtml::hiddenField('sid',$smsID);?>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8 panel panel-body">
            <?php if(!is_null($helpPolicy) AND !empty($helpPolicy)):?>
                <?=$helpPolicy->text;?>
            <?php endif;?>
        </div>
        <div class="col-md-2"></div>
    </div>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8"><h4>لیست سفید</h4></div>
        <div class="col-md-2"></div>
    </div>
    <div class="row">
        <div class="col-md-2 text-left">افزودن مخاطب از:</div>
        <div class="col-md-8">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#mobiles_bank">بانک موبایل</a></li>
                <li><a data-toggle="tab" href="#contacts">دفترچه مخاطبین</a></li>
                <li><a data-toggle="tab" href="#file">فایل</a></li>
                <li><a data-toggle="tab" href="#manual">دستی</a></li>
            </ul>
            <div class="tab-content" style="background: #fff;">
                <div id="mobiles_bank" class="tab-pane fade in active">
                    <?php $this->renderPartial('sms/__recipients_mobiles_bank_group_list',array(
                        'mobileBank'=>$mobileBank,
                        'dest'=>'wl',
                    ));?>
                </div>
                <div id="contacts" class="tab-pane fade">
                    <?php $this->renderPartial('sms/__recipients_contacts_group_list',array(
                        'contactGroups'=>$contactGroups,
                        'dest'=>'wl',
                    ));?>
                </div>
                <div id="file" class="tab-pane fade">
                    <?php $this->renderPartial('sms/__recipients_contacts_file_uploader',array(
                        'smsID'=>$smsID,
                    ));?>
                </div>
                <div id="manual" class="tab-pane fade">
                    <?php $this->renderPartial('sms/__recipients_contacts_manual',array(
                        'smsID'=>$smsID,
                    ));?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8"><h4>جستجو در لیست سفید</h4></div>
        <div class="col-md-2"></div>
    </div>
    <div class="search-wl">
        <?php $this->renderPartial('sms/__recipients_contacts_search_list',array('dataProvider'=>null));?>
    </div>
    <div class="row" style="margin: 50px 0;">
        <div class="col-md-2"></div>
        <div class="col-md-8"><hr style="border-color: #ccc;"></div>
        <div class="col-md-2"></div>
    </div>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8"><h4>لیست سیاه</h4></div>
        <div class="col-md-2"></div>
    </div>
    <div class="row">
        <div class="col-md-2 text-left">افزودن مخاطب از:</div>
        <div class="col-md-8">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#mobiles_bank_bl">بانک موبایل</a></li>
                <li><a data-toggle="tab" href="#contacts_bl">دفترچه مخاطبین</a></li>
                <li><a data-toggle="tab" href="#file_bl">فایل</a></li>
                <li><a data-toggle="tab" href="#manual_bl">دستی</a></li>
            </ul>
            <div class="tab-content" style="background: #fff;">
                <div id="mobiles_bank_bl" class="tab-pane fade in active">
                    <?php $this->renderPartial('sms/__recipients_mobiles_bank_group_list',array(
                        'mobileBank'=>$mobileBank,
                        'dest'=>'bl',
                    ));?>
                </div>
                <div id="contacts_bl" class="tab-pane fade">
                    <?php $this->renderPartial('sms/__recipients_contacts_group_list',array(
                        'contactGroups'=>$contactGroups,
                        'dest'=>'bl',
                    ));?>
                </div>
                <div id="file_bl" class="tab-pane fade">
                    <?php $this->renderPartial('sms/__recipients_contacts_file_uploader',array(
                        'smsID'=>$smsID,
                    ));?>
                </div>
                <div id="manual_bl" class="tab-pane fade">
                    <?php $this->renderPartial('sms/__recipients_contacts_manual',array(
                        'smsID'=>$smsID,
                    ));?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8"><h4>جستجو در لیست سیاه</h4></div>
        <div class="col-md-2"></div>
    </div>
    <div class="search-wl">
        <?php $this->renderPartial('sms/__recipients_contacts_search_list',array('dataProvider'=>null));?>
    </div>
    <?php $this->renderPartial('sms/__recipients_exception_modal',array(
        'contacts'=>$contacts,
    ));?>
</div>