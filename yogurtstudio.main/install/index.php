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

	public function doInstall()
	{
		ModuleManager::registerModule($this->MODULE_ID);
	}

	public function doUninstall()
	{
		ModuleManager::unRegisterModule($this->MODULE_ID);
	}
}
