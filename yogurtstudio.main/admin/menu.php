<?

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader;

AddEventHandler('main', 'OnBuildGlobalMenu', 'OnBuildGlobalMenuHandlerMax');
function OnBuildGlobalMenuHandlerMax(&$arGlobalMenu, &$arModuleMenu)
{
	if (!defined('ASPRO_MAX_MENU_INCLUDED')) {
		define('ASPRO_MAX_MENU_INCLUDED', true);

		IncludeModuleLangFile(__FILE__);
		$moduleID = 'yogurtstudio.main';

		$GLOBALS['APPLICATION']->SetAdditionalCss("/local/modules/" . $moduleID . "/css/menu.css");

		if ($GLOBALS['APPLICATION']->GetGroupRight($moduleID) >= 'R') {
			$arMenu = [
				'menu_id'  => 'global_menu_yogurtstudio_main',
				'text'     => Loc::getMessage('YS_MENU'),
				'title'    => Loc::getMessage('YS_MENU'),
				'sort'     => 1000,
				'items_id' => 'global_menu_yogurtstudio_main_items',
				'icon'     => 'imi_max',
				'items'    => [
					[
						'text'      => Loc::getMessage('YS_SUB_MENU_ONE'),
						'title'     => Loc::getMessage('YS_SUB_MENU_ONE'),
						'sort'      => 10,
						'url'       => '/bitrix/admin/'. $moduleID . '_mc.php?lang=' . urlencode(LANGUAGE_ID),
						'icon'      => 'imi_control_center',
						'page_icon' => 'pi_control_center',
						'items_id'  => 'control_center',
					],
					[
						'text'      => Loc::getMessage('YS_SUB_MENU_TWO'),
						'title'     => Loc::getMessage('YS_SUB_MENU_TWO'),
						'sort'      => 20,
						'url'       => '/bitrix/admin/'. $moduleID . '_options.php?mid=main&lang=' . urlencode(LANGUAGE_ID),
						'icon'      => 'imi_typography',
						'page_icon' => 'pi_typography',
						'items_id'  => 'main',
					],
				],
			];

			if (!\CModule::IncludeModule('aspro.smartseo')) {
				$arMenu['items'][] = [
					'text'  => Loc::getMessage('YS_SUB_MENU_THREE'),
					'title' => Loc::getMessage('YS_SUB_MENU_THREE'),
					'sort'  => 1000,
					'url'   => '/bitrix/admin/'. $moduleID . '_smartseo_load.php?lang=' . urlencode(LANGUAGE_ID),
					'icon'  => 'imi_smartseo',
				];
			}

			if (!isset($arGlobalMenu['global_menu_ys'])) {
				$arGlobalMenu['global_menu_ys'] = [
					'menu_id'  => 'global_menu_ys',
					'text'     => Loc::getMessage('YS_MENU_GLOBAL'),
					'title'    => Loc::getMessage('YS_MENU_GLOBAL'),
					'sort'     => 1000,
					'items_id' => 'global_menu_ys_items',
					'icon'  => 'ys_global_icon',
				];
			}

			$arGlobalMenu['global_menu_ys']['items'][$moduleID] = $arMenu;
		}
	}
}

?>