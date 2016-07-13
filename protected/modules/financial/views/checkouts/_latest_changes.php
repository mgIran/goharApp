<div class="col-md-12">
    <div class="plans-labels">
        <div class="col-md-12">
            <? $desc = Pages::getPageByName('last_changes_checkouts'); ?>
            <div class="factor-title">
                <span>
                <?=$desc->title?>
                </span>
            </div>
            <div class="factor-sections col-md-12" style="color:#fff;">
                <? $lastChange = CreditsTransactions::latestChange();?>
                <div class="row">
                    <div class="col-md-1">
                        <b>
                            تاریخ
                        </b>
                    </div>
                    <div class="col-md-1">
                        <?=Yii::app()->jdate->date("Y/m/d",$lastChange)?>
                    </div>
                    <div class="col-md-1">
                        <b>
ساعت
                        </b>
                    </div>
                    <div class="col-md-1">
                        <?=Yii::app()->jdate->date("H:m",$lastChange)?>
                    </div>

                    <div class="col-md-3">
                        <b>
                        زمان باقیمانده تا صفر شدن موجودی شما
                        </b>
                    </div>
                    <?
                    $planInformation = json_decode(Yii::app()->user->plan);
                    //$days = $planInformation->date + (intval($planInformation->expire_time) * 86400);
                    $days = $planInformation->expire_date;
                    $dateDiff =  $days - time();

                    if(!in_array(intval($planInformation->id),Plans::$deActivePlans)) {
                        $freePlanDays = Plans::model()->findByPk(Plans::FREE_PLAN);
                        $freePlanDays = $freePlanDays->expire_time;
                        $dateDiff += $freePlanDays * 86400;
                    }

                    $month = intval($dateDiff / 2592000);
                    $dateDiff -= $month * 2592000;
                    $week = intval($dateDiff / 604800);
                    $dateDiff -= $week * 604800;
                    $day = intval($dateDiff / 86400);
                    $dateDiff -= $day * 86400;
                    $hour = intval($dateDiff / 3600);
                    ?>
                    <div class="col-md-5">
                        <?=$month?>
                        ماه و
                        <?=$week?>
                        هفته و
                        <?=$day?>
                        روز و
                        <?=$hour?>
                        ساعت
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <?=$desc->text?>
                    </div>
                </div>
            </div>
        </div>
        <div class="factor-final"></div>
    </div>
</div>
<br/>
<div class="clearfix"></div>
<br/>