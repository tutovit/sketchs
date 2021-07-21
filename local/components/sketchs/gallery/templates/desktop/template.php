<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}


?>

<?php if (!empty($arResult['PHOTOS'])) { ?>
    <?php if (count($arResult['PHOTOS']) > 1) { ?>
        <div data-elem="gallery_<?= $arParams['ELEMENT_ID'] ?>" class="slider main-gallery">
            <div class="nav"></div>
            <div class="slider-wrapper">
                <? foreach ($arResult['PHOTOS'] as $arPhoto) { ?>
                    <div class="slide"
                         data-src="<?= $arPhoto['ORIGINAL']['src'] ?>" data-width="<?= $arPhoto['ORIGINAL']['width'] ?>" data-height="<?= $arPhoto['ORIGINAL']['height'] ?>"
                         style="background-image: url('<?= $arPhoto['PREVIEW']['src'] ?>')"></div>
                <? } ?>
            </div>
        </div>
    <? } elseif (count($arResult['PHOTOS']) > 0) { ?>
        <div data-elem="gallery_<?= $arParams['ELEMENT_ID'] ?>" class="main-gallery-one"
             data-src="<?= $arResult['PHOTOS'][0]['ORIGINAL']['src'] ?>" data-width="<?= $arResult['PHOTOS'][0]['ORIGINAL']['width'] ?>" data-height="<?= $arResult['PHOTOS'][0]['ORIGINAL']['height'] ?>"
             style="background-image: url(<?= $arResult['PHOTOS'][0]['PREVIEW']['src'] ?>)">

        </div>
    <?php } ?>
<?php } ?>
