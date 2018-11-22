<?php
 header('Access-Control-Allow-Origin: *');
 header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept' );
header( 'Content-Type:application/json');
 echo file_get_contents( 'http://wifi.1av.at/527/ubahnen_alles.json' );
