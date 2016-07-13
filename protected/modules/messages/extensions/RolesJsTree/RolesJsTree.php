<?php
class RolesJsTree extends CInputWidget
{
    protected $publishedAssetsPath;
    public
        $classes = NULL,
        $name = NULL,
        $id = NULL,
        $inputId = NULL,
        $itemsPrefixId = NULL,
        $currentPermissions = NULL,
        $itemsIdSeparator = '--',
        $isBank = FALSE,
        $ajax = FALSE,
        $onChanged = FALSE,
        $onOpenNode = FALSE,
        $onCloseNode = FALSE,
        $onCheckNode = FALSE,
        $onUnCheckNode = FALSE,
        $onSelectNode = FALSE;

	public function init()
	{
        if(is_null($this->id))
            throw new Exception("id must set");
        if(is_null($this->inputId))
            $this->inputId = $this->id."-val";
        $this->currentPermissions = json_decode($this->currentPermissions);
        $ajaxJs = "";
        if($this->ajax !== FALSE)
            $ajaxJs = ",".$this->ajax;

        $js = '
                $("#' . $this->id . '").jstree({
                    "plugins":["wholerow","checkbox"],
                    "core": {
                        "themes": {
                            "name": "proton",
                            "responsive": true
                        }' . $ajaxJs . '
                    }
                });
                $("#' . $this->id . '").jstree().close_all();';
        if($this->isBank === TRUE)
            $js .= '
                $("#' . $this->id . '").on("select_node.jstree",function(e, data){
                    e.preventDefault();
                    $("#' . $this->id . '").jstree(true).deselect_node(data.node);
                    $("#" + data.node.id).find(".jstree-anchor:first").toggleClass("js-tree-clicked");
                    var selectedElmsIds = new Array();
                    $("#' . $this->id . ' .jstree-anchor.js-tree-clicked").each(function() {
                        if (typeof $(this).closest(".jstree-node").data("id") !== typeof undefined && $(this).closest(".jstree-node").data("id") !== false)
                            var $id = $(this).closest(".jstree-node").data("id");
                        else
                            var $id = $(this).closest(".jstree-node").attr("id");
                        separator = $id.indexOf("' . $this->itemsIdSeparator . '");
                        if(separator != -1)
                        {
                            var catId = $id.substr(separator + 2);
                            selectedElmsIds.push(catId);
                        }
                    });
                    $("#' . $this->inputId . '").val(JSON.stringify(selectedElmsIds));
                    return false;
                });';


        if($this->onChanged)
            $js .= '$("#' . $this->id . '").on("changed.jstree",' . $this->onChanged . ');';
        if($this->onOpenNode)
            $js .= '$("#' . $this->id . '").on("open_node.jstree",' . $this->onOpenNode . ');';
        if($this->onCloseNode)
            $js .= '$("#' . $this->id . '").on("close_node.jstree",' . $this->onCloseNode . ');';
            if($this->onCheckNode)
            $js .= '$("#' . $this->id . '").on("check_node.jstree",' . $this->onCheckNode . ');';
        if($this->onUnCheckNode)
            $js .= '$("#' . $this->id . '").on("uncheck_node.jstree",' . $this->onUnCheckNode . ');';
        if($this->onSelectNode)
            $js .= '$("#' . $this->id . '").on("select_node.jstree",' . $this->onSelectNode . ');';

//        $js .= '$(document).on("click",\'input[type="submit"]\',function(e){
//                    var selectedElmsIds = new Array();
//                    $("#' . $this->id . ' .jstree-anchor.js-tree-clicked").each(function() {
//                        if (typeof $(this).closest(".jstree-node").data("id") !== typeof undefined && $(this).closest(".jstree-node").data("id") !== false)
//                            var $id = $(this).closest(".jstree-node").data("id");
//                        else
//                            var $id = $(this).closest(".jstree-node").attr("id");
//                        separator = $id.indexOf("' . $this->itemsIdSeparator . '");
//                        if(separator != -1)
//                        {
//                            var catId = $id.substr(separator + 2);
//                            selectedElmsIds.push(catId);
//                        }
//                    });
//                    $("#' . $this->inputId . '").val(JSON.stringify(selectedElmsIds));
//                });';


        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/jsTree.min.css');
        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/bootstrap-theme.min.css');
        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/jsTree.style.css');
        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/jsTree.min.js');
        //Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/jsTree.script.js');
        Yii::app()->getClientScript()->registerScript($this->id."Scripts",$js);
		self::renderView();
	}

    public function getAssetsUrl()
    {
        if(!isset($this->publishedAssetsPath))
        {
            $assetsSourcePath = Yii::getPathOfAlias('application.modules.messages.extensions.RolesJsTree.assets');

            $publishedAssetsPath = Yii::app()->assetManager->publish($assetsSourcePath, false, -1);

            return $this->publishedAssetsPath = $publishedAssetsPath;
        }
        else return $this->publishedAssetsPath;
    }

	private function renderView(){
		$this->render('view');
	}

    protected function getListItem($action){
        $temp = "";

//        $temp .=
//        '<li id="$$'.$action->id.'" '.(in_array($name,$modulePermissions)?'data-jstree=\'{"selected":true}\'':'').'>
//            <span class="js-tree-title">'.$permission['title'].'</span>
//        </li>';

        $temp .=
            '<li id="'.$this->itemsPrefixId.$this->itemsIdSeparator.$action->id.'">
                <span class="js-tree-title">'.$action->title.'</span>';

        if(isset($action->children) AND is_array($action->children)){
            $temp .= '<ul>';
            foreach($action->children as $child)
                $temp .= $this->getListItem($child);
            $temp .= '</ul>';
        }
        $temp .= '</li>';

        return $temp;
    }
}

?>
