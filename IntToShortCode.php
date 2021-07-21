<?
class IntToShortCode
{
    /**
     * Служит для создания защищённого от подбора короткого символьного кода из целого числа
     */
    static $set = 'laksjdhfgzmxncbv'; //свой набор символов, которые будет содержать символьный код
    static $hex = '0123456789abcdef';

    public static function encode($n)
    {
        $arSet = str_split(self::$set);
        $arHex = str_split(self::$hex);
        $arChars = str_split(dechex($n));

        foreach ($arChars as $i => &$char) {
            $char = $arSet[16 - (array_search($char, $arHex) + 1)];
        }

        $md5 = substr(md5($n), strlen(md5($n)) - 2);

        return implode('', $arChars) . $md5;
    }

    public static function decode($str)
    {
        $arSet = str_split(self::$set);
        $arHex = str_split(self::$hex);

        $md5check = substr($str, strlen($str) - 2);
        $str = substr($str, 0, strlen($str) - 2);

        $arChars = str_split($str);

        foreach ($arChars as $i => &$char) {
            $char = $arHex[16 - (array_search($char, $arSet) + 1)];
        }

        $iResult = hexdec(implode($arChars));
        $md5 = substr(md5($iResult), strlen(md5($iResult)) - 2);

        if ($md5 !== $md5check) {
            $iResult = false;
        }

        return $iResult;
    }
}
