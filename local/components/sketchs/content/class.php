<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

class ContentComponent extends \CBitrixComponent
{
    private $arSectionCodes = [];
    private $sGalleryTemplate = 'desktop';

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        global $APPLICATION,
               $USER;

        if (!Loader::includeModule('iblock')) {
            return;
        }

        $arResult['IS_ADMIN'] = $USER->IsAdmin();

        if ($this->arParams['AJAX'] === 'Y') {
            if ($_REQUEST['query'] || $_REQUEST['code'] == 'search') {
                $this->getAjaxSearchJson();
            } else {
                $this->sGalleryTemplate = 'modal';
                $this->getAjaxJson();
            }
        } else {
            $this->getSections()
                ->setItems()
                ->includeComponentTemplate();
        }

    }

    public function getSections()
    {
        $rsSections = \CIBlockSection::GetList([], [
            'IBLOCK_ID' => IBLOCK_CONTENT,
            'ACTIVE' => 'Y'
        ], false, [
            'IBLOCK_ID',
            'ID',
            'NAME',
            'DESCRIPTION',
            'CODE',
            'UF_*'
        ]);


        while ($arSection = $rsSections->Fetch()) {
            $this->arSectionCodes[$arSection['ID']] = $arSection['CODE'];
            $this->arResult['SECTIONS'][$arSection['CODE']] = [
                'id' => $arSection['ID'],
                'title' => \Clinic\Text::typograf($arSection['NAME']),
                'menu' => \Clinic\Text::typograf($arSection['UF_MENU_TITLE_DESKTOP']),
                'text' => $this->ParseGallery(\Clinic\Text::typograf($arSection['DESCRIPTION'])),
                'code' => $arSection['CODE'],
                'items' => [],
            ];
        }


        return $this;
    }

    public function setItems()
    {
        if (!empty($this->arResult['SECTIONS'])) {
            $rsItems = \CIBlockElement::GetList(['SORT' => 'ASC'], [
                'IBLOCK_ID' => IBLOCK_CONTENT,
                'IBLOCK_SECTION_ID' => array_column($this->arResult['SECTIONS'], 'id'),
                'ACTIVE' => 'Y'
            ], false, false, [
                'IBLOCK_ID',
                'IBLOCK_SECTION_ID',
                'ID',
                'NAME',
                'CODE',
                'PREVIEW_TEXT',
                'DETAIL_PICTURE',
                'PROPERTY_TITLE',
                'PROPERTY_PRICE',
                'PROPERTY_ICON'
            ]);

            while ($arItem = $rsItems->Fetch()) {
                $arImage = CFile::ResizeImageGet(
                    $arItem['DETAIL_PICTURE'],
                    array(
                        "width" => 400,
                        "height" => 400
                    )
                    , BX_RESIZE_IMAGE_EXACT, true
                );


                $this->arResult['SECTIONS'][$this->arSectionCodes[$arItem['IBLOCK_SECTION_ID']]]['items'][] = [
                    'title' => \Clinic\Text::typograf($arItem['NAME']),
                    'id' => $arItem['ID'],
                    'code' => $arItem['CODE'],
                    'text' => $this->ParseGallery(\Clinic\Text::typograf($arItem['PREVIEW_TEXT'])),
                    'subtitle' => \Clinic\Text::typograf($arItem['PROPERTY_TITLE_VALUE']),
                    'icon_code' => $arItem['PROPERTY_ICON_VALUE'],
                    'price' => $arItem['PROPERTY_PRICE_VALUE'],
                    'icon' => $arImage['src'],
                ];
            }

        }

        return $this;
    }

    public function ParseGallery($sTextContent)
    {
        $sTextContent = preg_replace_callback(
            "/#GALLERY_([\d]+)#/is" . BX_UTF_PCRE_MODIFIER,
            function ($matches) {
                global $APPLICATION;
                ob_start();
                $APPLICATION->IncludeComponent(
                    "clinic:gallery",
                    $this->sGalleryTemplate,
                    array(
                        "ELEMENT_ID" => $matches[1],
                    ),
                    false
                );
                $retrunStr = ob_get_clean();
                return $retrunStr;
            },
            $sTextContent);

        return $sTextContent;
    }

    public function getAjaxJson()
    {
        $arJson = [];
        $code = $_GET['code'];

        $rsItems = \CIBlockElement::GetList(['SORT' => 'ASC'], [
            'IBLOCK_ID' => IBLOCK_CONTENT,
            'CODE' => $code,
            'ACTIVE' => 'Y'
        ], false, false, [
            'IBLOCK_ID',
            'IBLOCK_SECTION_ID',
            'ID',
            'NAME',
            'CODE',
            'PREVIEW_TEXT',
            'PREVIEW_PICTURE',
            'PROPERTY_TITLE',
            'PROPERTY_DESC',
        ]);

        if ($arItem = $rsItems->Fetch()) {

            $arImage = CFile::ResizeImageGet(
                $arItem['PREVIEW_PICTURE'],
                array(
                    "width" => 768,
                    "height" => 768
                )
                , BX_RESIZE_IMAGE_EXACT, true
            );

            $rsMoreItems = \CIBlockElement::GetList(['SORT' => 'ASC'], [
                'IBLOCK_ID' => IBLOCK_CONTENT,
                'IBLOCK_SECTION_ID' => $arItem['IBLOCK_SECTION_ID'],
                'ACTIVE' => 'Y'
            ], false, false, [
                'IBLOCK_ID',
                'IBLOCK_SECTION_ID',
                'ID',
                'NAME',
                'CODE',
                'PREVIEW_TEXT',
                'PREVIEW_PICTURE',
                'PROPERTY_TITLE',
                'PROPERTY_DESC',
            ]);

            $arSection = \Bitrix\Iblock\SectionTable::getById($arItem['IBLOCK_SECTION_ID'])->fetch();
            $arMoreItems = [];
            if ($rsMoreItems->SelectedRowsCount() > 1) {
                $arMarkers = [];

                if ($arSection['CODE'] == 'pervaya-sistemnaya-stomatologiya') {
                    $arMarkers = explode(' ', 'I II III IV V VI VII VIII IX X');
                }
                $iCounter = 0;
                while ($arMoreItem = $rsMoreItems->Fetch()) {
                    $arMoreItem['NAME'] = preg_replace('/<[^>]*>/', ' ', $arMoreItem['NAME']);
                    $arMoreItem['NAME'] = str_replace(['  ', '- '], [' ', '-'], $arMoreItem['NAME']);
                    $arMoreItems[] = '<li data-elem="item_' . $arMoreItem['ID'] . '"><span class="marker">' . $arMarkers[$iCounter++] . '</span><a href="#' . $arMoreItem['CODE'] . '" class="js-detail-open"><span class="txt">' . \Clinic\Text::typograf($arMoreItem['NAME']) . '</span></a></li>';
                }
            }

            $arSection['NAME'] = preg_replace('/<[^>]*>/', ' ', $arSection['NAME']);
            $arSection['NAME'] = str_replace(['  ', '- '], [' ', '-'], $arSection['NAME']);

            $arJson = [
                'sectCode' => $arSection['CODE'],
                'id' => $arItem['ID'],
                'title' => $arItem['PROPERTY_TITLE_VALUE'] ? \Clinic\Text::typograf($arItem['PROPERTY_TITLE_VALUE']) : \Clinic\Text::typograf($arItem['NAME']),
                'subTitle' => \Clinic\Text::typograf($arItem['PROPERTY_DESC_VALUE']),
                'sectName' => \Clinic\Text::typograf($arSection['NAME']),
                'bgUrl' => $arImage['src'],
                'content' => $this->ParseGallery(\Clinic\Text::typograf($arItem['PREVIEW_TEXT']))
            ];

            if ($arItem['IBLOCK_SECTION_ID'] == 1) {
                $arJson['sectList'] = '<ul class="numbers">' . implode('', $arMoreItems) . '</ul>';
            } else {
                $arJson['sectList'] = '<ul>' . implode('', $arMoreItems) . '</ul>';
            }


        }

        echo json_encode($arJson);
        die;

    }


    public function getAjaxSearchJson()
    {
        $sQuery = $this->request->get('query');

        $arSearchItems = [];
        if (\CModule::IncludeModule('search')) {
            $filter['QUERY'] = $sQuery;
            $obSearch = new CSearch;
            $obSearch->SetOptions(array(//мы добавили еще этот параметр, чтобы не ругался на форматирование запроса
                'ERROR_ON_EMPTY_STEM' => false,
            ));

            $arSort = [
                'CUSTOM_RANK' => 'DESC',
                'TITLE_RANK' => 'DESC',
                'RANK' => 'DESC',
                'DATE_CHANGE' => 'DESC'
            ];

            $obSearch->Search(array(
                'QUERY' => $filter['QUERY'],
                'SITE_ID' => SITE_ID,
                'MODULE_ID' => 'iblock',
                'PARAM1' => 'content',
                'PARAM2' => '1'
            ), $arSort);
            if (!$obSearch->selectedRowsCount()) {//и делаем резапрос, если не найдено с морфологией...
                $obSearch->Search(array(
                    'QUERY' => $filter['QUERY'],
                    'SITE_ID' => SITE_ID,
                    'MODULE_ID' => 'iblock',
                    'PARAM1' => 'content',
                    'PARAM2' => '1'
                ), $arSort, array('STEMMING' => false));//... уже с отключенной морфологией
            }

            $arSearchItems = $arSearchSections = [];

            if (!$obSearch->errorno) {
                while ($arSearchItem = $obSearch->fetch()) {
                    $iStrPosS = strpos($arSearchItem['ITEM_ID'], 'S');
                    if ($iStrPosS !== false) {
                        $id = substr($arSearchItem['ITEM_ID'], $iStrPosS + 1);
                        $arSearchSections[$id] = $arSearchItem;
                    } else {
                        $arSearchItems[$arSearchItem['ITEM_ID']] = $arSearchItem;
                    }
                }



                $rsSearchSections = \Bitrix\Iblock\SectionTable::getList([
                    'filter' => [
                        'IBLOCK_ID' => IBLOCK_CONTENT,
                        'ID' => array_keys($arSearchSections)
                    ],
                    'select' => [
                        'ID',
                        'CODE'
                    ]
                ]);

                while ($arSearchItem = $rsSearchSections->fetch()) {

                    $arSearchItems[$arSearchItem['ID']]['TITLE'] = preg_replace('/<[^>]*>/', ' ', $arSearchItems[$arSearchItem['ID']]['TITLE']);
                    $arSearchItems[$arSearchItem['ID']]['TITLE'] = str_replace(['  ', '- '], [' ', '-'], $arSearchItems[$arSearchItem['ID']]['TITLE']);

                    $arSearchItemsResult[] = '<li><a href="#' . $arSearchItem['CODE'] . '" class="js-section-open text-bold text-size-xl text-center">' . $arSearchSections[$arSearchItem['ID']]['TITLE'] . '</a>
                <div class="text">' . $this->ParseGallery(\Clinic\Text::typograf($arSearchSections[$arSearchItem['ID']]['BODY_FORMATED'])) . '</div>
                </li>';
                }

                $rsSearchItems = \Bitrix\Iblock\ElementTable::getList([
                    'filter' => [
                        'IBLOCK_ID' => IBLOCK_CONTENT,
                        'ID' => array_keys($arSearchItems)
                    ],
                    'select' => [
                        'ID',
                        'CODE'
                    ]
                ]);

                while ($arSearchItem = $rsSearchItems->fetch()) {

                    $arSearchItems[$arSearchItem['ID']]['TITLE'] = preg_replace('/<[^>]*>/', ' ', $arSearchItems[$arSearchItem['ID']]['TITLE']);
                    $arSearchItems[$arSearchItem['ID']]['TITLE'] = str_replace(['  ', '- '], [' ', '-'], $arSearchItems[$arSearchItem['ID']]['TITLE']);

                    $arSearchItemsResult[] = '<li><a href="#' . $arSearchItem['CODE'] . '" class="js-detail-open text-bold text-size-xl text-center">' . $arSearchItems[$arSearchItem['ID']]['TITLE'] . '</a>
                <div class="text">' . $this->ParseGallery(\Clinic\Text::typograf($arSearchItems[$arSearchItem['ID']]['BODY_FORMATED'])) . '</div>
                </li>';
                }

                //'<li><a href="#' . $arSearchItem['ITEM_ID'] . '">' . $arSearchItem['TITLE'] . '</a>
                //<div class="text">' . $arSearchItem['BODY_FORMATED'] . '</div></li>';
            }
        }

        $form = <<<FORM
<div id="search">
                <div method="POST">
                    <div class="text-bold text-size-xl">Поиск по сайту</div>
                    <div class="inp-wrap" style="margin-top: 30px;">
                        <input type="text" class="inp field" value="{$sQuery}" name="query" id="search-inp" autocomplete="off" placeholder="Введите запрос">

                    </div>

                    <div class="df mt48">
                        <button type="submit" class="btn mr24 js-send-search">Найти</button>
                    </div>
                </div>
            </div>
FORM;


        $arJson = [
            'content' => $form .  '<ul>' . ((empty($arSearchItemsResult) && $sQuery) ? '<li>Ничего не найдено</li>' : implode('', $arSearchItemsResult)) . '</ul>',
        ];

        echo json_encode($arJson);
        die;
    }

}