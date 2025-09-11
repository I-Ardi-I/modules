<?php

namespace YogurtStudio\Main\Http;

class FilterController
{
	/**
	 * @param array|null $arFilterParams
	 * @param float|null $minPrice
	 * @param float|null $maxPrice
	 * @param int|null   $sectionId
	 * @param array|null $sort
	 *
	 * @return array
	 */
	public static function updateQuantityFilter(?array $arFilterParams, ?float $minPrice, ?float $maxPrice, ?int $sectionId, ?array $sort)
	: array {
		if (empty($sectionId)) {
			return [
				"status"  => 400,
				'damp'    => $sectionId,
				"message" => "Не указан раздел"
			];
		}

		if (empty($minPrice) && empty($maxPrice)) {
			return [
				"status"  => 400,
				"message" => "Нет фильтра цены"
			];
		}

		$arFilter = [
			"IBLOCK_ID"           => \YogurtStudio\Main\Helper\IblockHelper::getIdByCode("Catalog"),
			"SECTION_ID"          => $sectionId,
			"INCLUDE_SUBSECTIONS" => 'Y',
			">=CATALOG_PRICE_1"   => $minPrice,
			"<=CATALOG_PRICE_1"   => $maxPrice,
		];

		if ($sort["available"] === 'Y') {
			$arFilter["CATALOG_AVAILABLE"] = "Y";
		}

		if ($sort["sale"] === 'Y') {
			$arFilter ["!PROPERTY_SALE"] = false;
		}

		if ($sort["new"] === 'Y') {
			$arFilter["!PROPERTY_NEW"] = false;
		}

		$arFilterComplex = array_merge($arFilter, $arFilterParams);

		$elementsGetList = \CIBlockElement::GetList([], $arFilterComplex, false, false, ["ID"]);
		$elementsCount   = $elementsGetList->SelectedRowsCount();

		$countText = \YogurtStudio\Main\Helper\CommonHelper::declensionOfNumerals($elementsCount ?? 0, [
			"товар",
			"товара",
			"товаров"
		]);

		return [
			'status' => 200,
			'data'   => [
				"quantityText" => $elementsCount . " " . $countText,
			],
		];
	}
}