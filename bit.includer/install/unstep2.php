<?if(!check_bitrix_sessid()) return;?>
<?
echo CAdminMessage::ShowNote( GetMessage("MODULE_REMOVE_SUCCESS"));
?>
<form action="<?echo $APPLICATION->GetCurPage()?>">
    <input type="hidden" name="lang" value="<?echo LANG?>">
    <input type="submit" name="" value="<?echo GetMessage("MOD_BACK")?>">
    <form>