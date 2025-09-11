<?php

namespace YogurtStudio\Main\Helper;

class CountHelper
{
	public static function sendForm(array $params)
	: array {
		$sort  = "sort";
		$order = "asc";

		$iblockId  = 5;
		$sectionId = $params['sectionId'];
		$arrFilter = array_merge($params["data"], [
			">=CATALOG_PRICE_1" => $params['priceMin'],
			"<=CATALOG_PRICE_1" => $params['priceMax'],
		]);

		$elementsGetList = \CIBlockElement::GetList(
			[$sort => $order],
			array_merge(
				[
					"IBLOCK_ID"  => $iblockId,
					"SECTION_ID" => $sectionId
				],
				$arrFilter
			),
			false,
			false,
			["ID"]
		);

		$elementsCount = $elementsGetList->SelectedRowsCount();

		if (!empty($elementsCount)) {
			$countText = CommonHelper::declensionOfNumerals($elementsCount, [
				"товар",
				"товара",
				"товаров"
			]);

			$result = [
				'status' => 200,
				'data'   => [
					"quantityText" => $elementsCount . " " . $countText,
				]
			];
		} else {
			$result = [
				'status' => 400,
				'data'   => [
					"message" => "нет количества"
				]
			];
		}

		return $result;
	}
}