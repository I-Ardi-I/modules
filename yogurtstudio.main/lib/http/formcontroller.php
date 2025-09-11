<?php

namespace YogurtStudio\Main\Http;

class FormController
{
	public function sendForm(int $formId, array $arFields)
	: array {
		$result = [];

		if (!\Bitrix\Main\Loader::includeModule("form")) {
			return $result;
		}

		global $strError;

		if (!empty($arFields)) {
			$result["data"] = $arFields;
			if ($resultAdd = \CFormResult::Add($formId, $arFields)) {
				if ($formResult = \CFormResult::Mail($resultAdd)) {
					$result = [
						"resultID"   => $resultAdd,
						"formID"     => $formId,
						"formResult" => $formResult,
						"status"     => 200
					];
				} else {
					$result["result"] = [
						"status"  => 400,
						"message" => $strError
					];
				}
			} else {
				$result["result"] = [
					"status"  => 400,
					"message" => $strError
				];
			}
		} else {
			$result["result"] = [
				"status"  => 400,
				"message" => 'Пустой запрос'
			];
		}

		return $result;
	}
}