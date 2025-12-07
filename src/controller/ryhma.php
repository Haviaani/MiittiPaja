<?php
    function luoRyhma($adminemail,$loggeduser,$formdata,$baseUrl='') {
        // Tuodaan henkilö- ja ryhmä-mallin funktiot, joilla voidaan etsiä ja lisätä ryhmän tiedot tietokantaan.

        require_once(MODEL_DIR . 'henkilo.php');
        require_once(MODEL_DIR . 'ryhma.php');

        // Alustetaan virhetaulukko, joka palautetaan lopuksi joko tyhjänä tai virheillä täytettynä.

        $error = [];

        // Seuraavaksi tehdään lomaketietojen tarkistus. Tarkistusten periaate on jokaisessa kohdassa sama.
        // Jos kentän arvo ei täytä tarkistuksen ehtoja, niin error-taulukkoon lisätään virhekuvaus.
        // Lopussa error-taulukko on tyhjä, jos kaikki kentät menevät tarkistuksesta lävitse.

        // Tarkistetaan onko ryhmänimi määritelty ja uniikki.

        if (!isset($formdata['ryhmanimi']) || !$formdata['ryhmanimi']) {
            $error['ryhmanimi'] = "Anna ryhmälle nimi.";
        } else {
         if (haeRyhmaNimella($formdata['ryhmanimi'])) {
                $error['ryhmanimi'] = "Ryhmänimi on jo käytössä.";
            }
        }

        // Tarkistetaan onko ryhmäkuvaus määritelty.

        if (!isset($formdata['ryhmakuvaus']) || !$formdata['ryhmakuvaus']) {
            $error['ryhmakuvaus'] = "Anna ryhmälle kuvaus.";
        } 

        // Lisätään tiedot tietokantaan, jos edellä syötetyissä tiedoissa ei ollut virheitä,
        // eli error-taulukosta ei löydy virhetekstejä.

        if (!$error) {

            // Haetaan lomakkeen tiedot omiin muuttujiinsa.
            // Salataan salasana myös samalla.
            $ypid = $loggeduser['idhenkilo'];
            $ryhmanimi = $formdata['ryhmanimi'];
            $ryhmakuvaus = $formdata['ryhmakuvaus'];
            $jasenmaara = 1;
            $perustaja = $loggeduser['nimimerkki'];
            $perustajaemail = $loggeduser['email'];
            $email = $perustajaemail;

            // Lisätään ryhmä tietokantaan. Jos lisäys onnistui, tulee palautusarvona lisätyn ryhmän id-tunniste.

            $idryhma = lisaaRyhma($ypid,$ryhmanimi,$ryhmakuvaus,$jasenmaara,$perustaja,$perustajaemail);

            // Palautetaan JSON-tyyppinen taulukko, jossa:
            // status   = Koodi, joka kertoo lisäyksen onnistumisen.
            //            Hyvin samankaltainen, kluin HTTP-protokollan vastauskoodi.
            //            200 = OK
            //            400 = Bad Request
            //            500 = Internal Server Error
            // id       = Lisätyn rivin id-tunniste.
            // formdata = Lisättävän ryhmän lomakedata. Sama mitä annettiin syötteenä.
            // error    = Taulukko, jossa lomaketarkistuksessa esille tulleet virheet.

            // Tarkistetaan onnistuiko ryhmän tietojen lisääminen. Jos idryhma-muuttujassa on positiivinen arvo,
            // onnistui rivin lisääminen. Muuten lisäämisessä ilmeni ongelma.

            if ($idryhma) {

                // Luodaan ryhmälle aktivointiavain ja muodostetaan aktivointilinkki.

                require_once(HELPERS_DIR . "secret.php");
                $avain = generateActivationCode($email);
                $url = 'https://' . $_SERVER['HTTP_HOST'] . $baseUrl . "/vahvista_ryhma?key=$avain";

                // Päivitetään aktivointiavain tietokantaan ja lähetetään adminille sähköpostia.
                // Jos tämä onnistui, niin palautetaan palautusarvona tieto ryhmän onnistuneesta luomisesta
                // ja pyydetään odottamaan, että admin hyväksyy ryhmän.
                // Muuten palautetaan virhekoodi, joka ilmoittaa, että jokin lisäyksessä epäonnistui

                if (paivitaHyvavain($avain,$ryhmanimi) && lahetaRyhmaAnomus($adminemail,$url,$perustaja,$ryhmanimi,$ryhmakuvaus)) {
                    return [
                        "status" => 200,
                        "id"     => $idryhma,
                        "data"   => $formdata
                    ];
                } else {
                    return [
                        "status" => 500,
                        "data"   => $formdata
                    ];
                }
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

    function muokkaaRyhma($adminemail,$formdata,$ryhma,$baseUrl='') {
       
        require_once(MODEL_DIR . 'ryhma.php');

        $error = [];

        if ($formdata['ryhmanimi'] != $ryhma['ryhmanimi']) {
            if (!isset($formdata['ryhmanimi']) || !$formdata['ryhmanimi']) {
                $error['ryhmanimi'] = "Anna ryhmälle nimi.";
            } else {
            if (haeRyhmaNimella($formdata['ryhmanimi'])) {
                    $error['ryhmanimi'] = "Ryhmänimi on jo käytössä.";
                }
            }
        }

        if ($formdata['ryhmakuvaus'] != $ryhma['ryhmakuvaus']) {
            if (!isset($formdata['ryhmakuvaus']) || !$formdata['ryhmakuvaus']) {
                $error['ryhmakuvaus'] = "Anna ryhmälle kuvaus.";
            } 
        }

        if (!$error) {

            $ryhmanimi = $formdata['ryhmanimi'];
            $ryhmakuvaus = $formdata['ryhmakuvaus'];
            $id = $ryhma['idryhma'];
            $perustaja = $ryhma['perustaja'];
            $email = $ryhma['perustajaemail'];

            $idryhma = lisaaMuokattuRyhma($ryhmanimi,$ryhmakuvaus,$id);

            if ($idryhma) {

                require_once(HELPERS_DIR . "secret.php");
                $avain = generateActivationCode($email);
                $url = 'https://' . $_SERVER['HTTP_HOST'] . $baseUrl . "/vahvista_ryhma_muokkaus?key=$avain";

                if (paivitaHyvavain($avain,$ryhmanimi) && lahetaRyhmaMuokkaus($adminemail,$url,$perustaja,$ryhmanimi,$ryhmakuvaus)) {
                    return [
                        "status" => 200,
                    ];
                } else {
                    return [
                        "status" => 500,
                        "data"   => $formdata
                    ];
                }
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

    function hyvaksyRyhma($idhenkilo,$idryhma,$perustaja,$perustajaemail,$ryhmanimi) {

        require_once(MODEL_DIR . 'henkilo.php');

        if (lisaaHenkiloRyhmaan($idhenkilo,$idryhma) &&      
            lisaaHenkiloYllapitajiin($idhenkilo,$idryhma)) {            
            if (ryhmaHyvaksytty($perustaja,$perustajaemail,$ryhmanimi)) {
                return [
                    "status" => 200
                ];
            } else {
                return [
                    "status" => 500
                ];
            }
        }
    }

    function hyvaksyRyhmaMuokkaus($idryhma,$perustaja,$perustajaemail,$ryhmanimi) {

        require_once(MODEL_DIR . 'ryhma.php');

        $ryhma = haeRyhmaIDlla($idryhma);
        $uusinimi = $ryhma['uusinimi'];
        $uusikuvaus = $ryhma['uusikuvaus'];

        if (paivitaRyhma($uusinimi,$uusikuvaus,$idryhma)) {     
            if (tyhjennaRyhmaPaivitystieto($idryhma)) {        
                if (muokkausHyvaksytty($perustaja,$perustajaemail,$ryhmanimi)) {
                    return [
                        "status" => 200
                    ];
                } else {
                    return [
                        "status" => 500
                    ];
                }
            } else {
                return [
                    "status" => 500
                ];
            }
        } else {
            return [
                "status" => 500
            ];
        }
    }


    function jasenHakemus($idhenkilo,$nimimerkki,$email,$idryhma,$ryhmanimi,$perustaja,$perustajaemail,$baseUrl='') {

        require_once(HELPERS_DIR . "secret.php");
        require_once(MODEL_DIR . "ryhma.php");
        
        $avain = generateActivationCode($email);
        $url = 'https://' . $_SERVER['HTTP_HOST'] . $baseUrl . "/hyvaksytty_ryhmaan?key=$avain";

        if (hyvaksyLiittyminen($ryhmanimi,$perustaja,$perustajaemail,$nimimerkki,$url)) {
            if(lisaaHakemus($idhenkilo,$nimimerkki,$email,$idryhma,$avain)) {
                return [
                    "status" => 200
                ];
            } else {
                return [
                    "status" => 500
                ];  
            }
        } else {
            return [
                "status" => 500
            ];
        }
    }

    function hakemusHyvaksytty($idhenkilo,$nimimerkki,$email,$idryhma,$ryhmanimi,$perustaja) {

        require_once(MODEL_DIR . "henkilo.php");
        require_once(MODEL_DIR . "ryhma.php");

        if (lisaaHenkiloRyhmaan($idhenkilo,$idryhma) &&      
            lisaaHenkiloYllapitajiin($idhenkilo,$idryhma)) {            
            if (liittyminenHyvaksytty($nimimerkki,$email,$ryhmanimi,$perustaja)) {
                if (lisaaJasenmaara($idryhma)) {
                    return [
                        "status" => 200
                    ];
                } else {
                    return [
                        "status" => 500
                    ];
                }
            } else {
                return [
                    "status" => 500
                ];
            }
        } else {
            return [
            "status" => 500
            ];
        }
    }

    function poistaRyhmastaIDlla($idryhma,$idhenkilo) {

        require_once(MODEL_DIR . "henkilo.php");
        require_once(MODEL_DIR . "ryhma.php");

        if (poistaIDllaRyhmasta($idryhma,$idhenkilo)) {
            if (vahennaJasenmaara($idryhma)) {
                return [
                    "status" => 200
                ];
            } else {
                return [
                    "status" => 500
                ];
            }
        } else {
            return [
            "status" => 500
            ];
        }
    }

    function lahetaRyhmaAnomus($adminemail,$url,$perustaja,$ryhmanimi,$ryhmakuvaus) {
        $message = "Hei!\n\n" . 
                   "Käyttäjä $perustaja on luonut uuden ryhmän.\n" . 
                   "Käy hyväksymässä ryhmä klikkaamalla linkkiä.\n\n" . 
                   "$url\n\n" .
                   "Luotu ryhmä: $ryhmanimi\n" .
                   "Ryhmän kuvaus: $ryhmakuvaus\n\n" .
                   "Terveisin, MiittiPaja";
        return mail($adminemail,'MiittiPaja ryhmän aktivoimislinkki',$message);
    }

    function lahetaRyhmaMuokkaus($adminemail,$url,$perustaja,$ryhmanimi,$ryhmakuvaus) {
        $message = "Hei!\n\n" .
                   "Käyttäjä $perustaja on muokannut ryhmäänsä.\n" .
                   "Käy hyväksymässä muutokset klikkaamalla linkkiä.\n\n" .
                   "$url\n\n" .
                   "Uusi nimi: $ryhmanimi\n" .
                   "Uusi kuvaus: $ryhmakuvaus\n\n" .
                   "Terveisin, MiittiPaja";
        return mail($adminemail,'MiittiPaja ryhmän muokkauksen hyväksymislinkki',$message);
    }

    function ryhmaHyvaksytty($perustaja,$perustajaemail,$ryhmanimi) {
        $message = "Hei $perustaja!\n\n" .
                   "Ylläpito on hyväksynyt ryhmäsi.\n" .
                   "Ryhmä $ryhmanimi on nyt käytettävissä!\n\n" .
                   "Terveisin, MiittiPaja";
        return mail($perustajaemail,'MiittiPaja ryhmä hyväksytty',$message);
    }

    function muokkausHyvaksytty($perustaja,$perustajaemail,$ryhmanimi) {
        $message = "Hei $perustaja!\n\n" .
                   "Ylläpito on hyväksynyt ryhmäsi muutokset.\n" .
                   "Ryhmän $ryhmanimi tiedot päivitetty\n\n" .
                   "Terveisin, MiittiPaja";
        return mail($perustajaemail,'MiittiPaja ryhmän muokkaus hyväksytty',$message);
    }

    function hyvaksyLiittyminen($ryhmanimi,$perustaja,$perustajaemail,$nimimerkki,$url) {
        $message = "Hei $perustaja!\n\n" .
                   "Ryhmääsi $ryhmanimi on tullut liittymispyyntö.\n" .
                   "Nimimerkki $nimimerkki haluaisi liittyä ryhmään. \n" .
                   "Käy hyväksymässä liittyminen klikkaamalla linkkiä.\n\n" .
                   "$url\n\n" .
                   "Terveisin, MiittiPaja";
        return mail($perustajaemail,'MiittiPaja liittymispyyntö ryhmääsi',$message);
    }

    function liittyminenHyvaksytty($nimimerkki,$email,$ryhmanimi,$perustaja) {
        $message = "Hei $nimimerkki!\n\n" .
                   "Ryhmän ylläpitäjä $perustaja on hyväksynyt sinut ryhmääsi.\n" .
                   "Ryhmä $ryhmanimi on nyt lisätty omiin ryhmiisi!\n\n" .
                   "Terveisin, MiittiPaja";
        return mail($email,'MiittiPaja liittyminen ryhmään hyväksytty',$message);

    }

?>