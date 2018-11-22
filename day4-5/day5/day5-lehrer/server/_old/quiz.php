<?php
 usleep( mt_rand( 100*1000, 1000*1000) ); // 250ms verzögerung
 header('Access-Control-Allow-Origin: *');
 header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept' );
 header('Content-Type:application/json' );

  // get request data
if ( isset( $_GET['anfragetyp'] )) {
  $req =  new stdClass();
  $req->anfragetyp = $_GET['anfragetyp'];
  $req->schwierigkeit = $_GET['schwierigkeit'];
  $req->id = $_GET['id'];
  $req->db = $_GET['db'];
  $req->antwort = $_GET['antwort'];
} else {
  $req = file_get_contents('php://input');
  if ( $req == '' ) error(500);

  $req = json_decode( $req );
}



if ( isset( $req->db ) &&  $req->db != '' &&  $req->db != 'all' ) {
  $file = 'fragen_'.$req->db.'.json';
} else {
    $file = 'fragenx.json';
}

// make new file
if ( !file_exists( $file ) ) {
  //file_put_contents($file, '{"maxid":0, "quiz":[]}' );
  error(501);
}


$fragen = file_get_contents(  $file );
$f = json_decode( $fragen );
$maxid = $f->maxid;
if ( is_array( $f->highscore ) ) {
  $highscore = $f->highscore;
} else {
  $highscore = array();
}

$f = $f->quiz;

function error( $nr, $feldname = '' ) {
  $response = new stdClass();
  $response->fehlernummer = $nr;
  if ( $feldname != '' ) {
    $response->feldname = $feldname;
  }
  echo json_encode( $response );
  exit;
}

 $response = new stdClass();
 switch( $req->anfragetyp ) {
   case "frage":

    if ( !isset( $req->schwierigkeit )
    ) {
      error( 407 );
    }

    if ( $req->schwierigkeit == '' ) { error(407,'schwierigkeit'); }

    $r = array();
    foreach( $f as $fitem ) {
      if ( $req->schwierigkeit == $fitem->schwierigkeit )
        $r[] = $fitem;
    }
    if ( count($r) == 0 ) { error(410); }
    $randomF = $r[  mt_rand(0,count($r)-1) ];

    $response->id = $randomF->id;
    $response->frage = $randomF->frage;
    $response->antworten = $randomF->antworten;

   break;
   case "check":

      if ( !isset( $req->id )
      ) {
        error( 407 );
      }

        foreach( $f as $fitem ) {
            if ( $req->id == $fitem->id ) break;

          }

      if ( isset( $req->antwort )) {
          $response->korrekt = $req->antwort == $fitem->richtig;
      } else {
          $response->richtig = $fitem->richtig;
      }

    break;
   default:
    error(500);

 }

 echo json_encode( $response );
