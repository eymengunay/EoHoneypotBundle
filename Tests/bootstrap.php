<?php

$loader = require_once __DIR__ . "/../vendor/autoload.php";

// function registerContainerConfiguration($loader) {
//     $loader->load(__DIR__ . "/config.yml");
// }

function registerBundles() {
    return array(
        new Eo\HoneypotBundle\EoHoneypotBundle(),
    );
}