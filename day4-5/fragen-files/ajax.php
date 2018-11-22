<?php
// هذا الملف مرتبط بملف الجايسون لحفظ واستدعاء الأسئلة أو الداتا من السيرفر

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin,x-Requested-with,Content-Type, Accept');
header('Content-Type:application/json');

$antwort = new stdClass();

$antwort->x = str_replace( ',' , '.' , $_POST['x'] ); // wandelt , in . um

if ( is_numeric( $antwort->x ) ) {
  $antwort->check = true;
} else {
  $antwort->check = false;
}

echo json_encode( $antwort ); // wandelt Objekt in JSON um



/*
function getFragen() {
    $jsondatei = file_get_contents('fragen.json');
    $json = json_decode( $jsondatei);
    return $json->fragen;
}

function saveFragen($fragen) {
    $json = new stdClass();
    $json->fragen = $fragen;
    $jsondatei = json_encode($json);
    file_put_contents('fragen.json', $jsondatei);
}

 $antwort->x = str_replace(',','.', $_POST['x']);

if (is_numeric($antwort->x)) {
    //echo '{"check":true}';
    $antwort->check =true;
} else {
  //  echo '{"check":false}';
  $antwort->check =false;
} 

if (condition) {
    # code...
} 

switch ($_POST['requestart']) {
    case 'speichern':
        $alleFragen = getFragen();
        $neueFrage = new stdClass();
        $neueFrage->frage = $_POST['frage'];
    break;

    case 'ausgabe':
        $alleFragen = getFragen();
        $response = new stdClass();
        $response->fragen = $alleFragen;
        echo json_encode($response);
    break;

    case 'loeschen':
        #
    break;
    
}
*/
// echo json_encode($antwort);

