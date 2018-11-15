<?php
require_once '../vendor/autoload.php';

use JLSalinas\RWGen\Readers\Csv;
use JLSalinas\RWGen\Writers\Kml;

$filename = __DIR__ . '/FL_insurance_sample.csv';

$input = new Csv($filename);
$output = new Kml($filename . '.kml', [
    'overwrite' => 1,
    'func_folder' => function ($x) {
        return $x['state'] . ' - ' . $x['county'];
    }
]);

$count = [];

foreach ( $input as $customer ) {
    $key = $customer['statecode'] . ' - ' . $customer['county'];
    if ( !isset($count[$key]) ) {
        $count[$key] = 0;
    }
    $count[$key] += 1;
    if ( $count[$key] > 3 ) {
        continue;
    }

    $new_customer = [
        'name' => $customer['policyID'],
        'state' => $customer['statecode'],
        'county' => $customer['county'],
        'lat' => $customer['point_latitude'],
        'lng' => $customer['point_longitude'],
        'value' => $customer['tiv_2012'],
    ];
    $output->send($new_customer);
}
