<?php
IncludeModuleLangFile(__FILE__);
Class bit_includer extends CModule
{
    var $MODULE_ID = "bit.includer";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";

    var $sModulePath;
    var $sModuleInstallFullPath;

    function __construct()
    {
        $arModuleVersion = array();
        include("version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->PARTNER_NAME = GetMessage("CONTENT_INCLUDER_PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("CONTENT_INCLUDER_PARTNER_URI");
        $this->MODULE_NAME = GetMessage("CONTENT_INCLUDER_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("CONTENT_INCLUDER_MODULE_DESC");

        $this->sModulePath = "/bitrix/modules/";
        if ( strpos(__FILE__,"/local/modules/")!==false ) $this->sModulePath = "/local/modules/";

        $this->sModuleInstallFullPath = $_SERVER["DOCUMENT_ROOT"].$this->sModulePath.$this->MODULE_ID."/install/";
    }

    function DoInstall()
    {
        global $APPLICATION,$DOCUMENT_ROOT,$USER, $step,$arSitesList;


        if ($USER->IsAdmin())
        {
            $arSitesList = array();
            $rsSites = CSite::GetList($by="sort", $order="desc", Array());
            while ($arSite = $rsSites->Fetch()){
                $arSitesList[ $arSite['ID'] ] = $arSite['NAME'];
            }


            $step = IntVal($step);
            if ($step < 2)
            {
                $APPLICATION->IncludeAdminFile( GetMessage("CONTENT_INCLUDER_MODULE_INSTALL_TITLE"). $this->MODULE_ID, $this->sModuleInstallFullPath."step1.php");
            }elseif ($step == 2){

                RegisterModule($this->MODULE_ID);
                RegisterModuleDependences("main", "OnEndBufferContent", $this->MODULE_ID, '\Bit\Includer\Handlers', "OnEndBufferContentHandler");
                RegisterModuleDependences("fileman", "OnIncludeHTMLEditorScript", $this->MODULE_ID, '\Bit\Includer\Handlers', "OnIncludeHTMLEditorScriptHandler");
                $this->InstallFiles();

                $sSiteId = array_key_exists($_REQUEST['site_id'],$arSitesList)?$_REQUEST['site_id']:SITE_ID;
                if ($_REQUEST['install_sample_iblock']==="Y"){
                    $this->createSampleIblock( $sSiteId );
                }

                if ($_REQUEST['disable_new_editor']==="Y") {
                    COption::setOptionString("fileman", "use_editor_3", "");
                }

                COption::SetOptionString("bit.includer", "include_jquery", $_REQUEST['include_jquery_for_component']==="Y"?"Y":"N");


                $APPLICATION->IncludeAdminFile( GetMessage("CONTENT_INCLUDER_MODULE_INSTALL_TITLE"),  $this->sModuleInstallFullPath."step2.php");
            }
        }
    }

    function DoUninstall()
    {
        global $APPLICATION,$step;

        $step = IntVal($step);
        if ($step < 2)
        {
            $APPLICATION->IncludeAdminFile( GetMessage("CONTENT_INCLUDER_MODULE_UNINSTALL_TITLE"). $this->MODULE_ID, $this->sModuleInstallFullPath."unstep1.php");

        }elseif ($step == 2){

            UnRegisterModule($this->MODULE_ID);
            UnRegisterModuleDependences("main", "OnEndBufferContent", $this->MODULE_ID, '\Bit\Includer\Handlers', "OnEndBufferContentHandler");
            UnRegisterModuleDependences("fileman", "OnIncludeHTMLEditorScript", $this->MODULE_ID, '\Bit\Includer\Handlers', "OnIncludeHTMLEditorScriptHandler");
            $APPLICATION->IncludeAdminFile( GetMessage("CONTENT_INCLUDER_MODULE_UNINSTALL_TITLE"). $this->MODULE_ID, $this->sModuleInstallFullPath."unstep2.php");
        }


    }

    function InstallFiles($arParams = array())
    {
        self::CopyDirFilesWrapper($this->sModuleInstallFullPath."components/bitrix",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/.default/components/bitrix/", true, true);

        self::CopyDirFilesWrapper($this->sModuleInstallFullPath."js",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/htmleditor2/", true, true);

        self::CopyDirFilesWrapper($this->sModuleInstallFullPath."images",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/images/bit.includer/", true, true);

        return true;
    }

    public static function CopyDirFilesWrapper($from,$to)
    {
        CopyDirFiles($from,$to, true, true);
    }

    function createSampleIblock( $sSiteId )
    {
        global $APPLICATION;
        //include($this->sModuleInstallFullPath."bm.php");
        $arResult = include($this->sModuleInstallFullPath."import.php"); //$sSiteId добавляется в массив в этом файле

        $bm = new Bit\Includer\BitrixMigration($this->sModuleInstallFullPath);
        $bm->sStoreFilesDir = 'bm_files/';
        $bm->uploadArray($arResult);

        COption::SetOptionString("bit.includer", "iblock_type", "bit_includer");
        COption::SetOptionString("bit.includer", "iblock_id", $iIblockId=$this->getIBlockIdByCode("bit_includer_content"));
        COption::SetOptionString("bit.includer", "demo_iblock_id", $iIblockId);

        define("FOR_ALL_USERS_GROUP_ID",2); //Id групп для всех пользователей по-умолчанию
        CIBlock::SetPermission($iIblockId, Array(FOR_ALL_USERS_GROUP_ID=>"R"));

    }

    function getIBlockIdByCode($sIBlockCode)
    {
        if(CModule::IncludeModule('iblock')) {
            $arFilter = array(
                'CODE' => $sIBlockCode,
                'ACTIVE' => 'Y',
                'CHECK_PERMISSIONS' => 'N'
            );
            $dbItems = CIBlock::GetList(array('ID' => 'ASC'), $arFilter, false);
            if($arItem = $dbItems->Fetch()) {
                return intval($arItem['ID']);
            }
        }
        return false;
    }



    function UnInstallFiles()
    {
        DeleteDirFilesEx("/bitrix/templates/.default/components/bitrix/news.list/bit.includer");
        DeleteDirFilesEx("/bitrix/templates/.default/components/bitrix/news.detail/bit.includer");
        DeleteDirFilesEx("/bitrix/images/bit.includer");
        return true;
    }

}

?>