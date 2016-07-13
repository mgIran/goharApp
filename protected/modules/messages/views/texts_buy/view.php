<div id="factor-refresh">
<?
    $this->menu=array(
        array('label'=>'لیست', 'url'=>array('admin')),
    );

    $factorFields = json_decode($buyModel->details,true);
    $model = (object)array();
    $numberOfPages = $buyModel->qty;

    ?>
    <div class="col-md-12">
        <div class="clearfix"></div>
        <br/>
        <div class="clearfix"></div>
        <div class="plans-labels<?=((isset($_GET['num']))?' active':'')?>" id="factor-details">
            <div class="col-md-6 pull-right">
                <?$this->renderPartial('_factor',array(
                        'model' => $model,
                        'factorFields' => $factorFields
                    )
                )?>
            </div>
            <div class="col-md-6 pull-right">
                <?$this->renderPartial('//report/_desc')?>
                <div class="factor-sections factor-box col-md-12">
                    <?$this->renderPartial('_form', array(
                            'buyModel' => $buyModel,
                            'factorFields' => $factorFields,
                            'numberOfPages' => $numberOfPages
                        )
                    )?>
                </div>
            </div>
        </div>
    </div>
</div>