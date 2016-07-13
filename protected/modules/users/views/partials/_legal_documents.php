<div class="row" style="margin-bottom: 12px">
    <div class="col-md-4 pull-right">
        <span class="pull-left" style="margin-left: 5px">
        پسوند های مجاز
        </span>
    </div>
    <div class="col-md-4 pull-right">
        <span class="iwiu-drop-zone" style="border: none;padding: 0;margin: 0;display: inline">
            <span class="iwiu-formats" style="display: inline;">
                <span>JPG</span>
                <span>JPEG</span>
                <span>PNG</span>
                <span>GIF</span>
            </span>
        <span>
    </div>
    <div class="col-md-3 pull-right" style="margin-right: -6px">
        <span class="pull-left">
        حداکثر حجم فایل
        </span>
    </div>
    <div class="col-md-1 pull-right">
        <span class="iwiu-drop-zone" style="border: none;padding: 0;margin: 0;display: inline;">
            <span class="iwiu-formats" style="display: inline;">
                <span>300KB</span>
            </span>
        <span>
    </div>
</div>
<?$legalDocuments = array('personal_image','national_card_front','national_card_rear','birth_certificate_first','business_license','activity_permission');

foreach($legalDocuments as $type)
    $this->renderPartial('users.views.partials._each_legal_documents',array(
        'type' => $type,
        'form' => $form,
        'model'=> $model,
    ));
?>
<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'other_legal_documents'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <a class="btn btn-primary col-md-12 align-right" href="#" data-backdrop="static" data-toggle="modal" data-target="#other-legal-documents">
            جهت آپلود کلیک کنید
        </a>

    </div>
</div>
<? $this->renderPartial('users.views.partials._other_legal_documents',array(
    'model'=>$model
));