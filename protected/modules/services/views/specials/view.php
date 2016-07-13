<?
$title = $this::$actionsArray;
$title = $title['title'];

if(empty($model->details) OR is_null($model->details) OR $model->details == '')
    $model->makeDetails();
$answers = json_decode($model->details,TRUE);

?>

<h2>
    عنوان <?=$title?> :
    <?=$model->title?>
</h2>
<br/>

<table class="table table-hover pull-right">
    <thead>
        <tr>
            <th>
                <div class="pull-right">
                عنوان گزینه
                </div>
            </th>
            <th>
                <div class="pull-right">
                تعداد شرکت کننده
                </div>
            </th>
            <th>
                <div class="pull-right">
                نتیجه %
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        <?foreach($answers as $answer):?>
            <tr>
                <td>
                    <?=$answer['title']?>
                </td>
                <td>
                    <?=$answer['num']?>
                </td>
                <td>
                    <?=$answer['percent']?>%
                </td>
            </tr>
        <?endforeach;?>
    </tbody>
</table>
<?if($this->_type == SpecialServices::TYPE_OVERALL):?>
    <div class="col-md-12">
        <hr style="border-color: #999"/>
    </div>
    <h4>
        لیست نوبت های داده شده
    </h4>
    <table class="table table-hover pull-right">
        <thead>
        <tr>
            <th>
                <div class="pull-right">
                    تلفن
                </div>
            </th>
            <th>
                <div class="pull-right">
                    زمان
                </div>
            </th>
        </tr>
        </thead>
        <tbody>
        <?
        $startTime = $model->specialServicesOverallTime->start_time;
        $interval = $model->specialServicesOverallTime->interval * 60;
        foreach($model->specialServicesAnswers[0]->specialServicesSendedAnswers as $answer):?>
            <tr>
                <td>
                    <?=$answer->user?>
                </td>
                <td>
                    <?=Yii::app()->jdate->date('Y/m/d H:i:s',$startTime)?>
                </td>
            </tr>
        <?
            $startTime += $interval;
        endforeach;?>
        </tbody>
    </table>

<?endif;?>