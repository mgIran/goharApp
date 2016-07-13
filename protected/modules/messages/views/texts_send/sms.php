<?
/*
$this->renderPartial('sms/_body',array(
    'model' => $model,
    'mobilesBankCategories' => $mobilesBankCategories,
    'contactsCategories' => $contactsCategories,
    'webserviceCategories' => $webserviceCategories,
    'numbers' => $numbers
));*/

switch($step)
{
    case 'policy':
        $this->renderPartial('sms/_help_policy',array(
            'model' => $model ,
            'helpPolicy' => $data['helpPolicy'],
            'sendingSystem' => $data['sendingSystem'],
            'sendUsageA' => $data['sendUsageA'],
            'sendUsageB' => $data['sendUsageB'],
            'nextStepUrl'=>$data['nextStepUrl'],
        ));
        break;
    case 'info':
        $this->renderPartial('sms/_sms_data',array(
            'model' => $model ,
            'helpPolicy' => $data['helpPolicy'],
            'senders' => $data['senders'],
            'sendtype' => $sendtype,
            'nextStepUrl'=>$data['nextStepUrl'],
        ));
        break;
    case 'recipients':
        $this->renderPartial('sms/_recipients',array(
            'model' => $model ,
            'helpPolicy' => $data['helpPolicy'],
            'mobileBank'=>$data['mobileBank'],
            'smsID'=>$data['smsID'],
            'contactGroups'=>$data['contactGroups'],
            'contacts'=>$data['contacts'],
        ));
        break;
}