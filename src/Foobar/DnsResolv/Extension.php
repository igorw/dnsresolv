<?php

namespace Foobar\DnsResolv;

use Foobar\DnsResolv\Resolver\Resolver;
use Foobar\DnsResolv\Resolver\DigParser;

use Silex\Application;
use Silex\ExtensionInterface;

use Symfony\Component\Yaml\Yaml;

class Extension implements ExtensionInterface
{
    public function register(Application $app)
    {
        $app['dnsresolv.record_types'] = array('A', 'AAAA', 'CNAME', 'NS', 'MX', 'PTR', 'SPF', 'SRV', 'SRV', 'TXT', 'SOA');

        $app['dnsresolv.servers'] = $app->share(function ($app) {
            return Yaml::parse($app['dnsresolv.servers.filename']);
        });

        $app['dnsresolv.dig_parser'] = $app->share(function ($app) {
            return new DigParser();
        });

        $app['dnsresolv.resolver'] = $app->share(function ($app) {
            return new Resolver($app['dnsresolv.dig_parser']);
        });
    }
}

