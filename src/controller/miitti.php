<?php
    function luoMiitti($formdata,$idryhma) {

        require_once(MODEL_DIR . 'miitti.php');
        require_once(MODEL_DIR . 'ryhma.php');

        $error = [];
        $nykyaika = new DateTime();
        $formaika = new DateTime($formdata['aika']);
        $formilmo = new DateTime($formdata['ilmoloppuu']);

        if (!isset($formdata['miitti']) || !$formdata['miitti']) {
            $error['miitti'] = "Anna miitin nimi.";
        } else {
            if (haeMiittiNimella($formdata['miitti'])) {
                $error['miitti'] = "Saman niminen miitti löytyy jo.";
            }
        }

        if (!isset($formdata['aika']) || !$formdata['aika']) {
            $error['aika'] = "Anna miitille ajankohta.";
        } else if ($formaika < $nykyaika) {
            $error['aika'] = "Aika ei voi olla menneisyydessä.";
        }

        if (!isset($formdata['paikka']) || !$formdata['paikka']) {
            $error['paikka'] = "Anna miitille paikka.";
        } 

        if (!isset($formdata['osoite']) || !$formdata['osoite']) {
            $error['osoite'] = "Anna miitille osoite.";
        }

        if (!isset($formdata['info']) || !$formdata['info']) {
            $error['info'] = "Anna miitin info tai lisätiedot.";
        } 

        if (!isset($formdata['ilmoloppuu']) || !$formdata['ilmoloppuu']) {
            $error['ilmoloppuu'] = "Anna ilmoittautumisen loppuaika.";
        } else if ($formilmo < $nykyaika) {
            $error['ilmoloppuu'] = "Aika ei voi olla menneisyydessä.";
        } else if ($formaika < $formilmo) {
            $error['ilmoloppuu'] = "Ilmoittautuminen ei voi loppua miitin alkamisen jälkeen.";
        }

        // Lisätään tiedot tietokantaan, jos edellä syötetyissä tiedoissa ei ollut virheitä,
        // eli error-taulukosta ei löydy virhetekstejä.

        if (!$error) {

            // Haetaan lomakkeen tiedot omiin muuttujiinsa.
            // Salataan salasana myös samalla.
            $miitti = $formdata['miitti'];
            $aika = $formdata['aika'];
            $aika = str_replace('T', ' ', $aika) . ':00';
            $paikka = $formdata['paikka'];
            $osoite = $formdata['osoite'];
            $ilmoloppuu = $formdata['ilmoloppuu'];
            $ilmoloppuu = str_replace('T', ' ', $ilmoloppuu) . ':00';
            $info = $formdata['info'];

            $idmiitti = lisaaMiitti($idryhma,$miitti,$aika,$paikka,$osoite,$ilmoloppuu,$info);

            // Palautetaan JSON-tyyppinen taulukko, jossa:
            // status   = Koodi, joka kertoo lisäyksen onnistumisen.
            //            Hyvin samankaltainen, kluin HTTP-protokollan vastauskoodi.
            //            200 = OK
            //            400 = Bad Request
            //            500 = Internal Server Error
            // id       = Lisätyn rivin id-tunniste.
            // formdata = Lisättävän henkilön lomakedata. Sama mitä annettiin syötteenä.
            // error    = Taulukko, jossa lomaketarkistuksessa esille tulleet virheet.

            if ($idmiitti) {

                    return [
                        "status" => 200,
                        "id"     => $idmiitti,
                        "data"   => $formdata
                    ];
                } else {
                    return [
                        "status" => 500,
                        "data"   => $formdata
                    ];
                }
            } else {
            
            // Lomaketietojen tarkistuksessa ilmeni virheitä.

            return [
                "status" => 400,
                "data"   => $formdata,
                "error"  => $error
            ];
        }
    }

    function muokkaaMiitti($formdata,$miitti) {        

        require_once(MODEL_DIR . 'miitti.php');

        $error = [];
        $nykyaika = new DateTime();
        $formaika = new DateTime($formdata['aika']);
        $formilmo = new DateTime($formdata['ilmoloppuu']);
        $formaikavertaus = str_replace('T', ' ', $formdata['aika']) . ':00';
        $formilmovertaus = str_replace('T', ' ', $formdata['ilmoloppuu']) . ':00';

        if ($formdata['miitti'] != $miitti['miitti']) {
            if (!isset($formdata['miitti']) || !$formdata['miitti']) {
                $error['miitti'] = "Anna miitin nimi.";
            } else {
                if (haeMiittiNimella($formdata['miitti'])) {
                    $error['miitti'] = "Saman niminen miitti löytyy jo.";
                }
            }
        }

        if ($formaikavertaus != $miitti['aika']) {
            if (!isset($formdata['aika']) || !$formdata['aika']) {
                $error['aika'] = "Anna miitille ajankohta.";
            } else if ($formaika < $nykyaika) {
                $error['aika'] = "Aika ei voi olla menneisyydessä.";
            }        
        }

        if ($formdata['paikka'] != $miitti['paikka']) {
            if (!isset($formdata['paikka']) || !$formdata['paikka']) {
                $error['paikka'] = "Anna miitille paikka.";
            }
        }

        if ($formdata['osoite'] != $miitti['osoite']) {
            if (!isset($formdata['osoite']) || !$formdata['osoite']) {
                $error['osoite'] = "Anna miitille osoite.";
            }
        }

        if ($formdata['info'] != $miitti['info']) {
            if (!isset($formdata['info']) || !$formdata['info']) {
                $error['info'] = "Anna miitin info tai lisätiedot.";
            }
        }

        if ($formilmovertaus != $miitti['ilmoloppuu']) {
            if (!isset($formdata['ilmoloppuu']) || !$formdata['ilmoloppuu']) {
                $error['ilmoloppuu'] = "Anna ilmoittautumisen loppuaika.";
            } else if ($formilmo < $nykyaika) {
                $error['ilmoloppuu'] = "Aika ei voi olla menneisyydessä.";
            } else if ($formaika < $formilmo) {
                $error['ilmoloppuu'] = "Ilmoittautuminen ei voi loppua miitin alkamisen jälkeen.";
            }
        }

        if (!$error) {

            $miittiUusi = $formdata['miitti'];
            $aika = $formdata['aika'];
            $aika = str_replace('T', ' ', $aika) . ':00';
            $paikka = $formdata['paikka'];
            $osoite = $formdata['osoite'];
            $ilmoloppuu = $formdata['ilmoloppuu'];
            $ilmoloppuu = str_replace('T', ' ', $ilmoloppuu) . ':00';
            $info = $formdata['info'];
            $id = $miitti['idmiitti'];

            $idmiitti = paivitaMiitti($miittiUusi,$aika,$paikka,$osoite,$ilmoloppuu,$info,$id);

            if ($idmiitti == 1) {

                return [
                    "status" => 200,
                ];
            } else if ($idmiitti == 0) {
                return [
                    "status" => 0,
                ];
            } else {
                return [
                    "status" => 500,
                    "data"   => $formdata,
                    "error"  => $error
                ];
            }
        } else {

            return [
                "status" => 400,
                "data"   => $formdata,
                "error"  => $error
            ];
        }
    }
    
    function lahetaKutsut($idryhma,$idmiitti,$formdata,$baseUrl) {

        require_once(MODEL_DIR . 'ryhma.php');
        
        $palautus = [];

        $url = 'https://' . $_SERVER['HTTP_HOST'] . $baseUrl . "/miitti?id=$idmiitti";
        $jasenet = haeJasenetIDlla($idryhma);
        $miittinimi = $formdata['miitti'];
        $aika = $formdata['aika'];
        $aika = str_replace('T', ' ', $aika) . ':00';
        $ryhma = haeRyhmaIDlla($idryhma);
        $ryhmanimi = $ryhma['ryhmanimi'];

        foreach ($jasenet as $jasen) {

            $email = $jasen['email'];
            $nimimerkki = $jasen['nimimerkki'];

            $kutsu = miittiKutsu($email,$nimimerkki,$url,$miittinimi,$aika,$ryhmanimi);
            
            if (!$kutsu) {  
                $palautus = [
                    "status" => 500
                ];
            } else {
                $palautus = [
                    "status" => 200
                ];
            }
        }

        return $palautus;

    }

    function miittiKutsu($email,$nimimerkki,$url,$miittinimi,$aika,$ryhmanimi)  {
        $message = "Hei $nimimerkki!\n\n" . 
                   "Ryhmääsi $ryhmanimi on luotu uusi miitti $miittinimi joka on $aika.\n" . 
                   "Käy lukemassa lisää alla olevasta linkistä.\n\n" . 
                   "$url\n\n" .

                   "Terveisin, MiittiPaja";
        return mail($email,'MiittiPaja uusi miitti ryhmässäsi',$message);
    }

    function tarkistaKommentti($kommenttidata,$id,$loggeduser) {

        require_once(MODEL_DIR . 'miitti.php');

        $error = [];

        if (!isset($kommenttidata['kommentti']) || !$kommenttidata['kommentti']) {
            $error['kommentti'] = "Et voi kommentoida tyhjää.";
        } else {
            if (haeKommentti($kommenttidata['kommentti'],$loggeduser['idhenkilo'],$id)) {
                $error['kommentti'] = "Sama kommentti löytyy jo samalta käyttäjältä samasta miitistä.";
            }
        }

        if (!$error) {

            $idmiitti = $id;
            $idhenkilo = $loggeduser['idhenkilo'];
            $nimimerkki = $loggeduser['nimimerkki'];
            $kommentti = $kommenttidata['kommentti'];

            $idkommentti = lisaaKommentti($idmiitti,$idhenkilo,$nimimerkki,$kommentti);

            if ($idkommentti) {

                    return [
                        "status" => 200,
                        "id"     => $idmiitti,
                        "data"   => $kommenttidata
                    ];
            } else {
                    return [
                        "status" => 500,
                        "data"   => $kommenttidata
                    ];
            }

        } else {
            
            return [
                "status" => 400,
                "data"   => $kommenttidata,
                "error"  => $error
            ];
        }
    }

?>