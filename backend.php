<?php

#ob_start("ob_gzhandler");
session_start();

if (isset($_POST['begin']) || isset($_GET['begin'])) initExperiment();
else if (isset($_POST['id'])) newUser();
else if (isset($_GET['reset'])) restartExperiment();
else if (isset($_GET['label']) || isset($_POST['label'])) storeResult();
else if (isset($_GET['trial']) || $_POST['trial']) sendNextTrial();
else exit;

function restartExperiment() {
  unset($_SESSION['started']);
}

function newUser() {
    $id = $_POST['id'];
 
    $con = mysqli_connect('127.0.0.1', 'root', 'egbdfEGBDF02!');
    if (!$con) die('Could not connect: ' . mysqli_error());
    mysqli_select_db($con, 'testing');

    # ID MUST NOT EXIST IN DATABASE!
    $result = mysqli_query($con, "SELECT * FROM users WHERE username='".$id."'");
    $numrow = mysqli_num_rows($result);
    $row    = mysqli_fetch_array($result);
 
    initExperiment($row['id']);

    if ($numrow == 1) {
        $verify = null;
        print json_encode(array('verify'=>1, 'id'=>$row['id'], 'username'=>$id));
        exit; 
    } else { 
        print json_encode(array('verify'=>0));
    } 
}

function initExperiment($id) {
    $_SESSION['started'] = TRUE;
        
    $con = mysqli_connect('127.0.0.1', 'root', 'egbdfEGBDF02!');
    if (!$con) die('Could not connect: ' . mysqli_error());
    mysqli_select_db($con, 'testing');
    
    # get list of all storms already classified by this user
    $result = mysqli_query($con, "SELECT stormnum FROM classify WHERE usernum = '".$id."'");
        
    $_SESSION['storms_seen'] = array();
    while($row = mysqli_fetch_array($result)) {
        $_SESSION['storms_seen'][] = $row['stormnum'];
    }
    # DATABASE CALL: SELECT GROUP from groups
    # DATABASE CALL: UPDATE num in groups
    #$result = mysql_query("SELECT * FROM groups ORDER BY numusers ASC, number ASC LIMIT 1");
    #$row = mysql_fetch_array($result);
  
    # DATABASE CALL: INSERT USER INTO users
    #$result = mysql_query("UPDATE users SET group_type=".$row['number']." WHERE user_num='".$_SESSION['user_num']."'");

    #print json_encode(array('text'=>$text, 'img'=>$img));
}

function storeResult() {
  $con = mysqli_connect('127.0.0.1', 'root', 'egbdfEGBDF02!');
  if (!$con) die('Could not connect: ' . mysqli_error());
  mysqli_select_db($con, 'testing');
  
  $time_start = date("Y-m-d H:i:s");
  $usernum    = $_POST['userid'];
  $stormnum   = $_POST['thisid'];
  $label      = $_POST['label'];
  $conf       = $_POST['conf'];
 
  #$_SESSION['user_num'] = mysqli_insert_id();
  #$_SESSION['id'] = $id;
  
  $_SESSION['storms_seen'][] = $stormnum;
   
  $result = mysqli_query($con, "INSERT INTO classify (usernum, stormnum, label, conf, labeltime) VALUES ('".$usernum."', '".$stormnum."', '".$label."', '".$conf."', '".$time_start."')");
  
  #mysqli_close($con); 
  #print json_encode(array('result'=>$result, 'conf'=>$conf));
}

function sendNextTrial() {
  $con = mysqli_connect('127.0.0.1', 'root', 'egbdfEGBDF02!');
  if (!$con) die('Could not connect: ' . mysqli_error());
  mysqli_select_db($con, 'testing');

  if (count($_SESSION['storms_seen']) > 0) { 
      $seenstring = implode(',', $_SESSION['storms_seen']);
      $result = mysqli_query($con, "SELECT * FROM storms WHERE id NOT IN (".$seenstring.") ORDER BY RAND() LIMIT 1");
  } else {
      $result = mysqli_query($con, "SELECT * FROM storms WHERE id ORDER BY RAND() LIMIT 1");
  }

  $numrow = mysqli_num_rows($result);
  $row = mysqli_fetch_array($result);

  if ($numrow < 1) $nostorms = true;
  else $nostorms = false;

  print json_encode(array('seenstring'=>$seenstring, 'imgname'=>$row['imgstring'], 'id'=>$row['id'], 'numclassified'=>count($_SESSION['storms_seen'])));
  #mysqli_close($con); 
}
?>
