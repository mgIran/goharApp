<?
$widget = $this->createWidget('ext.EExcelView.EExcelView', array(
    'dataProvider' => $dataProvider,
    'title' => 'بانک پارسیان',
    'autoWidth' => false,
    'grid_mode' => 'export',
    'filename' => Yii::getPathOfAlias('webroot') . '/protected/checkouts_export/'.$export->export_file.'.xlsx',
    'exportType' => 'Excel2007',
    'disablePaging' => true,
    'stream' => false,
    'columns' => array(
        array(
            'header' => 'Destination Iban Number (Variz Be Sheba)',
            'value' => '"IR".$data->user->iban'
        ),
        array(
            'header' => 'Owner Name (Name e Sahebe Seporde)',
            'value' => '$data->user->holder_name'
        ),
        array(
            'header' => 'Transfer Amount (Mablagh)',
            'value' => '(ceil($data->price * 100 / floatval(100 + $data->wage))) * 10'
        ),
        array(
            'header' => 'Description (Sharh)',
            'value' => '$data->export_id'
        ),
        array(
            'header' => 'Factor Number (Shomare Factor)',
            'value' => '$data->id'
        ),
        array(
            'header' => 'Please do not remove this first row. (Lotfan radife nokhost ra paak nafarmayid.)',
            'value' => ''
        ),
    )
));
$widget->run();