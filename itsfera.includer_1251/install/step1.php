<?IncludeModuleLangFile(__FILE__);?>
<form action="<?echo $APPLICATION->GetCurPage()?>" name="form1">
    <?=bitrix_sessid_post()?>
    <p><?echo GetMessage("CONTENT_INCLUDER_INSTALL_PARAMS"); ?></p>
    <input type="hidden" name="lang" value="<?echo LANG?>">
    <input type="hidden" name="id" value="itsfera.includer">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="2">
    <p><input type="checkbox" id="include_jquery" name="include_jquery_for_component" value="Y" checked>
        <label for="include_jquery"><?echo GetMessage("CONTENT_INCLUDER_INCLUDE_JQUERY")?></label></p>

    <?
    //если используется новый визуальный редактор, предлагать переключиться на старый
    if (COption::getOptionString("fileman","use_editor_3","S")==="Y"):?>
    <p><input type="checkbox" id="disable_new_editor" name="disable_new_editor" value="Y">
        <label for="disable_new_editor"><?echo GetMessage("CONTENT_INCLUDER_DISABLE_NEW_EDITOR")?></label></p>
    <?endif?>

    <p><input type="checkbox" id="sample_iblock" name="install_sample_iblock" value="Y" checked>
        <label for="sample_iblock"><?echo GetMessage("CONTENT_INCLUDER_INSTALL_IBLOCK")?></label></p>

    <?if (count($arSitesList)>1):?>
        <p><label for="site_select"><?echo GetMessage("CONTENT_INCLUDER_SELECT_SITE")?>:</label><br />
        <select name="site_id" id="site_select">
            <?foreach($arSitesList as $iSiteId=>$sSiteName):?>
                <option value="<?=$iSiteId?>"><?=$sSiteName?></option>
            <?endforeach?>
        </select></p>
    <?else:
        reset($arSitesList);
        $first_key = key($arSitesList);
        ?><input type="hidden" name="site_id" value="<?echo $first_key?>">
    <?endif?>
    <input type="submit" name="inst" value="<?echo GetMessage("MOD_INSTALL")?>">
</form>