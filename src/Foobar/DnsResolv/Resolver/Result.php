<?php

namespace Foobar\DnsResolv\Resolver;

class Result
{
    public $query, $answer, $authority, $additional;
    public $queryTime;

    public function __construct($query = null, $answer = null, $authority = null, $additional = null, $queryTime = null)
    {
        $this->query = $query;
        $this->answer = $answer;
        $this->authority = $authority;
        $this->additional = $additional;
        $this->queryTime = $queryTime;
    }
}

