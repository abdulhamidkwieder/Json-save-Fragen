<?php
  header( 'Access-Control-Allow-Origin: *' );
  header( 'Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept' );

  usleep( mt_rand( 500000,3000000) );
  $a = array( 'Hallo', 'Servus', 'Grüß Gott', 'Griaß di');
  echo '<div>'.$a[mt_rand(0,3)].' Welt</div>';
