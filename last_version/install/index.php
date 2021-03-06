<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

class prominado_bootstrap extends CModule
{
    var $MODULE_ID = 'prominado.bootstrap';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $MODULE_GROUP_RIGHTS = 'Y';

    public function prominado_bootstrap()
    {
        $arModuleVersion = [];

        include __DIR__ . '/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_NAME = Loc::getMessage('PROMINADO_BOOTSTRAP_INSTALL_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('PROMINADO_BOOTSTRAP_INSTALL_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('PROMINADO_BOOTSTRAP_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('PROMINADO_BOOTSTRAP_PARTNER_URI');
    }


    public function InstallDB()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->registerEventHandler('main', 'OnBeforeProlog', $this->MODULE_ID, '\\Prominado\\Bootstrap\\Panel',
            'showPanel');
        $eventManager->registerEventHandler('main', 'OnCheckListGet', $this->MODULE_ID,
            '\\Prominado\\Bootstrap\\CheckList', 'onCheckListGet');
        $eventManager->registerEventHandler('iblock', 'OnIBlockPropertyBuildList', $this->MODULE_ID,
            '\\Prominado\\Bootstrap\\ListProperty', 'GetUserTypeDescription');

        return true;
    }

    public function UnInstallDB()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->unRegisterEventHandler('main', 'OnBeforeProlog', $this->MODULE_ID,
            '\\Prominado\\Bootstrap\\Panel', 'showPanel');
        $eventManager->unRegisterEventHandler('main', 'OnCheckListGet', $this->MODULE_ID,
            '\\Prominado\\Bootstrap\\CheckList', 'onCheckListGet');
        $eventManager->unRegisterEventHandler('iblock', 'OnIBlockPropertyBuildList', $this->MODULE_ID,
            '\\Prominado\\Bootstrap\\ListProperty', 'GetUserTypeDescription');
        ModuleManager::unRegisterModule($this->MODULE_ID);

        return true;
    }

    public function DoInstall()
    {
        global $APPLICATION;
        $this->InstallDB();

        $APPLICATION->IncludeAdminFile(Loc::getMessage('PROMINADO_BOOTSTRAP_INSTALL_TITLE'),
            Application::getDocumentRoot() . '/bitrix/modules/' . $this->MODULE_ID . '/install/step.php');
    }

    public function DoUninstall()
    {
        global $APPLICATION;

        $this->UnInstallDB();
        $APPLICATION->IncludeAdminFile(Loc::getMessage('PROMINADO_BOOTSTRAP_UNINSTALL_TITLE'),
            Application::getDocumentRoot() . '/bitrix/modules/' . $this->MODULE_ID . '/install/unstep.php');
    }
}

?>