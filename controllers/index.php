<?php

$this->map('/', function ($application) {
    $application->getView()->setScript('index/index');
});