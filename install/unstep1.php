<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeModuleLangFile(__FILE__);
?><form action="<?echo $APPLICATION->GetCurPage()?>">
    <?=bitrix_sessid_post()?>
    <p><?echo GetMessage("CONTENT_INCLUDER_REMOVE_PARAMS"); ?></p>
    <input type="hidden" name="lang" value="<?echo LANG?>">
    <input type="hidden" name="id" value="bit.includer">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    <p><input type="checkbox" id="sample_iblock" name="remove_sample_iblock" value="Y" checked>
        <label for="sample_iblock"><?echo GetMessage("CONTENT_INCLUDER_REMOVE_IBLOCK")?></label></p>
    <input type="submit" name="inst" value="<?echo GetMessage("MOD_REMOVE")?>">
</form>