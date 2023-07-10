<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

//session_start();
require_once 'db_connection.php';
//mysqli_report(MYSQLI_REPORT_ALL);
//mysqli_report(MYSQLI_REPORT_ERROR);

if(!isset($_POST['fmotiv'])) $_POST['fmotiv']='';
if(!isset($_POST['fobs'])) $_POST['fobs']='';
if(!isset($_POST['data1'])) $_POST['data1']='';
if(!isset($_POST['data2'])) $_POST['data2']='';
if(!isset($_POST['ora1'])) $_POST['ora1']='';
if(!isset($_POST['ora2'])) $_POST['ora2']='';
if(!isset($_POST['login_id'])) $_POST['login_id']='';
if(!isset($_POST['email'])) $_POST['email']='';
if(!isset($_POST['full_name'])) $_POST['full_name']='';
if(!isset($_GET['opt'])) $_GET['opt']='';


$opt = $_GET['opt'];
$opt=htmlspecialchars($opt);

$email = $_POST['email'];
$email=htmlspecialchars($email);

$full_name = $_POST['full_name'];
$full_name=htmlspecialchars($full_name);

$login_id = $_POST['login_id'];
$login_id=htmlspecialchars($login_id);

$fmotiv = $_POST['fmotiv'];
$fmotiv=htmlspecialchars($fmotiv);

$fobs = $_POST['fobs'];
$fobs=htmlspecialchars($fobs);

$data1 = $_POST['data1'];
$data1=htmlspecialchars($data1);

$data2 = $_POST['data2'];
$data2=htmlspecialchars($data2);

$ora1 = $_POST['ora1'];
$ora1=htmlspecialchars($ora1);

$ora2 = $_POST['ora2'];
$ora2=htmlspecialchars($ora2);

require_once '../biblioteci/google-api-php-client--PHP7.4/vendor/autoload.php';
$key_file_location = '/var/php_keys/php-login-365307-69f13304118c.json';
$delegatedAdmin = 'admin@lmvineu.ro';
$scopes = array(
    'https://www.googleapis.com/auth/admin.directory.user',
    'https://www.googleapis.com/auth/admin.directory.orgunit',
    'https://www.googleapis.com/auth/admin.directory.group'
);

$client = new Google_Client();
$client->setApplicationName("This is the name");
$client->setAuthConfig($key_file_location);
$client->setSubject($delegatedAdmin);
$client->setScopes($scopes);

$client->setAccessType('offline');
//$client->setPrompt('select_account consent');

$service_users = new Google\Service\Directory($client);

//$user_elev=$_SESSION['email'];
//$user_elev="test.pass@lmvineu.ro";
//$user_elev="marius.crainic@lmvineu.ro";
//$user_elev="darius.neamt@lmvineu.ro";
$user_elev=$email;
$user = $service_users->users->get($user_elev);
$nivel='';
  $clasa='';
  $rang='';
  $cnp='';
  $org_unite=$user->getorgUnitPath();
  $org_unit2 = get_string_between($org_unite, '/', '/');
  //print $org_unit2;
  if($org_unit2=="Elevi" || $org_unit2=="Profesor")
        {
              $val=$user->getExternalIds()[0];
              $cnp = get_string_between($val['value'], '[', ']');
              $rang = get_string_between($val['value'], '(', ')');
              if($rang == "elev")
              {
                  $nivel = get_string_between($org_unite, '/Elevi/', '/');
                  $clasa = substr($org_unite, strrpos($org_unite, "/") + 1);
                  
              }
        }else {
          $rang="Altul(admins)";
        }
  //print $org_unite;print"<br>";
  //print $rang;print"<br>";
  //print $nivel;print"<br>";
  //print $clasa;print"<br>";






switch($opt){
    case 'send':
      //	id 	Nume 	Clasa 	Orgunit 	Motiv 	Obs 	data1 	data2 	ora1 	ora2 	id_google 	valid
      $sql = "INSERT INTO bilete (email, Nume, Clasa, Orgunit, Motiv, Obs, data1, data2, ora1, ora2, id_google) VALUES ('".$email."', '".$full_name."', '".$clasa."', '".$org_unite."', '".$fmotiv."', '".$fobs."', '".$data1."', '".$data2."',  '".$ora1."', '".$ora2."','".$login_id."')";
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