<?IncludeModuleLangFile(__FILE__);?>
<form action="<?echo $APPLICATION->GetCurPage()?>" name="form1">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?echo LANG?>">
    <input type="hidden" name="id" value="itsfera.includer">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="2">
    <select name="site_id">
        <?foreach($arSitesList as $iSiteId=>$sSiteName):?>
            <option value="<?=$iSiteId?>"><?=$sSiteName?></option>
        <?endforeach?>
    </select>
    <p><input type="checkbox" name="install_sample_iblock" value="Y" checked>
        <label for="save_tables"><?echo GetMessage("CONTENT_INCLUDER_INSTALL_IBLOCK")?></label></p>
    <input type="submit" name="inst" value="<?echo GetMessage("MOD_INSTALL")?>">
</form>