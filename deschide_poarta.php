<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

//session_start();
require_once 'db_connection.php';
//mysqli_report(MYSQLI_REPORT_ALL);
//mysqli_report(MYSQLI_REPORT_ERROR);

if(!isset($_GET['opt'])) $_GET['opt']='';
if(!isset($_POST['tag_poarta'])) $_POST['tag_poarta']='';
if(!isset($_POST['id_elev'])) $_POST['id_elev']='';
if(!isset($_POST['activ'])) $_POST['activ']='';

$opt = $_GET['opt'];
$opt=htmlspecialchars($opt);

$tag_poarta = $_POST['tag_poarta'];
$tag_poarta=htmlspecialchars($tag_poarta);

$id_elev = $_POST['id_elev'];
$id_elev=htmlspecialchars($id_elev);

$activ = $_POST['activ'];
$activ=htmlspecialchars($activ);

switch($opt){
    case 'send':
      //	id 	Nume 	Clasa 	Orgunit 	Motiv 	Obs 	data1 	data2 	ora1 	ora2 	id_google 	valid
      $sql = "INSERT INTO poarta (tag_poarta, id_elev, activ) VALUES ('".$tag_poarta."', '".$id_elev."', '".$activ."')";
      //print $sql;
  
      if (mysqli_query($db_connection, $sql)) {
          echo "Comanda trimisa cu succes";
      } else {
          //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
          echo "Error: ". mysqli_error($db_connection);
      }
    break;
}

?>