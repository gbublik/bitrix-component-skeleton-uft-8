<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\Engine\Contract\Controllerable;
use \Bitrix\Main\Data\Cache;

class ContactsListComponent extends CBitrixComponent implements Controllerable
{
    public function onPrepareComponentParams($arParams)
    {        
        return $arParams;
    }

    public function executeComponent()
    {
        try {
            $this->checkDependency();
            
            $cache = Cache::createInstance();
            if ($cache->initCache($this->arParams['CACHE_TIME'], "IDCache")) {
                $this->arResult = $cache->getVars();
            } elseif ($cache->startDataCache()) {
                //Запросы
                $this->arResult = [];
                $cache->endDataCache($this->arResult);
            }
            
            $this->IncludeComponentTemplate();
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
        return parent::executeComponent();
    }

    protected function checkDependency()
    {
        if (!Loader::includeModule('prodvigaeff.ariston'))
        {
            throw new SystemException(Loc::getMessage('PD_ARISTON_MODULE_NOT_INSTALL'));
        }
        return true;
    } 
    
    public function configureActions()
    {
        return [
            'test' => [
                'prefilters' => [],
                'postfilters' => []
            ]
        ];
    }
    
    public function testAction(int $id)
    {
        $this->checkDependency();
    }
}
