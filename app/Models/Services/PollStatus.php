<?php namespace App\Models\Services;

class PollStatus {
    public $Waiting;
    public $Yes;
    public $No;
    public $Total;
    
    public function __construct($y, $n, $w)
    {
        $this->Waiting = $w;
        $this->Yes = $y;
        $this->No = $n;
        $this->Total = $y + $n + $w;
    }
}
