<!--<div class="col-md-4 pull-right">-->
<!--    <div class="panel panel-primary">-->
<!--        <div class="panel-heading">پیام های عمومی</div>-->
<!--        <table class="table">-->
<!--            <tr>-->
<!--                <td>-->
<!--                    <span class="pull-right panel-titles" >20% تخفیف زمستانه</span>-->
<!--                    <span class="badge pull-left">2 عدد</span>-->
<!--                </td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>-->
<!--                    <span class="pull-right panel-titles" >افزایش اعتبار نمایندگان</span>-->
<!--                </td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </div>-->
<!--</div>-->
<!--<div class="col-md-4 pull-right">-->
<!--    <div class="panel panel-success">-->
<!--        <div class="panel-heading">پیام های عمومی</div>-->
<!--        <table class="table">-->
<!--            <tr>-->
<!--                <td>-->
<!--                    <span class="pull-right panel-titles" >20% تخفیف زمستانه</span>-->
<!--                    <span class="badge pull-left">2 عدد</span>-->
<!--                </td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>-->
<!--                    <span class="pull-right panel-titles" >افزایش اعتبار نمایندگان</span>-->
<!--                </td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </div>-->
<!--</div>-->
<div class="col-md-4 pull-left">
    <div class="panel panel-info">
        <div class="panel-heading">آخرین تلاش های ورود</div>
        <table class="table">
            <?foreach($lastLogin as $login):?>
                <tr>
                    <td>
                        <span class="pull-right panel-titles"><?=Yii::app()->jdate->date('H:i:s - Y/m/d',$login->time)?>&nbsp;(<?=$login->ip?>)</span>
                        <span class="label label-<?=UsersLogins::$statusList[$login->status]['label']?>"><?=UsersLogins::$statusList[$login->status]['value']?></span>
                    </td>
                </tr>
            <?endforeach;?>
        </table>
    </div>
</div>