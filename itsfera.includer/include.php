<?php

/*
class ItsferaIncluderHandlers {

    //AddEventHandler("fileman", "OnIncludeHTMLEditorScript", "OnIncludeHTMLEditorScriptHandler");
    function OnIncludeHTMLEditorScriptHandler()
    {
        $script_filename = '/slider.js';
        echo '<script type="text/javascript" src="'.$script_filename.'?v='.@filemtime($_SERVER['DOCUMENT_ROOT'].$script_filename).'"></script>';
    }

    //AddEventHandler("main", "OnEndBufferContent", "OnEndBufferContentHandler");
    public static function OnEndBufferContentHandler(&$content)
    {
        if (!defined("ADMIN_SECTION")){

            $content = 'work!'.$content;
            //sliderIncluder($content,CCustomProject::getIBlockIdByCode('ru_gallery'),"[slider]","[/slider]");
            //photoIncluder($content,CCustomProject::getIBlockIdByCode('ru_gallery'),"[photo]","[/photo]");
        }
    }
}*/