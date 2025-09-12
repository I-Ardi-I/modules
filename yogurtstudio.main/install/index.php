<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class yogurtstudio_main extends \CModule
{
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
		CopyDirFiles(__DIR__.'/admin/', $_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/admin/'.static::partnerName.'/'.static::solutionName, true);
		CopyDirFiles(__DIR__.'/components/', $_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/components', true, true);
		CopyDirFiles(__DIR__.'/tools/', $_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/tools/'.static::partnerName.'/'.static::solutionName, true, true);

		CopyDirFiles(__DIR__.'/css/', $_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/css/'.static::partnerName.'/'.static::solutionName, true, true);
		CopyDirFiles(__DIR__.'/js/', $_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/js/'.static::partnerName.'/'.static::solutionName, true, true);
		CopyDirFiles(__DIR__.'/images/', $_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/images/'.static::partnerName.'/'.static::solutionName, true, true);

		return true;
	}

	function UnInstallFiles(){
		DeleteDirFilesEx(BX_ROOT.'/admin/'.static::partnerName.'/'.static::solutionName.'/');
		DeleteDirFilesEx(BX_ROOT.'/css/'.static::partnerName.'/'.static::solutionName.'/');
		DeleteDirFilesEx(BX_ROOT.'/js/'.static::partnerName.'/'.static::solutionName.'/');
		DeleteDirFilesEx(BX_ROOT.'/images/'.static::partnerName.'/'.static::solutionName.'/');
		DeleteDirFilesEx(BX_ROOT.'/tools/'.static::partnerName.'/'.static::solutionName.'/');

		$this->UnInstallComponents();

		return true;
	}

	function UnInstallComponents() {
		DeleteDirFilesEx(BX_ROOT.'/components/'.static::partnerName.'/marketing.popup/');

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
