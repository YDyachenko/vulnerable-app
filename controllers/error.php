<?php

$this->setController('pageNotFound', function ($application) {
    
});

$this->setController('internalError', function ($application, $exception = null) {
    return array(
        'exception' => $exception
    );
});