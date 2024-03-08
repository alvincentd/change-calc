<?php

namespace App\Services;

class DenominationService
{
    private array $denominations = [
        100,
        200,
        500,
        1000,
        2000,
        5000,
        10000,
        20000,
        50000,
        100000
    ];
    private string $exact = 'UANG PAS';

    public function getCombinations(int $amount): array
    {
        if($amount >= 100000) {
            return [$this->exact];
        }

        $combinations = [];
        $lesser = [];
        $greaterOrEqual = [];
        //Split jadi lebih kecil dan lebih besar / sama dengan
        foreach($this->denominations as $d) {
            if($amount > $d) {
                $lesser[] = $d;
            }else {
                $greaterOrEqual[] = $d;
            }
        }

        //Sort descending
        rsort($lesser);
        $lessers = $lesser;

        for($x = 0;$x < count($lesser); $x++) {
            $init = $lesser[$x];
            foreach($lessers as $value) {
                $total = $init;
                while($total < $amount) {
                    $total += $value;
                }
                if($total >= $amount && $total < max($this->denominations)) {
                    $combinations[] = $total;
                }
            }
            //Hapus value paling besar
            array_shift($lessers);
        }

        $combinations = array_values(array_unique(array_merge($combinations, $greaterOrEqual)));
        sort($combinations);
        foreach($combinations as $key => $combination) {
            if($combination === $amount) {
                $combinations[$key] = $this->exact;
            }
        }
        return $combinations;
    }
}
