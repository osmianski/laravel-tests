<?php

namespace App;

class DependentService
{
    protected $from;
    protected $to;

    public function __construct(SampleService $from, SampleService $to) {
        $this->from = $from;
        $this->to = $to;
    }
}
