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

<<<<<<< Updated upstream
=======
	/**
	 * Универсальная функция замены или удаления блока роутинга в .htaccess
	 *
	 * @param string $htaccessPath Путь к .htaccess
	 * @param string $patternRegex Регулярка для поиска блока
	 * @param string|null $replacement Строка для замены. Если null — удаляем блок.
	 * @return bool true если файл изменён, false если нет изменений
	 */
	function updateHtaccessBlock(string $htaccessPath, string $patternRegex, ?string $replacement = null): bool
	{
		if (!file_exists($htaccessPath)) {
			return false;
		}

		$content = file_get_contents($htaccessPath);

		$newContent = preg_replace($patternRegex, $replacement ?? '', $content, 1);

		if ($newContent === null) {
			return false;
		}

		if ($newContent !== $content) {
			file_put_contents($htaccessPath, $newContent);
			return true;
		}

		return false;
	}



	function InstallFiles(){
		CopyDirFiles(__DIR__.'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin', true);
		CopyDirFiles(__DIR__.'/settings/', $_SERVER['DOCUMENT_ROOT'].'/bitrix', true);
		CopyDirFiles(__DIR__.'/upload/', $_SERVER['DOCUMENT_ROOT'].'/upload', true);

		CopyDirFiles(__DIR__.'/routes/', $_SERVER['DOCUMENT_ROOT']. '/local/routes/', true);
		CopyDirFiles(__DIR__.'/components/', $_SERVER['DOCUMENT_ROOT'].'/local/components', true, true);

		return true;
	}

	function UnInstallFiles(){
		DeleteDirFiles(__DIR__.'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin');
		DeleteDirFiles(__DIR__.'/settings/', $_SERVER['DOCUMENT_ROOT'].'/bitrix');
		DeleteDirFiles(__DIR__.'/upload/', $_SERVER['DOCUMENT_ROOT'].'/upload');

		DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT']. '/local/routes');
		DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT']. '/local/components');

		return true;
	}


>>>>>>> Stashed changes
	public function doInstall()
	{
		ModuleManager::registerModule($this->MODULE_ID);
	}

	public function doUninstall()
	{
		ModuleManager::unRegisterModule($this->MODULE_ID);
	}
}
