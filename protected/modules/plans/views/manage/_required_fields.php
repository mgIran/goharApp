<?
$model = new Users;
if(!is_null($values))
    $model->attributes = $values;

CHtml::$afterRequiredLabel = '';
CHtml::$beforeRequiredLabel = '';
?>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'first_name',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'first_name',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'last_name',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'last_name',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'mobile',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'mobile',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'email',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'email',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'father_name',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'father_name',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'national_id',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'national_id',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-12">     <hr/> </div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'birth_town',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'birth_town',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'birth_city_id',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'birth_city_id',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-12">     <hr/> </div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'home_town',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'home_town',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'home_city_id',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'home_city_id',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'home_postal_code',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'home_postal_code',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'home_address',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'home_address',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'home_phone_number',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'home_phone_number',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-12">     <hr/> </div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'work_postal_code',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'work_postal_code',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'work_address',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'work_address',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'work_phone_number',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'work_phone_number',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-12">     <hr/> </div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'personal_image',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'personal_image',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'national_card_front',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'national_card_front',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'national_card_rear',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'national_card_rear',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'birth_certificate_first',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'birth_certificate_first',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'business_license',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'business_license',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'activity_permission',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'activity_permission',array('class'=>'pull-right css-label')); ?>
</div>

<div class="col-md-4 pull-right">
    <?php echo CHtml::activeCheckBox($model,'other_legal_documents',array('class'=>'pull-right css-checkbox')); ?>
    <?php echo CHtml::activeLabelEx($model,'other_legal_documents',array('class'=>'pull-right css-label')); ?>
</div>