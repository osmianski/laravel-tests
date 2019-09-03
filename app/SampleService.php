<?php

namespace App;

class SampleService
{
    protected $host;
    protected $port;

    public function __construct($host = 'localhost', $port = 80) {
        $this->host = $host;
        $this->port = $port;
    }

    // imaginary public methods would connect to specified host, call some API and return the result
}
