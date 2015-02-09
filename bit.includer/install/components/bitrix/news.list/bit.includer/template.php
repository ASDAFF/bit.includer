<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
if (!isset($arResult["ITEMS"][0])) return false;
if ( !defined("INCLUDER_JS_ALREADY_ADDED") ):
    if (COption::GetOptionString("bit.includer", "include_jquery", "N")=="Y"):?>
        <script type="text/javascript" src="<?php echo $templateFolder;?>/js/jquery-1.9.1.min.js"></script><?
    endif;
    ?><script type="text/javascript" src="<?php echo $templateFolder;?>/js/jssor.slider.mini.js"></script>
    <link href="<?php echo $templateFolder;?>/style.css" type="text/css"  rel="stylesheet" /><?
    define("INCLUDER_JS_ALREADY_ADDED", "Y");
endif;
?><script><?
    $unique = microtime();
    ?>jQuery(document).ready(function ($) {
            var options = {
                $ArrowNavigatorOptions: {
                    $Class: $JssorArrowNavigator$,
                    $ChanceToShow: 2
                }
            };
    var jssor_slider1 = new $JssorSlider$('slider_container<?php echo $unique;?>', options);
    });
</script>
<div id="slider_container<?php echo $unique?>" style="position: relative; top: 0px; left: 0px; width: 600px; height: 300px;">
    <!-- Slides Container -->
    <div u="slides" style="cursor: move; position: absolute; overflow: hidden; left: 0px; top: 0px; width: 600px; height: 300px;">
<?foreach($arResult["ITEMS"] as $arItem):?>
    <div><?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["DETAIL_PICTURE"])):
            ?><img
                    src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>"
                    width="<?=$arItem["DETAIL_PICTURE"]["WIDTH"]?>"
                    height="<?=$arItem["DETAIL_PICTURE"]["HEIGHT"]?>"
                    alt="<?=$arItem["DETAIL_PICTURE"]["ALT"]?>"
                    title="<?=$arItem["DETAIL_PICTURE"]["TITLE"]?>"
                    /><!-- Any HTML Content Here --><?
        endif;?>
    </div>
<?endforeach;?>
    </div>

    <!-- Arrow Left -->
        <span u="arrowleft" class="jssora02l" style="width: 55px; height: 55px; top: 123px; left: 8px;">
        </span>
    <!-- Arrow Right -->
        <span u="arrowright" class="jssora02r" style="width: 55px; height: 55px; top: 123px; right: 8px">
        </span>
</div>
