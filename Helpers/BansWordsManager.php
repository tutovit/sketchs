<?php

use Model\BansWords;

/**
 * Класс для работы с запрещёнными словами
 *
 * Class BansWordsManager
 */
class BansWordsManager {
    /**
     * Получение массива запрещённых слов
     * @return array
     */
    public static function GetBansWords() {
        /**
         * Массив запрещённых слов
         */
        static $words = [];
        if (empty($words)) {
            $words = BansWords::select()->get()->toArray();
        }
        return $words;
    }

    /**
     * Поиск и возврат списка запрещённых слов, которые содержит текст
     * @param $text
     * @return array
     */
    public static function TestBansWords($text) {
        $arTextWords = str_word_count($text, 1, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя");
        $arRows = BansWords::where(function($query) use ($arTextWords){
            foreach($arTextWords as $keyword){
                $query = $query->orWhere('word', 'LIKE', "$keyword");
            }
            return $query;
        })->get()->toArray();

        return array_column($arRows, 'word');
    }
}
