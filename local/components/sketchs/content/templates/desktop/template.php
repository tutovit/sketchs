<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<?php if (!empty($arResult['SECTIONS']['pervaya-sistemnaya-stomatologiya'])) { ?>

    <section class="section main-bn">
        <div class="inner" id="about">
            <div id="pervaya-sistemnaya-stomatologiya_SECTION" data-elem="section_<?= $arResult['SECTIONS']['pervaya-sistemnaya-stomatologiya']['id'] ?>" class="section__title"><?= $arResult['SECTIONS']['pervaya-sistemnaya-stomatologiya']['title'] ?></div>
            <div data-elem="section_<?= $arResult['SECTIONS']['pervaya-sistemnaya-stomatologiya']['id'] ?>" class="section__text">
                <?= $arResult['SECTIONS']['pervaya-sistemnaya-stomatologiya']['text'] ?>
            </div>

            <? if ($arResult['SECTIONS']['pervaya-sistemnaya-stomatologiya']['items']) { ?>
                <ul class="num">
                    <?
                    $arMarkers = explode(' ', 'I II III IV V VI VII VIII IX X');
                    ?>
                    <? foreach ($arResult['SECTIONS']['pervaya-sistemnaya-stomatologiya']['items'] as $iNum => $arItem) { ?>
                        <li data-elem="item_<?= $arItem['id'] ?>">
                            <span class="marker"><?= $arMarkers[$iNum] ?></span>
                            <a href="#<?= $arItem['code'] ?>" data-elem-modal="item_<?= $arItem['id'] ?>" class="js-detail-open"><?= $arItem['title'] ?></a>
                        </li>
                    <? } ?>
                </ul>
            <? } ?>

            <div class="main-bn__img"></div>
        </div>
    </section>
<?php } ?>


<?php if (!empty($arResult['SECTIONS']['glavnoe'])) { ?>
    <section class="section principal">
        <div class="inner" id="principal">
            <div id="glavnoe_SECTION" data-elem="section_<?= $arResult['SECTIONS']['glavnoe']['id'] ?>" class="section__title text-center"><?= $arResult['SECTIONS']['glavnoe']['title'] ?></div>

            <div class="principal-img"></div>
            <? if ($arResult['SECTIONS']['glavnoe']['items']) { ?>
                <ul>
                    <? foreach ($arResult['SECTIONS']['glavnoe']['items'] as $iNum => $arItem) { ?>
                        <li data-elem="item_<?= $arItem['id'] ?>">
                            <span class="marker"></span>
                            <a href="#<?= $arItem['code'] ?>" data-elem-modal="item_<?= $arItem['id'] ?>" class="js-detail-open"><?= $arItem['title'] ?></a>
                        </li>
                    <? } ?>
                </ul>
            <? } ?>
        </div>
    </section>
<?php } ?>


<?php if (!empty($arResult['SECTIONS']['u-nas-sterilno-i-bezopasno'])) { ?>
    <section class="section safety">
        <div class="inner" id="safety">
            <div id="u-nas-sterilno-i-bezopasno_SECTION" data-elem="section_<?= $arResult['SECTIONS']['u-nas-sterilno-i-bezopasno']['id'] ?>" class="section__title"><?= $arResult['SECTIONS']['u-nas-sterilno-i-bezopasno']['title'] ?></div>
            <div data-elem="section_<?= $arResult['SECTIONS']['u-nas-sterilno-i-bezopasno']['id'] ?>" class="section__text">
                <?= $arResult['SECTIONS']['u-nas-sterilno-i-bezopasno']['text'] ?>
            </div>
            <div class="safety-img"></div>
            <? if ($arResult['SECTIONS']['u-nas-sterilno-i-bezopasno']['items']) { ?>
                <ul>
                    <? foreach ($arResult['SECTIONS']['u-nas-sterilno-i-bezopasno']['items'] as $iNum => $arItem) { ?>
                        <li data-elem="item_<?= $arItem['id'] ?>">
                            <span class="marker"></span>
                            <a href="#<?= $arItem['code'] ?>" data-elem-modal="item_<?= $arItem['id'] ?>" class="js-detail-open"><?= $arItem['title'] ?></a>
                        </li>
                    <? } ?>
                </ul>
            <? } ?>
        </div>
    </section>
<?php } ?>

