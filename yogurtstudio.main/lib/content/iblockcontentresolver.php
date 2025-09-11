<?php

namespace YogurtStudio\Main\Content;

class IblockContentResolver
{
	private string $elementCode;

	public function __construct(string $elementCode)
	{
		$this->elementCode = $elementCode;
	}

	/**
	 * Получает весь контент инфоблока по коду элемента.
	 *
	 * @return array Ассоциативный массив всех свойств элемента.
	 * @throws \Exception Если модуль iblock не подключен.
	 */
	public function getContent()
	: array
	{
		if (!\CModule::IncludeModule("iblock")) {
			throw new \Exception('Модуль iblock не найден');
		}

		$arSelect = [
			'ID',
			'CODE',
			'IBLOCK_ID',
			'PROPERTY_VALUE'
		];

		$content = [];

		$elements = \CIBlockElement::GetList([], ['CODE' => $this->elementCode], false, [], $arSelect);
		while ($el = $elements->GetNextElement()) {
			$fields                   = $el->GetFields();
			$content[$fields['CODE']] = $el->GetProperties();
		}

		return $content;
	}

	/**
	 * Получает блок контента по имени.
	 *
	 * @param string $blockName Название блока (CODE элемента).
	 *
	 * @return array Массив свойств блока.
	 * @throws \Exception
	 */
	public function getContentBlock(string $blockName)
	: array {
		return $this->getContent()[$blockName] ?? [];
	}

	/**
	 * Получает значение поля блока.
	 *
	 * @param string $blockName Название блока.
	 * @param string $fieldName Название поля (свойства).
	 * @param bool   $isText    Возвращать текстовое значение (если поле с типом HTML/текст).
	 *
	 * @return string Значение поля.
	 * @throws \Exception
	 */
	public function getValue(string $blockName, string $fieldName, bool $isText = false)
	: string {
		$field = $this->getField($blockName, $fieldName);

		if ($isText && is_array($field['VALUE'])) {
			return $field['~VALUE']['TEXT'] ?? '';
		}

		return $field['VALUE'] ?? '';
	}

	/**
	 * Получает отформатированную ссылку (телефон или email).
	 *
	 * @param string $blockName Название блока.
	 * @param string $fieldName Название поля.
	 * @param bool   $isMail    Является ли поле email (true) или телефоном (false).
	 *
	 * @return string Отформатированная строка ссылки.
	 * @throws \Exception
	 */
	public function getHrefFormatted(string $blockName, string $fieldName, bool $isMail = false)
	: string {
		$value = $this->getValue($blockName, $fieldName);

		return $isMail ? \YogurtStudio\Main\Helper\HrefFormatted::getMailHrefFormatted($value) : \YogurtStudio\Main\Helper\HrefFormatted::getPhoneHrefFormatted($value);
	}

	/**
	 * Получает список путей к изображениям (массив ID в свойстве).
	 *
	 * @param string $blockName Название блока.
	 * @param string $fieldName Название поля.
	 *
	 * @return array Список путей к файлам.
	 * @throws \Exception
	 */
	public function getList(string $blockName, string $fieldName)
	: array {
		$field = $this->getField($blockName, $fieldName);

		$ids   = (array)($field['VALUE'] ?? []);
		$paths = [];

		foreach ($ids as $id) {
			$paths[] = $this->resolveFilePath((int)$id) ?: \Aprel\Paths::getImg("icon-no-photo.svg");
		}

		return $paths;
	}

	/**
	 * Получает путь к изображению, если он есть.
	 *
	 * @param string $blockName Название блока.
	 * @param string $fieldName Название поля.
	 *
	 * @return string Путь к изображению или путь к иконке "нет фото".
	 * @throws \Exception
	 */
	public function getPicturePath(string $blockName, string $fieldName)
	: string {
		$id = (int)($this->getField($blockName, $fieldName)['VALUE'] ?? 0);

		return $this->resolveFilePath($id) ?: \Aprel\Paths::getImg("icon-no-photo.svg");
	}

	/**
	 * Получает путь к файлу.
	 *
	 * @param string $blockName Название блока.
	 * @param string $fieldName Название поля.
	 *
	 * @return string Путь к файлу или пустая строка.
	 * @throws \Exception
	 */
	public function getFilePath(string $blockName, string $fieldName)
	: string {
		$id = (int)($this->getField($blockName, $fieldName)['VALUE'] ?? 0);

		return $this->resolveFilePath($id);
	}

	/**
	 * Получает массив данных конкретного поля.
	 *
	 * @param string $blockName Название блока.
	 * @param string $fieldName Название свойства.
	 *
	 * @return array Массив данных поля.
	 * @throws \Exception
	 */
	private function getField(string $blockName, string $fieldName)
	: array {
		$block = $this->getContentBlock($blockName);

		return $block[$fieldName] ?? [];
	}

	/**
	 * Возвращает путь к файлу по его ID.
	 *
	 * @param int $id ID файла.
	 *
	 * @return string Путь к файлу или пустая строка.
	 */
	private function resolveFilePath(int $id)
	: string {
		return $id > 0 ? \CFile::GetPath($id) : '';
	}
}
