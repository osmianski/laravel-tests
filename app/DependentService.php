<?php

namespace App;

class DependentService
{
    /**
     * @var SampleService
     */
    protected $from;
    /**
     * @var SampleService
     */
    protected $to;

    public function __construct(SampleService $from, SampleService $to) {
        $this->from = $from;
        $this->to = $to;
    }
}
