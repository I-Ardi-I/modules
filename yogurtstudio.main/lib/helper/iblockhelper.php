<?php

namespace YogurtStudio\Main\Helper;

use Bitrix\Iblock\Iblock;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\ORM\CommonElementTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

class IblockHelper
{
	private const DEFAULT_CACHE_TIME = 30 * 60;

	/**
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 * @throws ArgumentException
	 */
	public static function getCodeById(int $id)
	: string {
		$iblock = IblockTable::getList([
			'filter' => ['ID' => $id],
			'cache'  => ['ttl' => self::DEFAULT_CACHE_TIME],
		])->fetchObject();

		return $iblock->getCode();
	}

	public static function getIdByCode(string $code)
	: int {
		$iblock = IblockTable::getList([
			'filter' => ['CODE' => $code],
			'cache'  => ['ttl' => self::DEFAULT_CACHE_TIME],
		])->fetchObject();

		return (int)$iblock->getId();
	}

	/**
	 * @param $id
	 *
	 * @return CommonElementTable|string
	 */
	public static function getDataClassById($id)
	{
		return Iblock::wakeUp($id)->getEntityDataClass();
	}

	/**
	 * @param $code
	 *
	 * @return CommonElementTable|string
	 */
	public static function getDataClassByCode($code)
	{
		return Iblock::wakeUp(self::getIdByCode($code))->getEntityDataClass();
	}
}