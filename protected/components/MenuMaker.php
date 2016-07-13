<?php
class MenuMaker
{
    public
        $tempMenu = array(),
        $controllers = array(),
        $theme,$module;

    public function __construct($tempMenu,$controllers,$theme = 'default',$module='default'){
        $this->theme = $theme;
        $this->controllers = $controllers;
        $this->module = $module;
        foreach($tempMenu as $menu){
            if(!is_null($menu))
                $this->mergeMenuNames($menu);
        }
    }

    protected function menuParents($controllers){
        $items=array();
        foreach($controllers as $menu)
        {
            if(isset($menu['menu']) AND $this->isTrueType($menu)){
                if($this->module != 'default' AND isset($menu['module']))
                {
                    if(is_string($menu['module']) AND strpos($menu['module'],","))
                        $menu['module'] = explode(",",$menu['module']);
                    if(
                        (is_array($menu['module']) AND !in_array($this->module,$menu['module']))
                            OR
                        (is_string($menu['module']) AND ($menu['module'] != $this->module))
                    )
                        continue;
                }
                $itemOptions = array();
                if($this->theme == 'default')
                    $itemOptions = array('class'=>'dropdown pull-right','tabindex'=>"-1");


                $items[$menu['menu_name']]=array(
                    'parent' => (isset($menu['menu_parent'])?$menu['menu_parent']:NULL),
                    'label' => (isset($menu['title'])?$menu['title']:'بدون عنوان'),
                    'url' => (isset($menu['url'])?Yii::app()->createAbsoluteUrl($menu['url']):'#'),
                    'itemOptions' => $itemOptions,
                    //'itemOptions' => (isset($menu['itemOptions'])?$menu['itemOptions']:array()),
                    //'linkOptions' => (isset($menu['linkOptions'])?$menu['linkOptions']:array()),
                );

            }
        }
        return $items;
    }

    protected function isTrueType($controller){
        if(isset($controller['type'])){
            if($controller['type'] == 'all')
                return true;
            elseif(($controller['type'] == Yii::app()->user->type) // if type of controller access equal with login user
                AND (in_array($controller['menu_name'],$this->tempMenu) OR // if user has permission to show menu
                    ((Yii::app()->user->type == 'admin') AND Yii::app()->user->name == 'admin'))) // if he/she is admin show all menus
                return true;
        }
        elseif(isset($controller['menu_name'])
            AND (in_array($controller['menu_name'],$this->tempMenu) OR // if user has permission to show menu
                ((Yii::app()->user->type == 'admin') AND Yii::app()->user->name == 'admin'))) // if he/she is admin show all menus
            return true;
        return false;
    }

    protected function mergeMenuNames($allPermissions){
        $controllers = $this->controllers;
        foreach($allPermissions as $controller=>$permissions){
            if(isset($controllers[$controller]['menu_name']) AND !in_array($controllers[$controller]['menu_name'],$this->tempMenu))
                $this->tempMenu[] = $controllers[$controller]['menu_name'];


            foreach($permissions as $permission){
                if(isset($controllers[$controller][$permission]['menu_name']) AND !in_array($controllers[$controller][$permission]['menu_name'],$this->tempMenu))
                    $this->tempMenu[] = $controllers[$controller][$permission]['menu_name'];
            }
        }
    }

    public function publish(){
        $controllers = $this->controllers;
        $items = array();
        if(!Yii::app()->user->isGuest and isset(Yii::app()->user->userID))
        {
            $items = array_merge($items,$this->menuParents($controllers));
            foreach($controllers as $controller=>$menu)
            {
                $items = array_merge($items,$this->menuParents($menu));
            }

            $currentClassName = get_class(Yii::app()->controller);
            $currentClassName = $currentClassName::$actionsArray;
            $current = array();
            if(isset($currentClassName['menu_parent']))
                $current[] = $currentClassName['menu_parent'];
            if(isset($currentClassName['menu_name']))
                $current[] = $currentClassName['menu_name'];

            foreach($currentClassName as $action){
                if(is_array($action))
                {
                    if(isset($action['menu_parent']))
                        $current[] = $action['menu_parent'];
                    if(isset($action['menu_name']))
                        $current[] = $action['menu_name'];
                }
            }

            foreach($items as $key=>$item)
            {
                if(isset($item['parent'])){
                    $itemOptions = array();
                    $linkOptions = array();
                    if($this->theme == 'default')
                    {
                        $itemOptions = array('class'=>'dropdown pull-right','tabindex'=>"-1");
                        $linkOptions = array('class'=>'dropdown-toggle','data-toggle'=>"dropdown");
                    }

                    $items[$item['parent']]['items'][] = $item;

                    if($this->theme == 'default')
                        $items[$item['parent']]['label'] .= $this->setCaret($items[$item['parent']]['label']);

                    if(in_array($item['parent'],$current)){
                        if(isset($itemOptions['class']))
                            $itemOptions['class'] .= ' active';
                        else
                            $itemOptions['class'] = 'active';

                    }

                    $items[$item['parent']]['itemOptions'] = $itemOptions;
                    $items[$item['parent']]['linkOptions'] = $linkOptions;
                    unset($items[$key]);
                }
            }

            // check if menu url not set and has not sub menu remove that
            foreach($items as $key=>$item)
            {

                if((!isset($item['items']) AND $item['url']=='#')){
                    unset($items[$key]);
                }
            }
        }
        return $items;
    }

    protected function setCaret($value){

        if(is_string($value)){
            if(!preg_match('/class=["\']{0,1}caret["\']{0,1}/',$value)){
                return ' <div class="caret"></div>';
            }
        }
    }
}