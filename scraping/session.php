<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
//raccolgo i dati
$cittaPart = trim($_POST['cittaPart']);
$cittaDest = trim($_POST['cittaDest']);
$data = trim($_POST['data']);

$ora = trim($_POST['ora']);
$chkEventi = trim($_POST['chkEventi']);
//salvo i dati
$_SESSION['cittaPart'] = $cittaPart;
$_SESSION['cittaDest'] = $cittaDest;
$_SESSION['data'] = $data;
$_SESSION['ora'] = $ora;

if(!empty($_POST['chkEventi'])) {
    $_SESSION['chkEventi'] = true;
} 
else {
    $_SESSION['chkEventi'] = false;
}

//invio i dati
header("location: Acapt.php");
?>

