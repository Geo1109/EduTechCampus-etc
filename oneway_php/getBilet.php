<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

//session_start();
require_once 'db_connection.php';
//mysqli_report(MYSQLI_REPORT_ALL);
//mysqli_report(MYSQLI_REPORT_ERROR);

if(!isset($_GET['opt'])) $_GET['opt'] = '';
if(!isset($_GET['key'])) $_GET['key'] = '';
if(!isset($_GET['mac'])) $_GET['mac'] = '';

/*
$_POST['key'] = '3d449c6640760504dde5ba63f9e719bf';ec36008f7ec61284151bc7ce461b6b2a
$_POST['mac'] = '9494093d-0f75-4fe4-9f12-30f764f2105a';
*/

$opt = $_GET['opt'];
$opt=htmlspecialchars($opt);

$mac = $_GET['mac'];
$mac=htmlspecialchars($mac);

$key = $_GET['key'];
$key=htmlspecialchars($key);
//print "key=".$key;
//print "<br>";
//print "mac=".$mac;

        switch ($opt){
            case 'tot':
                       //id	email	Nume	Clasa	Orgunit	Motiv	Obs	data1	data2	ora1	ora2	id_google	valid
                $stmt = $db_connection->prepare("SELECT * FROM bilete where valid=1 and id_google='".$mac."'");

                $stmt ->execute();
                //$stmt -> bind_result($id, $latitudine, $longitudine, $nume, $tel, $data, $link);
                $stmt -> bind_result($id, $email, $Nume, $Clasa, $Orgunit, $Motiv, $Obs, $data1, $data2, $ora1, $ora2, $id_google, $valid);
                //id 	id_sofer 	id_client 	nume 	tel 	nr_masina 	mac 	lat 	lon 	adresa 	link 	status 	obs data_comanda    data_start 	data_client 	data_stop   banned  
                $products = array();

                while($stmt ->fetch()){

                    $temp = array();
                    
                    $temp['id'] = $id;
                    $temp['email'] = $email;
                    $temp['Nume'] = $Nume;
                    $temp['Clasa'] = $Clasa;
                    $temp['Orgunit'] = $Orgunit;
                    $temp['Motiv'] = $Motiv;
                    $temp['Obs'] = $Obs;
                    $temp['data1'] = $data1;
                    $temp['data2'] = $data2;
                    $temp['ora1'] = $ora1;
                    $temp['ora2'] = $ora2;
                    $temp['id_google'] = $id_google;
                    $temp['valid'] = $valid;
                    

                    array_push($products,$temp);
                    }

                    echo json_encode($products);
                
            break;
            
            case 'rate':
               
                $sql = "SELECT `data_stop`, `nota` FROM `log_comenzi` where `mac`='".$mac."' ORDER BY `data_stop` DESC LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt ->execute();
                //$stmt -> bind_result($id, $latitudine, $longitudine, $nume, $tel, $data, $link);
                $stmt -> bind_result($data_stop, $nota);
                 
                $products = array();

                while($stmt ->fetch()){

                    $temp = array();
                    
                    $temp['data_stop'] = $data_stop;
                    $temp['nota'] = $nota;
                    
                    array_push($products,$temp);
                    }

                    echo json_encode($products);

            break;
            }
        
        
    $db_connection->close();

$_POST['opt'] = '';
$_POST['key'] = '';
$_POST['mac'] = '';
?>