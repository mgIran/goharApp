<div id='modules_tree'>
    <ul>
        <?
        foreach($this->classes as $module=>$action)
        {
            echo $this->getListItem($module,$action);
        }
        ?>
    </ul>
</div>
<input type="hidden" name="<?=$this->name?>" id="js-tree-permissions"/>
