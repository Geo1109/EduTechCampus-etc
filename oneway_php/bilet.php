<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

//session_start();
require_once 'db_connection.php';
//mysqli_report(MYSQLI_REPORT_ALL);
//mysqli_report(MYSQLI_REPORT_ERROR);

if(!isset($_SESSION['logat'])) $_SESSION['logat']='';
if(!isset($_SESSION['admin'])) $_SESSION['admin']='';
if(!isset($_SESSION['org_unit'])) $_SESSION['org_unit']='';
if(!isset($_SESSION['login_id'])) $_SESSION['login_id']='';
if(!isset($_SESSION['full_name'])) $_SESSION['full_name']='';
if(!isset($_SESSION['email'])) $_SESSION['email']='';
if(!isset($_SESSION['profile_pic'])) $_SESSION['profile_pic']='';
if(!isset($_GET['opt'])) $_GET['opt']='';

if(!isset($_SESSION['cnp'])) $_SESSION['cnp']='';
if(!isset($_SESSION['rang'])) $_SESSION['rang']='';
if(!isset($_SESSION['nivel'])) $_SESSION['nivel']='';
if(!isset($_SESSION['clasa'])) $_SESSION['clasa']='';
if(!isset($_SESSION['org_unit_google'])) $_SESSION['org_unit_google']='';

if(!isset($_POST['fmotiv'])) $_POST['fmotiv']='';
if(!isset($_POST['fobs'])) $_POST['fobs']='';
if(!isset($_POST['data1'])) $_POST['data1']='';
if(!isset($_POST['data2'])) $_POST['data2']='';
if(!isset($_POST['ora1'])) $_POST['ora1']='';
if(!isset($_POST['ora2'])) $_POST['ora2']='';


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



//if(!isset($_POST['activ'])) $_POST['activ'] = '';
//if(!isset($_POST['nume'])) $_POST['nume'] = '';
//if(!isset($_POST['email'])) $_POST['email'] = '';

//print "aa".$_SESSION['logat']."login=".$_SESSION['login_id'];

