<?
namespace Bit\Includer;
use Bitrix\Main\Entity;
class Main{

    const NEWS_LIST_TEMPLATE = "bit.includer";
    const DETAIL_TEMPLATE = "bit.includer";


    //функционал вставки слайдера в контент [slider]section_id[/slider]
    public static function listIncluder(&$content)
    {
        //анонимные функции доступны только в >= 5.3
        $content = preg_replace_callback("/\[list\]([^\[]*)\[\/list\]/is",
            create_function('$matches','
                return '.__NAMESPACE__.'\Main::makeList( $matches[1] );
            '),$content);
    }

    public static function detailIncluder(&$content)
    {
        //анонимные функции доступны только в >= 5.3
        $content = preg_replace_callback("/\[detail\]([^\[]*)\[\/detail\]/is",
            create_function('$matches','
                return '.__NAMESPACE__.'\Main::makeDetail( $matches[1] );
            '),$content);
    }


    public static function makeList( $iSectionId )
    {
        global $APPLICATION;

        $iIblockId =  \COption::GetOptionString("bit.includer", "iblock_id", false);
        $iIblockType =  \COption::GetOptionString("bit.includer", "iblock_type", "") ;

        $rsEvents = GetModuleEvents("bit.includer", "OnBeforeMakeList");
        if ($arEvent = $rsEvents->Fetch()){
            return ExecuteModuleEvent($arEvent, $iSectionId,$iIblockId);
        }

        //вывод по коду раздела
        if (!is_numeric($iSectionId) && strlen($iSectionId)>0){
            \CModule::IncludeModule("iblock");
            $arFilter = Array('IBLOCK_ID'=>$iIblockId, 'CODE'=>$iSectionId);
            $db_list = \CIBlockSection::GetList(Array(), $arFilter, true);
            if($ar_result = $db_list->GetNext()){
                $iSectionId = $ar_result['ID'];
            }else {
                return "";
            }
        }
        ob_start();
        $APPLICATION->IncludeComponent(
        "bitrix:news.list",
            self::NEWS_LIST_TEMPLATE,
            Array(
                "DISPLAY_DATE" => "N",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => $iIblockType,
                "IBLOCK_ID" => $iIblockId,
                "NEWS_COUNT" => "0",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "FILTER_NAME" => "",
                "FIELD_CODE" => array("DETAIL_PICTURE"),
                "PROPERTY_CODE" => array(),
                "CHECK_DATES" => "N",
                "DETAIL_URL" => "",
                "PREVIEW_TRUNCATE_LEN" => "",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "SET_STATUS_404" => "N",
                "SET_TITLE" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "PARENT_SECTION" => $iSectionId,
                "PARENT_SECTION_CODE" => "",
                "INCLUDE_SUBSECTIONS" => "Y",
                "CACHE_TYPE" => "N",
                "CACHE_TIME" => "3600",
                "CACHE_NOTES" => "",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "PAGER_TEMPLATE" => ".default",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "PAGER_TITLE" => "",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "Y",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N"
            )
        );

        $componentContent = ob_get_clean();

        //Проверяем на наличие рекурсии
        $componentContent = preg_replace_callback("/\[(list|detail)\]([^\[]*)\[\/(list|detail)\]/is",
            create_function('$matches','
                return "";
            '),$componentContent);

        return $componentContent;

    }




    public static function makeDetail( $iPhotoId )
    {
        global $APPLICATION;

        $iIblockId =  intval( \COption::GetOptionString("bit.includer", "iblock_id", 0) );
        $iIblockType =  \COption::GetOptionString("bit.includer", "iblock_type", "") ;

        $rsEvents = GetModuleEvents("bit.includer", "OnBeforeMakeDetail");
        if ($arEvent = $rsEvents->Fetch()){
            return ExecuteModuleEvent($arEvent, $iPhotoId,$iIblockId);
        }

        //вывод по коду раздела
        if (!is_numeric($iPhotoId) && strlen($iPhotoId)>0){
            \CModule::IncludeModule("iblock");
            $arFilter = Array("IBLOCK_ID"=>$iIblockId, "CODE"=>$iPhotoId);
            $res = \CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), Array("ID"));
            if($ob = $res->GetNextElement()){
                $arFields = $ob->GetFields();
                $iPhotoId = $arFields['ID'];
            }
        }

        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:news.detail",
            self::DETAIL_TEMPLATE,
            Array(
                "DISPLAY_DATE" => "N",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "N",
                "USE_SHARE" => "N",
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => $iIblockType,
                "IBLOCK_ID" => $iIblockId,
                "ELEMENT_ID" => $iPhotoId,
                "ELEMENT_CODE" => "",
                "CHECK_DATES" => "N",
                "FIELD_CODE" => array(),
                "PROPERTY_CODE" => array(),
                "IBLOCK_URL" => "",
                "META_KEYWORDS" => "-",
                "META_DESCRIPTION" => "-",
                "BROWSER_TITLE" => "-",
                "SET_STATUS_404" => "N",
                "SET_TITLE" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "ADD_ELEMENT_CHAIN" => "N",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "USE_PERMISSIONS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "CACHE_NOTES" => "",
                "CACHE_GROUPS" => "Y",
                "PAGER_TEMPLATE" => ".default",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "PAGER_TITLE" => "Страница",
                "PAGER_SHOW_ALL" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "N",
                "AJAX_OPTION_HISTORY" => "N"
            ),
            false
        );
        $componentContent = ob_get_clean();

        //Проверяем на наличие рекурсии
        $componentContent = preg_replace_callback("/\[(list|detail)\]([^\[]*)\[\/(list|detail)\]/is",
            create_function('$matches','
                return "";
            '),$componentContent);

        return $componentContent;
    }

    function getAvailableSections()
    {
        $iIblockId =  \COption::GetOptionString("bit.includer", "iblock_id", false);
        $arSections = array();
        if ( intval($iIblockId)>0){
            \CModule::IncludeModule("iblock");
            $arFilter = Array('IBLOCK_ID'=>$iIblockId, 'ACTIVE'=>"Y");
            $db_list = \CIBlockSection::GetList(Array(), $arFilter, true);
            while($ar_result = $db_list->GetNext()){
                $arSections[ $ar_result['ID'] ] = $ar_result['NAME'];

            }
        }
        return $arSections;
    }
}