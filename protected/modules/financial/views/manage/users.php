<? $this->widget("ext.iWebFunctions.iWebFunctions");?>
<div class="form ">
    <div class="row" style="border-bottom: 1px solid #aaa;margin-bottom: 10px">
        <h2 class="pull-right"><?=static::$actionsArray[$this->action->id]['title']?></h2>
    </div>
    <!-- Head buttons-->
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'users-form',
        'htmlOptions' => array(
            'class' => ''
        ),
    )); ?>
        <div class="row">
            <br/>
            <div class="row">
                <?$this->renderPartial('_minimum_credit',array('settings'=>$settings))?>
            </div>
            <br/><br/>
            <div class="row">
                <?$this->renderPartial('_moderation_credit_limit',array('settings'=>$settings))?>
            </div>
        </div>
        <div class="row">
            <div id="custom-box" class="col-md-6 pull-right">
                <?$this->renderPartial('_custom')?>
            </div>
        </div>

        <div class="clearfix"></div>
        <br/>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<?
$cs = Yii::app()->clientScript;
$cs->registerCss('rowReset','
.row{margin:0}
.agency-profit .row{
    float:right;
    width:33%;
}
#modules_tree{
    overflow:hidden;
    max-height:500px;
}
');

$cs->registerScript("scrollModulesTree","
    $('#modules_tree').niceScroll();
");