<?php

use Symfony\Component\HttpFoundation\Response;

require __DIR__.'/../vendor/silex.phar';

$app = new Silex\Application();

$app['autoloader']->registerNamespace('Foobar', __DIR__.'/../src');
$app['autoloader']->registerNamespace('Symfony', __DIR__.'/../vendor/symfony/src');
$app->register(new Foobar\DnsResolv\Extension(), array(
    'dnsresolv.servers.filename' => __DIR__.'/../config/servers.yml',
));

$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.path'       => __DIR__.'/../views',
    'twig.class_path' => __DIR__.'/../vendor/twig/lib',
));

$app['debug'] = true;

/**
 * Index page
 */
$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
});

/**
 * Resolve DNS queries
 *
 * query string options
 * * server: the dns server to query
 * * name: the record to look up (eg. customer server name)
 * * type: record type; A, AAAA, CNAME, NS, MX, PTR, SPF, SRC, TXT, SOA
 */
$app->get('/resolve', function () use ($app) {
    $request = $app['request'];

    $hostnames = array_map(function ($server) { return $server['hostname']; }, $app['dnsresolv.servers']);
    if (!in_array($request->get('server'), $hostnames)) {
        $app->abort(400, 'Invalid server supplied.');
    }

    $hostnameRegex = '/^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)*[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?$/';
    if (!preg_match($hostnameRegex, $request->get('name'))) {
        $app->abort(400, 'Invalid host name supplied.');
    }

    if (!in_array($request->get('type'), $app['dnsresolv.record_types'])) {
        $app->abort(400, 'Invalid record type supplied.');
    }

    $resolver = $app['dnsresolv.resolver'];
    $result = $resolver->query(
        $request->get('server'),
        $request->get('name'),
        $request->get('type')
    );

    return new Response(
        json_encode($result),
        200,
        array('Content-Type' => 'application/json')
    );
});

$app->get('/servers', function () use ($app) {
    return new Response(
        json_encode(array('servers' => $app['dnsresolv.servers'])),
        200,
        array('Content-Type' => 'application/json')
    );
});

$app->error(function ($e, $code) {
    return new Response($e->getMessage(), $code);
});

$app->run();
