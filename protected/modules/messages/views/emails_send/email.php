<?
$this->renderPartial('email/_body',array(
        'model' => $model ,
        'emailsBankCategories' => $emailsBankCategories,
        'contactsCategories' => $contactsCategories,
        'templates' => $templates
    ));