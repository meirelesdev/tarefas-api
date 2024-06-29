<?php

require '../vendor/autoload.php';

use OpenApi\Generator;

header('Content-Type: application/json');

$openapi = Generator::scan([__DIR__ . '/../app/Controllers']);
echo $openapi->toJson();