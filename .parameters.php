<?php
/** @var array $arCurrentValues */
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule('iblock'))
    return;

$arTypesEx = CIBlockParameters::GetIBlockTypes(['-'=>' ']);

$arIBlocks=[];
$db_iblock = CIBlock::GetList(
    ['SORT'=>'ASC'],
    [
        'SITE_ID'=>$_REQUEST['site'],
        'TYPE' => ($arCurrentValues['IBLOCK_TYPE']!='-'?$arCurrentValues['IBLOCK_TYPE']:'')
    ]
);
while($arRes = $db_iblock->Fetch())
    $arIBlocks[$arRes['ID']] = $arRes['NAME'];

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => [
        'IBLOCK_TYPE' => [
            'PARENT' => 'BASE',
            'NAME' => 'Тип инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $arTypesEx,
            'DEFAULT' => 'news',
            'REFRESH' => 'Y',
        ],
        'IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Код инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'DEFAULT' => '={$_REQUEST["ID"]}',
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ],
        'CACHE_TIME'  =>  ['DEFAULT' => 86400],
    ]
];
