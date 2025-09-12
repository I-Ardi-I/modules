<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class yogurtstudio_main extends \CModule
{

	const solutionName	= 'main';
	const partnerName = 'yogurtstudio';

	public function __construct()
	{
		$arModuleVersion = [];

		include __DIR__ . '/version.php';

		if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
			$this->MODULE_VERSION      = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		}

		$this->MODULE_ID           = 'yogurtstudio.main';
		$this->MODULE_NAME         = Loc::getMessage('YS_REST_MODULE_NAME');
		$this->MODULE_DESCRIPTION  = Loc::getMessage('YS_REST_MODULE_DESCRIPTION');
		$this->MODULE_GROUP_RIGHTS = 'N';
		$this->PARTNER_NAME        = Loc::getMessage('YS_REST_MODULE_PARTNER_NAME');
		$this->PARTNER_URI         = 'https://yogurtstudio.ru';
	}


	function InstallFiles(){
		CopyDirFiles(__DIR__.'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin', true);

		CopyDirFiles(__DIR__.'/routes/', $_SERVER['DOCUMENT_ROOT']. '/local/' . '/routes/', true);
		CopyDirFiles(__DIR__.'/components/', $_SERVER['DOCUMENT_ROOT'].'/local/components', true, true);

		return true;
	}

	function UnInstallFiles(){
		DeleteDirFiles(__DIR__.'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin');

		DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT']. '/local/' . '/routes/');
		DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT']. '/local/' . '/components');

		return true;
	}


	public function doInstall()
	{
		ModuleManager::registerModule($this->MODULE_ID);

		$this->InstallFiles();
	}

	public function doUninstall()
	{
		ModuleManager::unRegisterModule($this->MODULE_ID);

		$this->UnInstallFiles();
	}
}