<?php if (!empty($arResult['SECTIONS']['fotogalereya'])) { ?>
    <section id="fotogalereya_SECTION" data-elem="section_<?= $arResult['SECTIONS']['fotogalereya']['id'] ?>" class="section main-slider">
        <div class="inner" id="gallery">
            <?= $arResult['SECTIONS']['fotogalereya']['text'] ?>
        </div>
    </section>
<?php } ?>

<?php if (!empty($arResult['SECTIONS']['oborudovanie-i-tekhnologii'])) { ?>
    <section id="oborudovanie-i-tekhnologii_SECTION" class="section equipment">
        <div class="inner" id="equipment">
            <div data-elem="section_<?= $arResult['SECTIONS']['oborudovanie-i-tekhnologii']['id'] ?>" class="section__title text-center"><?= $arResult['SECTIONS']['oborudovanie-i-tekhnologii']['title'] ?></div>
            <div data-elem="section_<?= $arResult['SECTIONS']['oborudovanie-i-tekhnologii']['id'] ?>" class="section__text text-center mt40">
                <?= $arResult['SECTIONS']['oborudovanie-i-tekhnologii']['text'] ?>
            </div>

            <div class="equipment__list">
                <? foreach ($arResult['SECTIONS']['oborudovanie-i-tekhnologii']['items'] as $iNum => $arItem) { ?>
                    <div data-elem="item_<?= $arItem['id'] ?>" class="equipment__list-item">
                        <a href="#<?= $arItem['code'] ?>" data-elem-modal="item_<?= $arItem['id'] ?>" class="js-detail-open"><div class="image"
                             style="background-repeat: no-repeat;background-position: center 0;background-image: url(<?= $arItem['icon'] ?>)"></div>
                        <span><?= $arItem['title'] ?></span></a>
                    </div>
                <? } ?>
            </div>
        </div>
    </section>
<?php } ?>


<?php if (!empty($arResult['SECTIONS']['komfort-dlya-patsientov'])) { ?>

    <section id="komfort-dlya-patsientov_SECTION" class="section comfort">
        <div class="inner" id="comfort">
            <div data-elem="section_<?= $arResult['SECTIONS']['komfort-dlya-patsientov']['id'] ?>" class="section__title text-center"><?= $arResult['SECTIONS']['komfort-dlya-patsientov']['title'] ?></div>
            <div data-elem="section_<?= $arResult['SECTIONS']['komfort-dlya-patsientov']['id'] ?>" class="section__text text-center">
                <?= $arResult['SECTIONS']['komfort-dlya-patsientov']['text'] ?>
            </div>
            <div class="comfort__list mt40">

                <? foreach ($arResult['SECTIONS']['komfort-dlya-patsientov']['items'] as $iNum => $arItem) { ?>
                    <div data-elem="item_<?= $arItem['id'] ?>" class="comfort__list-item">
                        <a href="#<?= $arItem['code'] ?>" data-elem-modal="item_<?= $arItem['id'] ?>" class="js-detail-open"><div class="icon <?= $arItem['icon_code'] ?>"></div>
                        <span><?= $arItem['title'] ?></span>
                        </a>
                    </div>
                <? } ?>
            </div>
        </div>
    </section>

<?php } ?>


<?php if (!empty($arResult['SECTIONS']['uslugi'])) { ?>
    <section id="uslugi_SECTION" class="section service">
        <div class="inner" id="service">
            <div data-elem="section_<?= $arResult['SECTIONS']['uslugi']['id'] ?>" class="section__title text-center"><?= $arResult['SECTIONS']['uslugi']['title'] ?></div>
            <div data-elem="section_<?= $arResult['SECTIONS']['uslugi']['id'] ?>" class="section__text text-center">
                <?= $arResult['SECTIONS']['uslugi']['text'] ?>
            </div>
            <div class="service__list mt40">

                <? foreach ($arResult['SECTIONS']['uslugi']['items'] as $iNum => $arItem) { ?>
                    <div data-elem="item_<?= $arItem['id'] ?>" class="service__list-item">
                        <div class="ttl"><?= $arItem['title'] ?></div>
                        <div class="txt">
                            <?= $arItem['text'] ?>
                        </div>
                    </div>
                <? } ?>

            </div>
        </div>
    </section>

<?php } ?>

