<?
if(!$USER->IsAdmin())
	return;
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$arAllOptions = array(
    array("iblock_type",GetMessage("CONTENT_INCLUDER_OPTIONS_IBLOCK_TYPE"), "", array("text", 25)),
	array("iblock_id", GetMessage("CONTENT_INCLUDER_OPTIONS_IBLOCK_ID"), "0", array("text", 5 )),
    array("include_jquery", GetMessage("CONTENT_INCLUDER_OPTIONS_INCLUDE_JQUERY"), "Y", array("checkbox", "Y")),
    array("add_to_editor", GetMessage("CONTENT_INCLUDER_OPTIONS_ADD_TO_EDITOR"), "Y", array("checkbox", "Y")),
);


CModule::IncludeModule("iblock");
$dbIBlockType = CIBlockType::GetList();
$arIBTypes = array();
$arIB = array();
while ($arIBType = $dbIBlockType->Fetch()){
    if ($arIBTypeData = CIBlockType::GetByIDLang($arIBType["ID"], LANG)){
        $arIB[$arIBType['ID']] = array();
        $arIBTypes[$arIBType['ID']] = '['.$arIBType['ID'].'] '.$arIBTypeData['NAME'];
    }
}

$dbIBlock = CIBlock::GetList(array('SORT' => 'ASC'), array('ACTIVE' => 'Y'));
while ($arIBlock = $dbIBlock->Fetch()){
    $arIB[$arIBlock['IBLOCK_TYPE_ID']][$arIBlock['ID']] = ($arIBlock['CODE'] ? '['.$arIBlock['CODE'].'] ' : '').$arIBlock['NAME'];
}


$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("MAIN_TAB_SET"), "ICON" => "ib_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults)>0 && check_bitrix_sessid())
{
	if(strlen($RestoreDefaults)>0)
	{
		COption::RemoveOption("itsfera.includer");
	}
	else
	{
		foreach($arAllOptions as $arOption)
		{
			$name=$arOption[0];
			$val=$_REQUEST[$name];
			if($arOption[2][0]=="checkbox" && $val!="Y")
				$val="N";
			COption::SetOptionString("itsfera.includer", $name, $val, $arOption[1]);
		}
	}
	if(strlen($Update)>0 && strlen($_REQUEST["back_url_settings"])>0)
		LocalRedirect($_REQUEST["back_url_settings"]);
	else
		LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
}


$tabControl->Begin();
?>
<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?echo LANGUAGE_ID?>">
<?$tabControl->BeginNextTab();?>
    <tr>
        <td>
            <label for="iblock_type"><?=GetMessage("CONTENT_INCLUDER_OPTIONS_IBLOCK_TYPE")?>:</label></td>
        <td>
            <select name="iblock_type" onchange="changeIblockList(this.value)">
                <option value=""><?= GetMessage('CAL_NOT_SET')?></option>
                <?foreach ($arIBTypes as $ibtype_id => $ibtype_name):?>
                    <option value="<?= $ibtype_id?>" <?if($ibtype_id == COption::GetOptionString("itsfera.includer", 'iblock_type')){echo ' selected="selected"';}?>><?= $ibtype_name?></option>
                <?endforeach;?>
            </select>
        </td>
    </tr>
    <tr>
        <td><label for="iblock_id"><?=GetMessage("CONTENT_INCLUDER_OPTIONS_IBLOCK_ID")?>:</label></td>
        <td>
            <select id="iblock_id" name="iblock_id">
                <?if (COption::GetOptionString("itsfera.includer", 'iblock_type',0)!==0):?>
                    <?foreach ($arIB[ COption::GetOptionString("itsfera.includer", 'iblock_type',0) ] as $iblock_id => $iblock):?>
                        <option value="<?= $iblock_id?>"<? if($iblock_id == COption::GetOptionString("itsfera.includer", 'iblock_id')){echo ' selected="selected"';}?>><?= $iblock?></option>
                    <?endforeach;?>
                <?else:?>
                    <option value="">Not set</option>
                <?endif;?>

            </select>
        </td>
    </tr>

    <script>

        var arIblocks = <?= CUtil::PhpToJsObject($arIB)?>;
        function changeIblockList(value, index)
        {
            if (null == index)
                index = 0;

            var
                i, j,
                arControls = [
                    BX('iblock_id')
                ];

            for (i = 0; i < arControls.length; i++)
            {
                if (arControls[i])
                    arControls[i].options.length = 0;

                if (!value)
                {
                    arControls[i].options[0] = new Option('<?= GetMessage('CAL_NOT_SET')?>', '');
                    continue;
                }

                for (j in arIblocks[value])
                    arControls[i].options[arControls[i].options.length] = new Option(arIblocks[value][j], j);
            }
        }
    </script>



	<?
	foreach($arAllOptions as $arOption):
        if ( in_array($arOption[0],array('iblock_type','iblock_id')) ) continue;
		$val = COption::GetOptionString("itsfera.includer", $arOption[0], $arOption[2]);
		$type = $arOption[3];
	?>
	<tr>
		<td width="40%" nowrap <?if($type[0]=="textarea") echo 'class="adm-detail-valign-top"'?>>
			<label for="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo $arOption[1]?>:</label>
		<td width="60%">
			<?if($type[0]=="checkbox"):?>
				<input type="checkbox" id="<?echo htmlspecialcharsbx($arOption[0])?>" name="<?echo htmlspecialcharsbx($arOption[0])?>" value="Y"<?if($val=="Y")echo" checked";?>>
			<?elseif($type[0]=="text"):?>
				<input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($arOption[0])?>">
			<?elseif($type[0]=="textarea"):?>
				<textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo htmlspecialcharsbx($val)?></textarea>
			<?endif?>
		</td>
	</tr>
	<?endforeach?>
<?$tabControl->Buttons();?>
	<input type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
	<input type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
	<?if(strlen($_REQUEST["back_url_settings"])>0):?>
		<input type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
		<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
	<?endif?>
	<input type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="return confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
	<?=bitrix_sessid_post();?>
<?$tabControl->End();?>
</form>