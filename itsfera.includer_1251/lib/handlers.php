<?php
namespace Itsfera\Includer;

class Handlers {


    function OnIncludeHTMLEditorScriptHandler()

    {
        //TODO ������� �������� ������������ �� ����� ���������� ��������


        $arSections = Main::getAvailableSections();

        echo '<script>
        var arSections = [';
        $count = 0;
        echo "{value: '[detail]0[/detail]', name: '���� �������'}";
        if ( count($arSections)>0 ) echo ",\n";
        foreach($arSections as $iSectionId=>$sSectionName){
            $count++;
            echo "{value: '[list]".intval($iSectionId)."[/list]', name: '".$sSectionName."'}";
            if ($count!=count($arSections)) echo ",\n";
        }
        echo ']
        </script>
        ';
        $script_filename = '/bitrix/admin/htmleditor2/itsfera_includer.js';
        echo '<script type="text/javascript" src="'.$script_filename.'?v='.@filemtime($_SERVER['DOCUMENT_ROOT'].$script_filename).'"></script>';
    }
    //AddEventHandler("main", "OnEndBufferContent", "OnEndBufferContentHandler");
    public static function OnEndBufferContentHandler(&$content)
    {
        if (!defined("ADMIN_SECTION")){
            Main::listIncluder($content);
            Main::detailIncluder($content);
        }
    }
}