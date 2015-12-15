<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("DRCLINICS_DIRECTIONS_NAME"),
	"DESCRIPTION" => GetMessage("DRCLINICS_DIRECTIONS_DESCRIPTION"),
	"ICON" => "/images/news_all.gif",
	"COMPLEX" => "Y",
	"SORT" => 10,
	"PATH" => array(
		"ID" => "drclinic",
		"NAME" => GetMessage('T_DRCLINICS_SECTION_NAME'),
		"CHILD" => array(
			"ID" => "record",
			"NAME" => GetMessage("T_DRCLINICS_DESC_RECORD"),
			"SORT" => 10,
		),
	),
);
