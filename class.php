<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;
use Prodvigaeff\Bilet\Core\EventsTable;
use Prodvigaeff\Bilet\Core\StageTable;
use Bitrix\Main\Engine\Contract\Controllerable;

class PublicViteComponent extends CBitrixComponent  implements Controllerable
{
    /** @var \Bitrix\Main\HttpResponse  */
    protected $response;

    /**
     * PublicViteComponent constructor.
     * @param null $component
     * @throws SystemException
     */
    public function __construct($component = null)
    {
        $this->response = Application::getInstance()->getContext()->getResponse();
        parent::__construct($component);
    }

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        if ($this->startResultCache()) {
            try {
                $this->checkDependency();
                $this->arResultCacheKeys = [];
                $this->IncludeComponentTemplate();
            } catch (Exception $e) {
                $this->response->setStatus('404 Not Found');
                ShowError($e->getMessage());
                $this->abortResultCache();
            }
        }
        if ($this->arResult['stage']) {
            $GLOBALS['APPLICATION']->SetTitle('Загаловок');
            $GLOBALS['APPLICATION']->AddChainItem('Загаловок');
        }
        return parent::executeComponent();
    }

    /**
     * @return bool
     * @throws SystemException
     * @throws LoaderException
     */
    protected function checkDependency()
    {
        if (empty($this->arParams['STAGE_ID'])) {
            throw new SystemException('Не установлен id зала');
        }
        if (!Loader::includeModule('prodvigaeff.bilet')) {
            throw new SystemException('Module prodvigaeff.bilet not installed');
        }
        return true;
    }
    
    protected function listKeysSignedParameters()
    {
       return [
              'IBLOCK_ID'
         ];
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
