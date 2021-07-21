<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

class GalleryComponent extends \CBitrixComponent
{
    private $arSectionCodes = [];

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {

        if (!Loader::includeModule('iblock')) {
            return;
        }

        $this->setGallery()->includeComponentTemplate();

    }


    public function setGallery()
    {
        $this->arResult['PHOTOS'] = [];

        $rsItems = \CIBlockElement::GetList(['SORT' => 'ASC'], [
            'IBLOCK_ID' => IBLOCK_GALLERY,
            'ID' => $this->arParams['ELEMENT_ID'],
        ], false, false, [
            'IBLOCK_ID',
            'ID'
        ]);

        while ($ob = $rsItems->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arFields['PROPERTIES'] = $ob->GetProperties();

            foreach ($arFields['PROPERTIES']['PHOTO']['VALUE'] as $iFileID) {
                $arImage = [];
                $arImage['ORIGINAL'] = CFile::ResizeImageGet(
                    $iFileID,
                    Array(
                        "width" => 1920,
                        "height" => 1080
                    )
                    , BX_RESIZE_IMAGE_EXACT, true
                );
                $arImage['PREVIEW'] = CFile::ResizeImageGet(
                    $iFileID,
                    Array(
                        "width" => 640,
                        "height" => 480
                    )
                    , BX_RESIZE_IMAGE_EXACT, true
                );

                $arImages[] = $arImage;

            }
        }

        $this->arResult['PHOTOS'] = $arImages;


        return $this;
    }


}