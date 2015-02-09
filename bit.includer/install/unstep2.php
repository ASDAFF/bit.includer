<?if(!check_bitrix_sessid()) return;?>
<?
echo CAdminMessage::ShowNote( GetMessage("MODULE_REMOVE_SUCCESS"));
?>
<form action="<?echo $APPLICATION->GetCurPage()?>">
    <input type="hidden" name="lang" value="<?echo LANG?>">
    <p><input type="checkbox" id="sample_iblock" name="remove_sample_iblock" value="Y" checked>
        <label for="sample_iblock"><?echo GetMessage("CONTENT_INCLUDER_REMOVE_IBLOCK")?></label></p>
    <input type="submit" name="" value="<?echo GetMessage("MOD_BACK")?>">
    <form>