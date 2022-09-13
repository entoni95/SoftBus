<?php
session_start();
$cittaPart =  $_SESSION['cittaPart'];
$cittaArr =  $_SESSION['cittaDest'];

$linkCittaPart = str_replace(' ', '-', $cittaPart);
$linkCittaPart = strtolower($linkCittaPart);

$xpathCittaArr = str_replace(' ', '-', $cittaArr);
$xpathCittaArr = strtolower($xpathCittaArr);

header('Content-type: text/plain; charset=utf-8');
//nome del file di output
$name = "orariAcapt";

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

$link = "https://www.acapt.it/percorsi_e_orari/".$linkCittaPart;


$page = file_get_contents($link);
@$doc = new DOMDocument();
@$doc->loadHTML($page);

$xpath = new DomXPath($doc);


$nodeList = $xpath->query("//div[@id='$xpathCittaArr']//td[@class='vc_table_cell']");

$length = count($nodeList);
$handle = fopen("../output/$name.xml", "w");

$node = $nodeList->item(1);
$durata = $node->nodeValue;
$durata = str_replace('tempo di percorrenza ', '', $durata);
$durata = str_replace('’', '', $durata);

fwrite($handle, "<orario>\n");
fwrite($handle, "<viaggio>\n");

for ($i = 4; $i < $length; $i++) : //da i=0 a i=3 dati non rilevanti (tranne la durata che è stata estratta prima)
    $node = $nodeList->item($i);
    if ($i % 2 == 0) { //i nodi pari contengono gli orari
        $oraPart = date("H:i", strtotime($node->nodeValue));
        $oraArr = date('H:i', strtotime($oraPart. " + $durata minutes")); //l'ora di arrivo è la somma dell'ora di partenza più la durata del viaggio
        fwrite($handle, "<partenza>\n");
        fwrite($handle, "<citta>" . $cittaPart . "</citta>\n");
        fwrite($handle, "<ora>" . $oraPart . "</ora>\n");
        fwrite($handle, "</partenza>\n");
        fwrite($handle, "<arrivo>\n");
        fwrite($handle, "<citta>" . $cittaArr . "</citta>\n");
        fwrite($handle, "<ora>" . $oraArr . "</ora>\n");
        fwrite($handle, "</arrivo>\n");
    } else { //i nodi dispari contengono le note
        fwrite($handle, "<note>" . $node->nodeValue . "</note>\n");
        fwrite($handle, "<compagnia>Acapt</compagnia>\n");
        fwrite($handle, "<imgcompagnia>https://www.acapt.it/wp-content/uploads/2017/05/logo-acapt-2.png</imgcompagnia>\n");
    }
endfor;
fwrite($handle, "</viaggio>\n");
fwrite($handle, "\n</orario>");

fclose($handle);
header("Location: FerGargano.php");


