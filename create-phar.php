<?php

ini_set("phar.readonly", 0);

$pharFile = "bin/cidr.phar";

if (file_exists($pharFile)) {
    unlink($pharFile);
}
if (file_exists($pharFile.".gz")) {
    unlink($pharFile.".gz");
}

$p = new Phar($pharFile);
$p->buildFromDirectory("src/");
$p->setDefaultStub("/main.php");
$p->compress(Phar::GZ);

echo "compile success!!", PHP_EOL;
