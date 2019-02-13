<?php
require_once __DIR__ . '/vendor/autoload.php';

$logger = new class extends \GBench\AbstractHandler {
    public function startAfter(\GBench\GBench $gbench)
    {
        echo 'good start';
    }

    public function stopAfter(\GBench\GBench $gbench, \GBench\Record $record)
    {
        echo 'good stop';
    }
};

$gb = new \GBench\GBench($logger);
$gb->start(); // good start
$gb->stop(); // good stop
