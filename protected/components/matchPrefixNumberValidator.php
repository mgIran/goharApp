<?php
class matchPrefixNumberValidator extends CValidator {
    public $message = '{$attribute} صحیح نمی باشد.';
    public $values = '';
    public $equal = FALSE;
    protected function validateAttribute($object,$attribute){
        if(empty($object->$attribute) OR is_null($object->$attribute)){
            return TRUE;
        }
        $labels = $object->attributeLabels();
        $this->message = str_replace('{$attribute}',$labels[$attribute],$this->message);
        if(isset($this->values) AND
            !is_null($this->values)){
            $allPrefixes = explode(',',$this->values);
            $foundFlag = FALSE;
            foreach($allPrefixes as $prefix){
                if(
                    (!$this->equal AND preg_match('/^'.$prefix.'\d*$/',$object->$attribute))
                    OR
                    ($this->equal AND $object->$attribute == $prefix)){
                    $foundFlag = TRUE;
                }
            }
            if(!$foundFlag){
                $this->addError($object,$attribute,$this->message);
                return FALSE;
            }
        }
        return TRUE;
    }

} 