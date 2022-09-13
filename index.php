<?php
include 'include/datalist.php';
$datenow = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="it">

    <head>
        <title>SoftBus - Viaggia informato</title>

        <!-- Favicon -->
        <link rel="icon" href="images/favicon.png" type="image/png"/>

        <!-- Styles -->
        <link rel='stylesheet' href='assets/css/split.css' type='text/css' media='screen' />
        <meta name="viewport" content="width=device-width,initial-scale=1" />

        <style>
            .box-text {
                color: #848d96;
                font-size: 70%;
            }
            .input-travel {
                width: 100%;
                padding: 12px 20px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }

            .input-travel-small {
                padding: 12px 20px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }

            .btn-submit {
                width: 100%;
                background-color: #4CAF50;
                color: white;
                padding: 14px 20px;
                margin: 8px 0;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            .btn-submit:hover {
                background-color: #45a049;
            }

            .box {
                border-radius: 5px;
                background-color: #f2f2f2;
                padding: 20px;
            }
        </style>

    </head>

    <body id="fullsingle" class="page-template-page-fullsingle-split">
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

        <div class="fs-split">

            <!-- Self-Hosted Video Side -->
            <div class="split-image split-video">
                <video class="video" muted autoplay loop src="videos/background.mp4"></video>	
            </div>

            <!-- Content Side -->
            <div class="split-content">

                <div class="split-content-vertically-center">

                    <div class="split-intro">

                        <span class="tagline">SoftBus</span>

                    </div>

                    <div class="split-bio">
                        <p>Viaggia informato e scopri gli eventi attorno a te</p><br>

                        <div class="box">
                            <form name="form_login" method="post" action="scraping/session.php">
                                <label for="cittaPart">Partenza</label>
                                <input class="input-travel" list="citta" name="cittaPart" placeholder="Da:" required>

                                <label for="cittaDest">Arrivo</label>
                                <input class="input-travel" list="citta" name="cittaDest" placeholder="A:" required>

                                <label for="data">Data</label>
                                <input class="input-travel-small" type="date" name="data" value="<?php echo $datenow?>" required> &nbsp;&nbsp;&nbsp;&nbsp;    
                                <label for="data">Ora</label>
                                <input class="input-travel-small" type="time" name="ora" value="07:00" required>
                                <h3 class="box-text"><input type="checkbox" name="chkEventi" id="chkEventi">&nbsp;mostra solo eventi in centro</h3>

                                <button class="btn-submit">Vai</button>
                            </form>
                        </div>
                    </div>	
                    <br><br>
                    <div class="split-lists">

                        <div class="split-list">

                            <h3>Video</h3>

                            <ul>
                                <li><a href="demo.php">Demo</a></li>
                                <li><font color="#031b31">.</font></li>
                            </ul>
                        </div>
                        <div class="split-list">
                            <h3>Download</h3>

                            <ul>
                                <li><a href="files/SoftBus.apk">Web App APK</a></li>
                                <li><a href="files/SoftBus-presentazione.pdf" target="blank">Presentazione</a></li>
                            </ul>

                        </div>

                    </div>

                    <div class="split-credit">

                        <p>Università degli studi di Salerno - Progetto di Integrazione Dati su Web<br>Quirito Antonio (mat. 0522500577)</p>

                    </div>		

                </div>

            </div>

        </div>

    </body>
</html>