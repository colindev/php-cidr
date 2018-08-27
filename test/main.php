#!/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

$cidr = cidr\parseCIDR($argv[1]);
$ip = cidr\parseIPv4($argv[2]);

var_dump($cidr->inRange($ip));