if($_SESSION['logat'] != "Adevarat" || $_SESSION['login_id'] ==''){
//if($_SESSION['logat'] != "Adevarat" ){
    echo "Trebuie sa fi autentificat cu lmvineu.ro! <br>";
    echo '<br>Redirectionare la <a class="login-btn" href="login.php">Login</a>
    <p id="wait"></p>';
   print' 
    <script>
    var div = document.getElementById(\'wait\');
    var counter = 0;
    var waitSecs = 8;
    var refreshId = setInterval(function () {
      ++counter;
      div.innerHTML = div.innerHTML + \'&#x25cf;\'
      if(counter == waitSecs){
        clearInterval(refreshId);
        top.location.href=\'login.php\'
      }
    }, 1000);
    </script>
    ';
    //header('Location: login.php');
    //exit;
}else{
include "head.php";
include "menu.php";
/*
if($_SESSION['rang']=='elev')
  {
include "menu_elev.php";
  }else if($_SESSION['rang']=='prof'){
    include "menu_prof.php";
  }else{
    include "menu.php";
  }
*/
print '<div class="content">';
switch($_GET['opt']){
  case 'send':
    //	id 	Nume 	Clasa 	Orgunit 	Motiv 	Obs 	data1 	data2 	ora1 	ora2 	id_google 	valid
    $sql = "INSERT INTO bilete (email, Nume, Clasa, Orgunit, Motiv, Obs, data1, data2, ora1, ora2, id_google) VALUES ('".$_SESSION['email']."', '".$_SESSION['full_name']."', '".$_SESSION['clasa']."', '".$_SESSION['org_unit_google']."', '".$fmotiv."', '".$fobs."', '".$data1."', '".$data2."',  '".$ora1."', '".$ora2."','".$_SESSION['login_id']."')";
    //print $sql;

    if (mysqli_query($db_connection, $sql)) {
        echo "Comanda trimisa cu succes";
    } else {
        //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        echo "Error: ". mysqli_error($db_connection);
    }
  break;
  case 'validare':
    if(!isset($_POST['activ']))  {
      print "<h2>Niciun elev selectat!</h2></div>";
    } else {
      $i=0;

      for($i=0;$i<count($_POST['activ']);$i++){
          $activ=$_POST['activ'][$i]; 
          $id_db=$_POST['id_db'][$activ];
          $email=$_POST['email'][$activ];
          
          $sql = "UPDATE bilete SET valid=1 where email='".$email."' and id='".$id_db."'";
          //print $sql;

          if (mysqli_query($db_connection, $sql)) {
              echo "Ok: "; print $id_db." ";
          } else {
              //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
              echo "Error: ". mysqli_error($conn);
          }

          
          //$j=$i+1;
    }
  }
  break;
  default:
  //$_SESSION['rang']='elev';
  if($_SESSION['rang']=='elev')
  {
  print'
  <form action="/google_login/bilet.php?opt=send" method="post">
    <label for="fname">Motivul plecarii</label><br>
    <input type="text" id="fmotiv" name="fmotiv" placeholder="Introdu pentru ce iti trebuie"><br>
    <label for="fname">Ora plecarii</label><br>
    <input type="date" name="data1" id="data1" value="'.date('Y-m-d').'"/>
    <input type="time" name="ora1" id="ora1" value="00:00:01"/>
    <br>
    <label for="fname">Ora sosirii</label><br>
    <input type="date" name="data2" id="data2" value="'.date('Y-m-d').'"/>
    <input type="time" name="ora2" id="ora2" value="00:00:01"/>
    <br>
    <label for="fname">Observatii</label><br>
    <input type="text" id="fobs" name="fobs" placeholder="Introdu obs"><br>
    <br>
    <input type="submit" value="Trimite">
  </form> 
  ';
  }else{

        $sql="SELECT * FROM `diriginti` WHERE `mail`='".$_SESSION['email']."'";
        //print $sql;
        $result = mysqli_query($db_connection, $sql);
        
        $org_unit='';
        if(mysqli_num_rows($result) > 0){
            
            while ($row = mysqli_fetch_row($result)) {
                //printf ("%s (%s)\n", $row[0], $row[1]);
              
            //$org_unit="'/Elevi/Liceal/Clasa a XII-a A'";
            $org_unit="'/Elevi/".$row[4]."/".$row[2]."'";
            print $org_unit;
            }
            
              //print_r($list_users);
           

        
        //print '<div class="content">';
        //print $org_unit;
        print '<form action="bilet.php?opt=validare" id="form1" method="post">
              <input type="hidden" name="org_unit" value="'. $org_unit. '">';
        print'<table border=\'1\' style="width:100%;border: 2px solid black;border-collapse: collapse;"> 
                    <tr>
                        <th><input type="checkbox" onClick="toggle(this)" />Tot<br/></th>
                        <th>Nr.crt.</th>
                        <th>Id</th>
                        <th>Nume</th>
                        <th>Clasa</th>
                        <th>Motiv</th>
                        <th>Data1</th>
                        <th>Data2</th>
                        <th>Obs</th> 
                        <th>Valid</th>   
                        
                    </tr>';
                    $i=0;
        
        $sql="SELECT * FROM `bilete` WHERE `Orgunit`=".$org_unit." and `valid`= 0";
        //print $sql;
        $result = mysqli_query($db_connection, $sql);

        if(mysqli_num_rows($result) > 0){
            
            while ($row = mysqli_fetch_row($result)) {
                //printf ("%s (%s)\n", $row[0], $row[1]);
              
            
           // print $org_unit;
            $j=$i+1;
              //printf("%s (%s) %s\n", $user->getPrimaryEmail(), $user->getName()->getFullName(),$user->getorgUnitPath());
              //id	email	Nume	Clasa	Orgunit	Motiv	Obs	data1	data2	ora1	ora2	id_google	valid
              print '<input type="hidden" name="id_db[]" value="'. $row[0]. '">';
              print '<input type="hidden" name="email[]" value="'. $row[1]. '">';
              print'
                    <tr>
                        <td><input type="checkbox" class="tocheck" name="activ[]" value="'.$i.'" ></td>
                        <td>'.$j.'</td>
                        <td>'.$row[0].'</td>
                        <td>'.$row[2].'</td>
                        <td>'.$row[3].'</td>
                        <td>'.$row[5].'</td>
                        <td>'.$row[7].' <span style="color:red">'.$row[9].'</span></td>
                        <td>'.$row[8].' <span style="color:red">'.$row[10].'</span></td>
                        <td>'.$row[6].'</td>
                        <td>'.$row[12].'</td>
                        
                    </tr>';
                    
                    $i++;
                    
            }
        }
        print '<tr><td>
        <input type="submit" name="formSubmit" value="Trimite" />
        </td>
        </tr>
        </table></form>';
        
        }else{
          print 'Nu esti diriginte!';
        }



  }//else elev
}

//print '<table><tr><td>aa</td><td>bb</td></tr>  <tr><td>cc</td><td>dd</td></tr></table>';
print '</div>';
include "footer.php";
}
?>