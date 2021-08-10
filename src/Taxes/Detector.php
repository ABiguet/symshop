<?php

namespace App\Taxes;

class Detector
{
    protected $seuil;
    public function __construct(float $seuil){
        $this->seuil=$seuil;
    }
    public function detect(float $test) : bool
    {
        if ($test > $this->seuil)
        {
            return true;
        }
        return false;
    }
}