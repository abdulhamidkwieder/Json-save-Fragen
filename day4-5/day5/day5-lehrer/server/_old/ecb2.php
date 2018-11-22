<?php
 header('Access-Control-Allow-Origin: *');
 header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept' );
 echo file_get_contents( 'https://www.ecb.europa.eu/stats/policy_and_exchange_rates/euro_reference_exchange_rates/html/index.en.html' );
