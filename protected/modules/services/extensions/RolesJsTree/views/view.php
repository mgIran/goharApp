<div id='<?=$this->id?>'>
    <ul>
        <?
        foreach($this->classes as $action){
            echo $this->getListItem($action);
        }
        ?>
    </ul>
</div>
<input type="hidden" name="<?=$this->name?>" id="<?=$this->inputId?>"/>
