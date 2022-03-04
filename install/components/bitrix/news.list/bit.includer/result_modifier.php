<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$iMaxWidth = 600;
$iMaxHeight = 300;

foreach ($arResult['ITEMS'] as $k=>&$arItem){
    if (is_array($arItem['DETAIL_PICTURE'])) {
        $file = CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], array('width'=>$iMaxWidth, 'height'=>$iMaxHeight),BX_RESIZE_IMAGE_EXACT , true);
        //BX_RESIZE_IMAGE_PROPORTIONAL
        $arItem['DETAIL_PICTURE']['SRC'] = $file['src'];
        $arItem['DETAIL_PICTURE']['WIDTH'] = $file['width'];
        $arItem['DETAIL_PICTURE']['HEIGHT'] = $file['height'];
    }
}