<?php

namespace Foobar\DnsResolv\Resolver;

class Resource
{
    public $host, $ttl, $class, $type, $data;

    public function __construct($host = null, $ttl = null, $class = null, $type = null, $data = null)
    {
        $this->host = $host;
        $this->ttl = $ttl;
        $this->class = $class;
        $this->type = $type;
        $this->data = $data;
    }
}

