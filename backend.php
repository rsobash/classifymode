<?php

session_start();
    
$server = "127.0.0.1";
$username = "root";
$password = "egbdfEGBDF02!";

if (isset($_POST['begin']) || isset($_GET['begin'])) initExperiment();
else if (isset($_POST['id'])) newUser();
else if (isset($_GET['label']) || isset($_POST['label'])) storeResult();
else if (isset($_GET['trial']) || $_POST['trial']) sendNextStorm();
else exit;

function dbconnect() {
    global $server, $username, $password;
    $mysqli = new mysqli($server, $username, $password, 'testing');
    if ($mysqli->connect_error) exit('Error connecting to database');
    return $mysqli;
}

function newUser() { 
    $id = $_POST['id'];
    $mysqli = dbconnect();

    # ensure input id consists of 3-12 lowercase alphabetic letters (presumably the users last name)
    if (!preg_match('/[a-z]{3,12}/', $id)) {
        print json_encode(array('verify'=>0));
        exit;
    }

    # id must already exist in database
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $numrow = $result->num_rows;
    $row = $result->fetch_assoc();
    $stmt->close();

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
    $mysqli = dbconnect();
        
    # get list of all storms already classified by this user
    $stmt = $mysqli->prepare("SELECT count(stormnum) AS count FROM classify WHERE usernum = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $_SESSION['numclassified'] = $row['count'];
    
    #$_SESSION['storms_seen'] = array();
    #while($row = $result->fetch_assoc()) {
    #    $_SESSION['storms_seen'][] = $row['stormnum'];
    #} 

    $stmt->close();
}

function storeResult() {
    $mysqli = dbconnect();
  
    $time_start = date("Y-m-d H:i:s");
    $usernum    = $_POST['userid'];
    $stormnum   = $_POST['thisid'];
    $label      = $_POST['label'];
    $conf       = $_POST['conf'];
 
    #$_SESSION['storms_seen'][] = $stormnum;
    $_SESSION['numclassified'] += 1;

    $stmt = $mysqli->prepare("INSERT INTO classify (usernum, stormnum, label, conf, labeltime) VALUES ( ?, ?, ?, ?, ? )");
    $stmt->bind_param("iisis", $usernum, $stormnum, $label, $conf, $time_start);
    $stmt->execute();
    $stmt->close();
}

function sendNextStorm() {
    $mysqli = dbconnect();
  
    # this could slow down in the future when classify/storms tables are large...
    $stmt = $mysqli->prepare("SELECT * FROM storms WHERE id NOT IN ( SELECT stormnum FROM classify WHERE usernum = ? ) ORDER BY RAND() LIMIT 1");
    $stmt->bind_param("s", $_GET['userid']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $numrow = $result->num_rows;
    $row = $result->fetch_assoc();
    $stmt->close();
    
    # check if no storms are returned
    if ($numrow < 1) $nostorms = true;
    else $nostorms = false;

    #$seenstring = implode(',', $_SESSION['storms_seen']);
    #print json_encode(array('seenstring'=>$seenstring, 'imgname'=>$row['imgstring'], 'id'=>$row['id'], 'numclassified'=>count($_SESSION['storms_seen'])));

    print json_encode(array('imgname'=>$row['imgstring'], 'id'=>$row['id'], 'numclassified'=>$_SESSION['numclassified']));
}
?>
