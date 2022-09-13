<?php

session_start();
$cittaPart = $_SESSION['cittaPart'];
$cittaArr = $_SESSION['cittaDest'];
$data = $_SESSION['data'];
$json_string = "https://api.ferroviedelgargano.com/search/1/" . $cittaPart . "/" . $cittaArr . "/" . $data . "/" . $data;
//nome del file di output
$name = "orariFerGar";

$jsondata = file_get_contents($json_string);

// Store JSON data in a PHP variable
$json = file_get_contents($json_string);

//var_dump(json_decode($json, true));
$solutions = json_decode($json);


//TODO AUMENTARE LA DATA DI 2H E INSERIRE IL RISULTATO ALL'INTERNO DI UN CODICE XML
$handle = fopen("../output/$name.xml", "w");
fwrite($handle, "<orario>\n");

fwrite($handle, "<viaggio>\n");

for ($i = 0; $i < sizeof($solutions); $i++) {

//prelevare la prima soluzione relativa alla partenza
    $oraPartenza = date("H:i", strtotime($solutions[$i]->solutionDates[0]->trips[0]->departureDateTime . " + 2 hours"));

//prelevare l'ultima soluzione relativa all'arrivo
    $ultima_soluzione = sizeof($solutions[$i]->solutionDates[0]->trips);
    $oraArrivo = date("H:i", strtotime($solutions[$i]->solutionDates[0]->trips[$ultima_soluzione - 1]->arrivalDateTime . " + 2 hours"));
//prelevare il prezzo
    $prezzo = $solutions[$i]->solutionDates[0]->calculatedPrice;
    fwrite($handle, "<partenza>\n");
    fwrite($handle, "<citta>" . $cittaPart . "</citta>\n");
    fwrite($handle, "<ora>" . $oraPartenza . "</ora>\n");
    fwrite($handle, "</partenza>\n");
    fwrite($handle, "<arrivo>\n");
    fwrite($handle, "<citta>" . $cittaArr . "</citta>\n");
    fwrite($handle, "<ora>" . $oraArrivo . "</ora>\n");
    fwrite($handle, "</arrivo>\n");
    fwrite($handle, "<note>Prezzo: " . $prezzo . " euro</note>\n");
    fwrite($handle, "<compagnia>Ferrovie del Gargano</compagnia>\n");
    fwrite($handle, "<imgcompagnia>http://www.ferroviedelgargano.com/Portals/0/logo_fdg_white.jpg?ver=2018-05-02-122004-383</imgcompagnia>\n");

}
fwrite($handle, "</viaggio>\n");
fwrite($handle, "</orario>\n");

fclose($handle);
header("Location: CityNews.php");

