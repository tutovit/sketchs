<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Список запрещённых слов
 *
 *
 * @property string word - слово
 *
 */
class BansWords extends Model {
    /**
     * Имя таблицы
     */
    const TABLE_NAME = "bans_words";

    /**
     * Отключаем встроенную обработку created_at, updated_at
     */
    public $timestamps = false;
}