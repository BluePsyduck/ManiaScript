<?php

spl_autoload_register(function($class) {
    $found = false;
    if (substr($class, 0, 12) === 'ManiaScript\\') {
        $file = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        var_dump($file);
        if (file_exists($file)) {
            require_once($file);
            $found = true;
        }
    }
    return $found;
});