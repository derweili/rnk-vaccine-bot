<?php

namespace Derweili\RnkBot;

/**
 * Import Composer
 */
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/config.php';

$centers = new Centers();
$vaccines = new Vaccines();

/**
 * Set max execution time
 */
set_time_limit(300);

$bot = new Bot( $centers, $vaccines );

$bot->run();