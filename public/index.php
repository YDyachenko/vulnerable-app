<?php

chdir(dirname(__DIR__));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath('./library'),
    get_include_path(),
)));

include 'Micro/Loader/StandardAutoloader.php';

$loader = new Micro\Loader\StandardAutoloader();
$loader->register();

$application = new Micro\Application\Application(include './config.php');
$application->run();