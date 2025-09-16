<?php

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

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

		CopyDirFiles(__DIR__.'/routes/', $_SERVER['DOCUMENT_ROOT']. '/local/routes/', true);
		CopyDirFiles(__DIR__.'/components/', $_SERVER['DOCUMENT_ROOT'].'/local/components', true, true);

		return true;
	}

	function UnInstallFiles(){
		DeleteDirFiles(__DIR__.'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin');
		DeleteDirFiles(__DIR__.'/settings/', $_SERVER['DOCUMENT_ROOT'].'/bitrix');

		DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT']. '/local/routes');
		DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT']. '/local/components');

		return true;
	}


	public function doInstall()
	{
		ModuleManager::registerModule($this->MODULE_ID);

		$this->InstallFiles();

		$htaccessPath = $_SERVER['DOCUMENT_ROOT'].'/.htaccess';

		$patternInstall = '/RewriteCond\s+\%\{REQUEST_FILENAME\}\s+!\/bitrix\/urlrewrite\.php\$\s*RewriteRule\s+\^\(\.\*\)\$\s+\/bitrix\/urlrewrite\.php\s+\[L\]/m';

		$replacementInstall = "RewriteCond %{REQUEST_FILENAME} !/bitrix/routing_index.php$\nRewriteRule ^(.*)$ /bitrix/routing_index.php [L]";

		$this->updateHtaccessBlock($htaccessPath, $patternInstall, $replacementInstall);


	}

	public function doUninstall()
	{

		$this->UnInstallFiles();

		$htaccessPath = $_SERVER['DOCUMENT_ROOT'].'/.htaccess';

		$patternUninstall = '/RewriteCond\s+\%\{REQUEST_FILENAME\}\s+!\/bitrix\/routing_index\.php\$\s*RewriteRule\s+\^\(\.\*\)\$\s+\/bitrix\/routing_index\.php\s+\[L\]/m';

		$replacementUninstall = "RewriteCond %{REQUEST_FILENAME} !/bitrix/urlrewrite.php$\nRewriteRule ^(.*)$ /bitrix/urlrewrite.php [L]";

		$this->updateHtaccessBlock($htaccessPath, $patternUninstall, $replacementUninstall);



		ModuleManager::unRegisterModule($this->MODULE_ID);

	}
}
