<?php
include 'include/datalist.php';

session_start();
$partenza = $_SESSION['cittaPart'];
$arrivo = $_SESSION['cittaDest'];
$data = date("d/m/Y", strtotime($_SESSION['data']));
$oraUtente = $_SESSION['ora'];
$chkEventi = $_SESSION['chkEventi'];

$doc1 = new DOMDocument();
$doc1->load('output/orariAcapt.xml');
$xp = new DOMXPath($doc1);

$doc2 = new DOMDocument();
$doc2->load('output/orariFerGar.xml');

$test1 = $doc2->getElementsByTagName('viaggio');
foreach ($test1 as $testItem) {
    $id = $testItem->getAttribute('external_id');
    if ($xp->evaluate("count(//test[@external_id='{$id}'])") == 0) {
        $copyNode = $doc1->importNode($testItem, true);
        $doc1->documentElement->appendChild($copyNode);
    }
}

$xmlOrari = new SimpleXMLElement($doc1->saveXML());

$xmlEventi = simplexml_load_file("output/eventi.xml");
?>

<!DOCTYPE html>
<html lang="it">

    <head>
        <title><?php echo $partenza ?> - <?php echo $arrivo ?> | SoftBus - Viaggia informato</title>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- Favicon -->
        <link rel="icon" href="images/favicon.png" type="image/png"/>

        <!-- Styles -->
        <link rel='stylesheet' href='assets/css/split.css' type='text/css' media='screen' />
        <meta name="viewport" content="width=device-width,initial-scale=1" />

        <style>
            body {font-family: Arial, Helvetica, sans-serif;}
            * {box-sizing: border-box;}

            /* Button used to open the contact form - fixed at the bottom of the page */
            .open-button {
                background-color: #555;
                color: white;
                padding: 16px 20px;
                border: none;
                cursor: pointer;
                opacity: 0.8;
                position: fixed;
                bottom: 23px;
                right: 28px;
                width: 280px;
            }

            /* The popup form - hidden by default */
            .form-popup {
                display: none;
                position: fixed;
                bottom: 0;
                right: 15px;
                border: 3px solid #f1f1f1;
                z-index: 9;
            }

            /* Add styles to the form container */
            .form-container {
                max-width: 300px;
                padding: 10px;
                background-color: white;
            }

            /* Full-width input fields */
            .form-container .input-travel {
                width: 100%;
                padding: 15px;
                margin: 5px 0 22px 0;
                border: none;
                background: #f1f1f1;
            }

            /* When the inputs get focus, do something */
            .form-container .input-travel:focus {
                background-color: #ddd;
                outline: none;
            }

            /* Set a style for the submit/login button */
            .form-container .btn {
                background-color: #4CAF50;
                color: white;
                padding: 16px 20px;
                border: none;
                cursor: pointer;
                width: 100%;
                margin-bottom:10px;
                opacity: 0.8;
            }

            /* Add a red background color to the cancel button */
            .form-container .cancel {
                background-color: red;
            }

            /* Add some hover effects to buttons */
            .form-container .btn:hover, .open-button:hover {
                opacity: 1;
            }
        </style>

    </head>

    <body id="fullsingle" class="page-template-page-fullsingle-split">

        <div class="fs-split">
            <!-- Orari Side -->
            <div class="split-image">
                <div class="split-image-vertically-center">
                    <p>Orari tratta <?php echo $partenza ?> - <?php echo $arrivo ?></p>
                    <p><?php echo $data ?></p>
                    <br>
                    <div class="split-bio">
                        <?php
                        if ($xmlOrari != null) {
                            /* Relative paths also work... */
                            $compagnia = $xmlOrari->xpath('//viaggio/compagnia');
                            $imgcompagnia = $xmlOrari->xpath('//viaggio/imgcompagnia');
                            $cittaPart = $xmlOrari->xpath('//viaggio/partenza/citta');
                            $oraPart = $xmlOrari->xpath('//viaggio/partenza/ora');
                            $cittaArr = $xmlOrari->xpath('//viaggio/arrivo/citta');
                            $oraArr = $xmlOrari->xpath('//viaggio/arrivo/ora');
                            $note = $xmlOrari->xpath('//viaggio/note');

                            $length = count($oraArr);
                            if ($length != 0) {
                                for ($i = 0; $i < $length; $i++) :
                                    if ($oraPart[$i] >= $oraUtente) {
                                        ?>
                                        <img src='<?php echo $imgcompagnia[$i] ?>' width='<?php
                                        list($width, $height) = getimagesize($imgcompagnia[$i]);
                                        if ($width <= 200)
                                            $width = $width - 100;
                                        else
                                            $width = $width - 200;
                                        echo $width;
                                        ?>' alt='<?php echo $compagnia[$i] ?>'>
                                        <p><?php echo $cittaPart[$i] ?></p>
                                        <p><?php echo $oraPart[$i] ?></p>
                                        <p><?php echo $cittaArr[$i] ?></p>
                                        <p><?php echo $oraArr[$i] ?></p>
                                        <p><?php echo $note[$i] ?></p><br><br>
                                        <?php
                                    }
                                endfor;
                            } else {
                                echo'non sono stati trovati orari';
                            }
                        } else
                            echo 'Si è verificato un problema durante il caricamento degli orari';
                        ?>
                    </div>
                </div>
            </div>

            <!-- Eventi Side -->
            <div class="split-content">

                <div class="split-content-vertically-center">
                    <p>Cosa fare a <?php echo $arrivo ?></p>
                    <br><br>
                    <div class="split-bio">
                        <?php
                        if ($xmlEventi != null) {
                            /* Relative paths also work... */
                            $immagine = $xmlEventi->xpath('//evento/immagine');
                            $titolo = $xmlEventi->xpath('//evento/titolo');
                            $luogo = $xmlEventi->xpath('//evento/luogo');


                            $length = count($titolo);

                            if ($length != 0) {
                                for ($i = 0; $i < $length; $i++) :
                                    if ($chkEventi) {
                                        $isEvents = false;
                                        if (strpos($luogo[$i], 'Centro') !== false) {
                                            $isEvents = true;
                                            ?>
                                            <p>
                                                <?php
                                                if ($immagine[$i] != '') {
                                                    echo "<img src='$immagine[$i]' width='100' align='right'>";
                                                } else
                                                    echo "<img src='images/default.jpg' width='100' align='right'>";
                                                echo $titolo[$i];
                                                ?>
                                            </p>
                                            <br>
                                            <p>Luogo: <?php
                                                if ($luogo[$i] != '')
                                                    echo $luogo[$i];
                                                else
                                                    echo 'non disponibile'
                                                    ?>
                                            </p>
                                            <br>
                                            <p>———</p>
                                            <br><br> 
                                            <?php
                                        }
                                    } else {
                                        $isEvents = true;
                                        ?>
                                        <p>
                                            <?php
                                            if ($immagine[$i] != '') {
                                                echo "<img src='$immagine[$i]' width='100' align='right'>";
                                            } else
                                                echo "<img src='images/default.jpg' width='100' align='right'>";
                                            echo $titolo[$i];
                                            ?>
                                        </p>
                                        <br>
                                        <p>Luogo: <?php
                                            if ($luogo[$i] != '')
                                                echo $luogo[$i];
                                            else
                                                echo 'non disponibile'
                                                ?>
                                        </p>
                                        <br>
                                        <p>———</p>
                                        <br><br> 
                                        <?php
                                    }
                                endfor;
                            } else {
                                $isEvents = -1;
                                echo'non sono stati trovati eventi in programma';
                            }
                            /* while(list( , $node) = each($partenze)) {
                              echo $node,"<br>";
                              }
                             */
                        } else{
                            $isEvents = -1;
                            echo 'Si è verificato un problema durante il caricamento degli eventi';
                            }
                        if (!$isEvents)
                                    echo'non sono stati trovati eventi in centro';
                        ?>
                    </div>

                </div>

            </div>

        </div>


        <!--popup window-->
        <datalist id="citta">
            <?php
            $length = count($datalist);
            if ($length != 0) {
                for ($i = 0; $i < $length; $i++) :
                    ?>
                    <option value="<?php echo $datalist[$i] ?>">
                        <?php
                    endfor;
                }
                ?>
        </datalist>
        <button class="open-button" onclick="openForm()">Nuovo viaggio</button>

        <div class="form-popup" id="myForm">
            <form name="form_login" class="form-container" method="post" action="scraping/session.php">
                <h1>Nuovo viaggio</h1>
                <input class="input-travel" list="citta" name="cittaPart" placeholder="Partenza:" required>

                <input class="input-travel" list="citta" name="cittaDest" placeholder="Arrivo:" required>

                <input class="input-travel" type="date" name="data" style="width: 60%;" value="<?php echo date("Y-m-d", strtotime($data)) ?>" required>
                <input class="input-travel" type="time" name="ora" style="width: 38%;" value="<?php echo $oraUtente ?>" required>
                <h4 style="color: #848d96;font-size: 60%;"><input type="checkbox" name="chkEventi" id="chkEventi">&nbsp;mostra solo eventi in centro</h4>
                <br>
                <button class="btn">Vai</button>
                <button type="button" class="btn cancel" onclick="closeForm()">Chiudi</button>
            </form>
        </div>

        <script>
            function openForm() {
                document.getElementById("myForm").style.display = "block";
            }

            function closeForm() {
                document.getElementById("myForm").style.display = "none";
            }
        </script>

    </body>
</html>



