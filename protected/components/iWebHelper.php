<?php
class iWebHelper {
    public static function truncate($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false) {
        if ($considerHtml) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }

            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);

            $total_length = strlen($ending);
            $open_tags = array();
            $truncate = '';

            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it’s an “empty element” with or without xhtml-conform closing slash (f.e.)
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag (f.e.)
                    } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = array_search($tag_matchings[1], $open_tags);
                        if ($pos !== false) {
                            unset($open_tags[$pos]);
                        }
                        // if tag is an opening tag (f.e. )
                    } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        array_unshift($open_tags, strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate’d text
                    $truncate .= $line_matchings[1];
                }

                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length+$content_length > $length) {
                    // the number of characters which are left
                    $left = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1]+1-$entities_length <= $left) {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                } else {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }

                // if the maximum length is reached, get off the loop
                if($total_length >= $length) {
                    break;
                }
            }
        } else {
            if (strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = substr($text, 0, $length - strlen($ending));
            }
        }

        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = strrpos($truncate, ' ');
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = substr($truncate, 0, $spacepos);
            }
        }

        // add the defined ending to the text
        $truncate .= $ending;

        if($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '';
            }
        }

        return $truncate;
    }

    public static function searchArabicAndPersian($value)
    {
        $patterns = array('/([.\\+*?\[^\]$(){}=!<>|:-])/','/ی|ي|ئ/','/ک|ك/','/ه|ة/','/ا|آ|إ|أ/','/\s/');
        $replacements = array('','(ی|ي|ئ)','(ک|ك)','(ه|ة)','(ا|آ|إ|أ)','.+');
        return preg_replace($patterns, $replacements, $value);
    }
    public static function getUserId(){
        if(isset(Yii::app()->user->userID))
            return Yii::app()->user->userID;
    }

    public static function jalaliToGregorian($jalaliDate){
        if(isset(Yii::app()->jdate)){
            $jalaliDateArray = explode('-',$jalaliDate);
            $year = intval($jalaliDateArray[0]);
            $month = intval($jalaliDateArray[1]);
            $day = intval($jalaliDateArray[2]);
            $gregorianArray = Yii::app()->jdate->toGregorian($year,$month,$day);

            $gregorian = implode($gregorianArray,'-');

            $time = explode(' ',$jalaliDate);
            $time = $time[1];
            $gregorian .= " ".$time;

            return $gregorian;
        }
        else
            throw new Exception('jalaliDate Component not config');
    }

    public static function jalaliToTime($jalaliDate){
        return strtotime(self::jalaliToGregorian($jalaliDate));
    }

    public static function makePath($pathName, $isFileName=false){

        if($isFileName){

            $pathName = substr($pathName, 0, strrpos($pathName, '/'));

        }

        // Check if directory already exists

        if (is_dir($pathName) || empty($pathName)) {

            return true;

        }

        // Ensure a file does not already exist with the same name

        $pathName = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $pathName);

        if (is_file($pathName)) {

            trigger_error('mkdirr() File exists', E_USER_WARNING);

            return false;

        }

        // Crawl up the directory tree

        $nextPathName = substr($pathName, 0, strrpos($pathName, DIRECTORY_SEPARATOR));

        if (iWebHelper::makePath($nextPathName)) {

            if (!file_exists($pathName)) {

                return mkdir($pathName);

            }

        }

        return false;

    }

    public static function isJSON($string){
        return is_string($string) && is_object(json_decode($string)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }


//    public static function importModule($moduleName,$file='*',$directory='models'){
//        Yii::import("application.$moduleName.contacts.$directory.$file");
//    }
} 