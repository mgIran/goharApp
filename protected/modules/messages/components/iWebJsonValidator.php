<?php
class iWebJsonValidator extends CValidator {
    public $type;
    protected function validateAttribute($object,$attribute){
        if(!is_array($object->$attribute))
            return;
        if($this->type == 'number')
            foreach($object->$attribute as $field)
            {
                if(!isset($field['value']))
                    continue;
                if(!is_numeric($field['value']) AND !empty($field['value']))
                {
                    $label = $object->attributeLabels();
                    $label = $label[$attribute];
                    $object->addError($attribute,"تمامی مقادیر \"$label\" باید از نوع عددی باشد.");
                    break;
                }
            }
    }

} 