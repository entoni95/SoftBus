<?php
session_start();
$cittaArr = $_SESSION['cittaDest'];

$eventDest = str_replace(' ', '-', $cittaArr);

$siteName = $eventDest;

if (($siteName == "Apricena") || ($siteName == "Bovino")|| ($siteName == "Cagnano-Varano")|| ($siteName == "Carpino")|| ($siteName == "Ischitella")|| ($siteName == "Lesina")|| ($siteName == "Lesina-Marina")|| ($siteName == "Manfredonia")|| ($siteName == "Monte-Sant'Angelo")|| ($siteName == "Peschici")|| ($siteName == "Poggio-Imperiale")|| ($siteName == "Rodi-Garganico")|| ($siteName == "San-Giovanni-Rotondo")|| ($siteName == "San-Marco-in-Lamis")|| ($siteName == "San-Severo")|| ($siteName == "San-Menaio")|| ($siteName == "San-Nicandro-Garganico")|| ($siteName == "Torremaggiore")|| ($siteName == "Torre-Mileto")|| ($siteName == "Vieste")){
    $siteName = "Foggia";
}
if ($siteName == "Roma-Fiumicino"){ 
    $siteName = "Roma";
    $eventDest = "Roma";
}
if (($siteName == "Rho") || ($siteName == "Nova-Milanese")) $siteName = "Milano";

header('Content-type: text/plain; charset=utf-8');
//nome del file di output
$name = "eventi";

if (isset($_POST["action"]) and $_POST["action"] == "upload") {
    if (isset($_FILES["user_file"])) {
        $file = $_FILES["user_file"];
        if ($file["error"] == UPLOAD_ERR_OK and is_uploaded_file($file["tmp_name"])) {
            $ext_ok = array('html', 'htm', 'php');
            $temp = explode(".", $file["name"]);
            $ext = end($temp);
//creo un nome univoco del file e lo inserisco in una cartella
            $newfilename = $name . '.' . end($temp);
            move_uploaded_file($file["tmp_name"], "../output/" . $newfilename);
        }
    }
}

$link = "http://www.".$siteName."today.it/notizie/".$eventDest."/eventi/";

$page = file_get_contents($link);
@$doc = new DOMDocument();
@$doc->loadHTML($page);

$xpath = new DomXPath($doc);

$nodeListImage = $xpath->query("//img/@srcset");
$nodeListTitle = $xpath->query("//h3[@class='story-heading heading-md']");
$nodeListPlace = $xpath->query("//span[@ class='location-label']");

$length = count($nodeListTitle);
if ($length != 0) $length = 6; //seleziono solo i primi 6 eventi

$handle = fopen("../output/$name.xml", "w");

fwrite($handle, "<eventi>\n");
for ($i = 0; $i < $length; $i++) :
    fwrite($handle, "<evento>\n");
    $nodeImage = $nodeListImage->item($i);
    fwrite($handle, "<immagine>" . $nodeImage->nodeValue . "</immagine>\n");
    $nodeTitle = $nodeListTitle->item($i);
    fwrite($handle, "<titolo>" . $nodeTitle->nodeValue . "</titolo>\n");
    $nodePlace = $nodeListPlace->item($i);
    fwrite($handle, "<luogo>" . $nodePlace->nodeValue . "</luogo>\n");
    fwrite($handle, "</evento>\n\n");

endfor;
fwrite($handle, "</eventi>\n");

fclose($handle);
header("Location: ../result.php");



