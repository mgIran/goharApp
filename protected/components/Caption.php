<?php

class Caption extends CWidget
{
    public $icon='';
    public $title='';
    public function init(){}
 
    public function run()
    {        
        echo 
            "<div class='item-icon ".$this->icon."'></div>
            <h5 class='panel-title inline'>".$this->title."</h5>";
    }
}
?>
