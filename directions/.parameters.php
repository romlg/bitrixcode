<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
$arComponentParameters = array(
	"GROUPS"     => array(
		'FORM_SETTINGS' => array(
			'NAME' => GetMessage('FORM_SETTINGS')
		)
	),
	"PARAMETERS" => array(
        "AJAX_MODE" => array(),
        "IBLOCK_ID" => array(
            "NAME"  => GetMessage("IBLOCK_ID"),
            "TYPE" => "STRING",
            "DEFAULT" =>"20"
        ),
        "SECTION_ID" => array(
            "NAME"  => GetMessage("IBLOCK_SECTION_ID"),
            "TYPE" => "STRING",
            "DEFAULT" =>"137"
        ),
	),
);
