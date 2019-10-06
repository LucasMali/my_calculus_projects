<?php
/**
 * @author Lucas Maliszewski
 * Copyright 2019 Lucas Maliszewski
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the 
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included 
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT 
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF 
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE 
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 */

/**
 * TrapSimp
 */
class TrapSimp
{
    const INTEGRATION = true;
    const ROUND = 2;
    private $b;
    private $a;
    private $n;
    private $w;
    private $values;
    private $t;
    private $s;
    private $currentN;

    public function __construct()
    {
        $this->a = 0;
        $this->b = 160;
        $this->n = 4;
        $this->w = 0;
    }

    public function run()
    {
        if (is_array($this->n)) {
            foreach ($this->n as $n) {
                $this->currentN = $n;
                $this->setW($n);
                $this->calculateValues();
            }
        } else {
            $this->currentN = $this->n;
            $this->setW($this->n);
            $this->calculateValues();
        }
    }

    protected function calculateValues()
    {
        $this->values = $this->loadValues();
        $this->t = $this->trap($this->values);
        $this->s = $this->simp($this->values);
        echo "N: " . $this->currentN;
        echo " | DX: " . $this->w;
        echo " | TRAP: " . sprintf('%.' . self::ROUND . 'f', $this->t);
        echo " | SIMP: " . sprintf('%.' . self::ROUND . 'f', $this->s);
        echo " | TRAP ERROR: " . sprintf('%.' . self::ROUND . 'f', $this->getTrapError());
        echo " | SIMP ERROR: " . sprintf('%.' . self::ROUND . 'f', $this->getSimpError()) . PHP_EOL;
    }

    public function setW($n)
    {
        $this->w = ($this->b - $this->a) / $n;
    }

    private function trap($values)
    {
        $f = 1 / 2 * array_shift($values);
        $l = 1 / 2 * array_pop($values);
        $m = array_sum($values);
        return $this->w * ($f + $m + $l);
    }

    private function simp($values)
    {
        $f = array_shift($values);
        $l = array_pop($values);

        foreach ($values as $key => $value) {
            if ($key % 2 == 0) {
                $even[] = $value;
            } else {
                $odd[] = $value;
            }
        }

        /*
         * This is opposite because they flip flop due to the first and last 
         * being taken off, so it seems that the even is odd and
         * the odd is even but if we put the first and last
         * values back onto the stack, they are being affected 
         * correctly.
         */

        // According to the rules, the evens are multiplied with 2--see case statement above.
        $sum = array_sum($even) * 4;
        // According to the rules, the odds are multiplied with 4--see case statement above.
        $sum += array_sum($odd) * 2;
        $sum += $f + $l;

        return $sum * ($this->w / 3);
    }

    private function loadValues($int = false)
    {
        $middle = [];
        for ($i = $this->a, $j = $this->b; $i <= $j; $i += $this->w) {
            if ($int) {
                $middle[] = $this->theEqInt($i);
            } else {
                $middle[] = $this->theEq($i);
            }
        }
        return $middle;
    }

    private function getTrapError()
    {
        $c = $this->t;
        $x = $this->theEqInt($this->b) - $this->theEqInt($this->a);
        return  \abs($c - $x);
    }

    private function getSimpError()
    {
        $c = $this->s;
        $x = $this->theEqInt($this->b) - $this->theEqInt($this->a);
        return (\abs($this->s) - $x);
    }

    protected function theEq($x)
    {
        // The intergrand.
        $eq = 0;
        $eq = $x;
        return $eq;
    }

    protected function theEqInt($x)
    {
        // For now you'll have to manually find the intergral.
        $eq = 0;
        // $eq = Intergral goes here...
        return $eq;
    }
}

$ts = new TrapSimp();
$ts->run();
