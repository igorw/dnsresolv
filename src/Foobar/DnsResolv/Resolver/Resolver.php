<?php

namespace Foobar\DnsResolv\Resolver;

/**
 * DNS query resolver.
 *
 * Based on PEAR Net_Dig.
 */
class Resolver
{
    private $parser;

    public function __construct(DigParser $parser)
    {
        $this->parser = $parser;
    }

    public function query($server, $name, $type)
    {
        $cmd = sprintf(
            'dig @%s %s %s',
            escapeshellarg($server),
            escapeshellarg($name),
            escapeshellarg($type)
        );
        exec($cmd, $output);
        $raw = trim(implode("\n", $output));
        return $this->parser->parse($raw);
    }
}

