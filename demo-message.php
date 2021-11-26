<?php

namespace Derweili\RnkBot;

/**
 * Import Composer
 */
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/config.php';

$centers = new Centers();
$vaccines = new Vaccines();


$notification = new PushoverNotification( $centers->get_all_ids()[0], $vaccines->get_all_ids()[0] ); 

$notification->send_available_vaccine_notification();