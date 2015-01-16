<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$iMaxWidth = 600;
$iMaxHeight = 500;

if (is_array($arResult['DETAIL_PICTURE'])) {
    $file = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], array('width'=>$iMaxWidth, 'height'=>$iMaxHeight),BX_RESIZE_IMAGE_PROPORTIONAL , true);
    $arResult['DETAIL_PICTURE']['SRC'] = $file['src'];
    $arResult['DETAIL_PICTURE']['WIDTH'] = $file['width'];
    $arResult['DETAIL_PICTURE']['HEIGHT'] = $file['height'];
}
