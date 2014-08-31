<?php

$finder = \Symfony\CS\Finder\DefaultFinder::create()
    ->files()
    ->name('*.php')
    ->in('source');

return \Symfony\CS\Config\Config::create()
    ->finder($finder);
