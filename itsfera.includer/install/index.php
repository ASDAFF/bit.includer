<?php
IncludeModuleLangFile(__FILE__);
Class itsfera_includer extends CModule
{
    var $MODULE_ID = "itsfera.includer";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";

    var $sModulePath;

    function __construct()
    {
        include("version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->PARTNER_NAME = GetMessage("CONTENT_INCLUDER_PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("CONTENT_INCLUDER_PARTNER_URI");
        $this->MODULE_NAME = GetMessage("CONTENT_INCLUDER_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("CONTENT_INCLUDER_MODULE_DESC");

        $this->sModulePath = "/bitrix/modules/";
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
                $APPLICATION->IncludeAdminFile( GetMessage("CONTENT_INCLUDER_MODULE_INSTALL_TITLE"). $this->MODULE_ID, $DOCUMENT_ROOT.$this->sModulePath.$this->MODULE_ID."/install/step1.php");
            }elseif ($step == 2){

                RegisterModule($this->MODULE_ID);
                RegisterModuleDependences("main", "OnEndBufferContent", $this->MODULE_ID, '\Itsfera\Includer\Handlers', "OnEndBufferContentHandler");
                RegisterModuleDependences("fileman", "OnIncludeHTMLEditorScript", $this->MODULE_ID, '\Itsfera\Includer\Handlers', "OnIncludeHTMLEditorScriptHandler");
                $this->InstallFiles();

                $sSiteId = array_key_exists($_REQUEST['site_id'],$arSitesList)?$_REQUEST['site_id']:SITE_ID;
                if ($_REQUEST['install_sample_iblock']==="Y"){
                    $this->createSampleIblock( $sSiteId );
                }

                if ($_REQUEST['disable_new_editor']==="Y") {
                    COption::setOptionString("fileman", "use_editor_3", "");
                }

                COption::SetOptionString("itsfera.includer", "include_jquery", $_REQUEST['include_jquery_for_component']==="Y"?"Y":"N");


                $APPLICATION->IncludeAdminFile( GetMessage("CONTENT_INCLUDER_MODULE_INSTALL_TITLE"),  $DOCUMENT_ROOT.$this->sModulePath.$this->MODULE_ID."/install/step2.php");
            }
        }
    }

    function DoUninstall()
    {
        global $APPLICATION,$DOCUMENT_ROOT;
        UnRegisterModule($this->MODULE_ID);
        UnRegisterModuleDependences("main", "OnEndBufferContent", $this->MODULE_ID, '\Itsfera\Includer\Handlers', "OnEndBufferContentHandler");
        UnRegisterModuleDependences("fileman", "OnIncludeHTMLEditorScript", $this->MODULE_ID, '\Itsfera\Includer\Handlers', "OnIncludeHTMLEditorScriptHandler");
        $APPLICATION->IncludeAdminFile( GetMessage("CONTENT_INCLUDER_MODULE_UNINSTALL_TITLE"). $this->MODULE_ID, $DOCUMENT_ROOT.$this->sModulePath.$this->MODULE_ID."/install/unstep.php");

    }

    function InstallFiles($arParams = array())
    {
        self::CopyDirFilesWrapper($_SERVER["DOCUMENT_ROOT"].$this->sModulePath.$this->MODULE_ID."/install/components/bitrix",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/.default/components/bitrix/", true, true);

        self::CopyDirFilesWrapper($_SERVER["DOCUMENT_ROOT"].$this->sModulePath.$this->MODULE_ID."/install/js",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/htmleditor2/", true, true);


        return true;
    }

    public static function CopyDirFilesWrapper($from,$to)
    {
        if (!is_writable($to)) {

            echo CAdminMessage::ShowMessage( getMessage("COPY_ERROR_DIR_IS_NOR_WRITABLE",Array ("#PATH#" => $to,"#FROM#"=>$from)) );
            //echo 'Dir is not writable';
            return false;
        }
        CopyDirFiles($from,$to, true, true);
    }

    function createSampleIblock( $sSiteId )
    {
        global $APPLICATION;
        include("bm.php");
        $arResult = include("import.php"); //$sSiteId добавляется в массив в этом файле

        $bm = new BitrixMigration($_SERVER["DOCUMENT_ROOT"].$this->sModulePath.$this->MODULE_ID."/install/");
        $bm->sStoreFilesDir = 'bm_files/';
        $bm->uploadArray($arResult);

        COption::SetOptionString("itsfera.includer", "iblock_type", "itsfera_includer");
        COption::SetOptionString("itsfera.includer", "iblock_id", $this->getIBlockIdByCode("itsfera_includer_content"));

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
        DeleteDirFilesEx("/bitrix/templates/.default/components/bitrix/news.list/itsfera.includer");
        DeleteDirFilesEx("/bitrix/templates/.default/components/bitrix/news.detail/itsfera.includer");
        return true;
    }

}

?>