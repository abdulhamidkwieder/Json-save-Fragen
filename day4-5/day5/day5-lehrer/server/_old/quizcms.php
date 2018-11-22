<?php
 usleep( mt_rand( 100*1000, 1000*1000) ); // 250ms verzögerung
 header('Access-Control-Allow-Origin: *');
 header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept' );
 header('Content-Type:application/json' );

  // get request data
if ( isset( $_GET['anfragetyp'] )) {
  $req =  new stdClass();
  $req->anfragetyp = $_GET['anfragetyp'];
  $req->frage = $_GET['frage'];
  $req->antworten = array();
  $req->antworten[0] = $_GET['a1'];
  $req->antworten[1] = $_GET['a2'];
  $req->antworten[2] = $_GET['a3'];
  $req->antworten[3] = $_GET['a4'];
  $req->richtig = $_GET['richtig'];
  $req->schwierigkeit = $_GET['schwierigkeit'];
  $req->id = $_GET['id'];
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
  file_put_contents($file, '{"maxid":0, "quiz":[]}' );
}


$fragen = file_get_contents(  $file );
$f = json_decode( $fragen );
$maxid = $f->maxid;
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
   case "neu":

    if ( !isset( $req->frage ) ||
      !is_array( $req->antworten ) ||
      !isset( $req->richtig ) ||
      !isset( $req->schwierigkeit )
    ) {
      error( 407 );
    }
      if ( $req->frage == '' ) { error(407,'frage'); }
      if ( $req->antworten[0] == '' ) { error(407,'antwort'); }
      if ( $req->antworten[1] == '' ) { error(407,'antwort'); }
      if ( $req->antworten[2] == '' ) { error(407,'antwort'); }
      if ( $req->antworten[3] == '' ) { error(407,'antwort'); }
      if ( $req->schwierigkeit == '' ) { error(407,'schwierigkeit'); }

    foreach( $f as $fitem ) {
      if ( $fitem->frage == $req->frage ) {
        error( 408 );
      }
    }

    $maxid++;
    $entry = new stdClass();

      $entry->id =  $maxid;
      $entry->frage = $req->frage;
        $entry->antworten=array(
        $req->antworten[0],
        $req->antworten[1],
        $req->antworten[2],
        $req->antworten[3],
      );
      $entry->richtig=$req->richtig;
      $entry->schwierigkeit=$req->schwierigkeit;


    $response->id = $maxid;
    $f[] = $entry;
    $v = new stdClass();
    $v->maxid = $maxid;
    $v->quiz = $f;
    file_put_contents($file , json_encode( $v ) );

   break;
   case "edit":
      if ( !isset( $req->id ) || !isset( $req->frage ) ||
        !is_array( $req->antworten ) ||
        !isset( $req->richtig ) ||
        !isset( $req->schwierigkeit )
      ) {
        error( 407 );
      }

      if ( $req->frage == '' ) { error(407,'frage'); }
      if ( $req->antworten[0] == '' ) { error(407,'antwort'); }
      if ( $req->antworten[1] == '' ) { error(407,'antwort'); }
      if ( $req->antworten[2] == '' ) { error(407,'antwort'); }
      if ( $req->antworten[3] == '' ) { error(407,'antwort'); }
      if ( $req->schwierigkeit == '' ) { error(407,'schwierigkeit'); }

      $r = array();
      $edit = false;
      foreach( $f as $fitem ) {
        if ( $fitem->frage == $req->frage &&  $fitem->id != $req->id) {
          error( 408 );
        }
        if ( $fitem->id != $req->id ) {
          $r[] = $fitem;
        } else {
          $edit = true;
              $entry = new stdClass();

                $entry->id =  $req->id;
                $entry->frage = $req->frage;
                  $entry->antworten=array(
                  $req->antworten[0],
                  $req->antworten[1],
                  $req->antworten[2],
                  $req->antworten[3],
                );
                $entry->richtig=$req->richtig;
                $entry->schwierigkeit=$req->schwierigkeit;
          $r[] = $entry;
        }
      }

      if ( !$edit ) {
        error( 404 );
      }

      $v = new stdClass();
      $v->maxid = $maxid;
      $v->quiz = $r;
      file_put_contents($file , json_encode( $v ) );

      $response->erfolg = true;



   break;
  case "entfernen":
      if ( !isset( $req->id ) ) {
        error( 407 );
      }
      $r = array();
      $del = false;
      foreach( $f as $fitem ) {
        if ( $fitem->id != $req->id ) {
          $r[] = $fitem;
        } else {
          $del = true;
        }
      }

      if ( !$del ) {
        error( 404 );
      }

      $v = new stdClass();
      $v->maxid = $maxid;
      $v->quiz = $r;
      file_put_contents($file , json_encode( $v ) );

      $response->erfolg = true;


 break;
   case "lesen":

    $r = array();
    foreach( $f as $fitem ) {
      if ( !isset( $req->schwierigkeit ) || $req->schwierigkeit == $fitem->schwierigkeit )
        $r[] = $fitem;
    }
    $response->fragen = $r;
   break;


   default:
    error(500);

 }

 echo json_encode( $response );
