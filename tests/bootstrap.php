<?php

require_once __DIR__.'/../vendor/silex.phar';

$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$loader->registerNamespace('Foobar', array(__DIR__.'/../src', __DIR__));
$loader->register();
