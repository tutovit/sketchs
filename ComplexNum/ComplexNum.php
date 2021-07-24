<?php

/**
 *
 * //Выражение: (-4+8i) / (2+3i)
 * $obComplexNum = new ComplexNum(-4, 8);
 * $obComplexNum->division(2, 3);
 * var_dump($obComplexNum->getResult());//массив из двух элементов (действительно и мнимое числа)
 * echo $obComplexNum->getResult(true);//строка в формате записи комплексного числа
 *
 * //Выражение: квадратный корень комплексного числа -4
 * $obComplexNum = new ComplexNum();
 * $obComplexNum->sqrt(-4);
 * var_dump($obComplexNum->getResult());//массив из двух элементов (действительно и мнимое числа)
 * echo $obComplexNum->getResult(true);//строка в формате записи комплексного числа
 *
 * //Выражение: квадратный корень комплексного числа -4 плюс (2+3i)
 * $obComplexNum = new ComplexNum();
 * $obComplexNum->sqrt(-4)->plus(2, 3);
 * var_dump($obComplexNum->getResult());//массив из двух элементов (действительно и мнимое числа)
 * echo $obComplexNum->getResult(true);//строка в формате записи комплексного числа
 *
 * Class ComplexNum
 *
 */

class ComplexNum
{
    protected $result = null;


    public function __construct($a = 0, $b = 0)
    {
        $this->result = [$a, $b];

        return $this;
    }

    public function getResult($bString = false)
    {

        if ($bString) {
            if ($this->result[0] == 0) {
                $res = implode('', ['', self::formatArgumentB($this->result[1], true)]);
            } else {
                $res = implode('', [$this->result[0], self::formatArgumentB($this->result[1])]);
            }
        } else {
            $res = $this->result;
        }

        return $res;
    }

    public function plus($a2, $b2)
    {
        $a1 = floatval($this->result[0]);

        if ($this->result[1] === 'i') {
            $b1 = 1;
        } else {
            $b1 = floatval($this->result[1]);
        }

        if ($b2 == 'i') {
            $b2 = 1;
        }

        $this->result = [
            $a1 + $a2,
            $b1 + $b2
        ];

        return $this;
    }


    public function minus($a2, $b2)
    {
        $a1 = floatval($this->result[0]);

        if ($this->result[1] === 'i') {
            $b1 = 1;
        } else {
            $b1 = floatval($this->result[1]);
        }

        if ($b2 == 'i') {
            $b2 = 1;
        }

        $this->result = [$a1 - $a2, $b1 - $b2];

        return $this;
    }

    public function multiply($a2, $b2)
    {
        $a1 = floatval($this->result[0]);

        if ($this->result[1] === 'i') {
            $b1 = 1;
        } else {
            $b1 = floatval($this->result[1]);
        }

        if ($b2 == 'i') {
            $b2 = 1;
        }

        $this->result = [(($a1 * $a2) + ($b1 * -1) * $b2), (($b1 * $a2) + ($a1) * $b2)];

        return $this;
    }


    public function division($a2, $b2)
    {
        $a1 = floatval($this->result[0]);

        if ($this->result[1] === 'i') {
            $b1 = 1;
        } else {
            $b1 = floatval($this->result[1]);
        }

        if ($b2 === 'i') {
            $b2 = 1;
        }

        $this->result = [
            (($a1 * $a2) + ($b1 * $b2)) / (($a2 * $a2) + ($b2 * $b2)),
            (($b1 * $a2) - ($a1 * $b2)) / (($a2 * $a2) + ($b2 * $b2))
        ];

        return $this;
    }

    public function sqrt($arg)
    {
        $arg = floatval($arg);

        if ($arg < 0) {
            $this->result = [0, sqrt($arg * -1)];
        } else {
            $this->result = [sqrt($arg), 0];
        }

        return $this;
    }

    public static function formatArgumentB($sArg, $bSingle = false)
    {
        if ($sArg == 0) {
            $sArg = '';
        } else {
            if (strpos($sArg, '-') === false) {
                if (strpos($sArg, '+') === false && !$bSingle) {
                    $sArg = '+' . $sArg;
                }
            }

            if (strpos($sArg, 'i') === false) {
                $sArg = $sArg . 'i';
            }
        }

        return $sArg;
    }

}