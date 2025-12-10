<?php
    error_reporting(E_ALL);
ini_set('display_errors', 1);
    // Aloitetaan istunnot.
    session_start();

    // suoritetaan projektin aloitusskripti.
    require_once '../src/init.php';

    // Haetaan kirjautuneen käyttäjän tiedot.
    if (isset($_SESSION['user'])) {
        require_once MODEL_DIR . 'henkilo.php';
        $loggeduser = haeHenkilo($_SESSION['user']);
     } else {
        $loggeduser = NULL;
    }

    // Siistitään polku urlin alusta ja mahdolliset parametrit urlin lopusta.
    // Siistimisen jälkeen osoite /~p89565/lanify/tapahtuma?id=1 on lyhentynyt muotoon /tapahtuma.

    $request = str_replace($config['urls']['baseUrl'],'',$_SERVER['REQUEST_URI']);
    $request = strtok($request, '?');

    // Luodaan uusi Plates-olio ja kytketään se sovelluksen sivupohjiin.
    
    $templates = new League\Plates\Engine(TEMPLATE_DIR);

    $templates->addData(['request' => $request]);

    // Selvitetään mitä sivua on kutsuttu ja suoritetaan sivua vastaava käsittelijä.

    switch ($request) {
        case '/':
            echo $templates->render('etusivu');
            break;
        case '/ryhmat':            
            if ($loggeduser) {
                require_once MODEL_DIR . 'ryhma.php';
                $ryhmat = haeRyhmaTiedot();
                  echo $templates->render('ryhmat', ['ryhmat' => $ryhmat,
                                                     'loggeduser' => $loggeduser]);
                break;                                     
            } else {
                echo $templates->render('ryhmat_kirjautumaton');
                break;
            }
            break;
        case '/omat_ryhmat':            
            if ($loggeduser) {
                require_once MODEL_DIR . 'ryhma.php';
                $omatryhmat = haeOmatRyhmat($loggeduser['idhenkilo']);
                echo $templates->render('omat_ryhmat', ['omatryhmat' => $omatryhmat,
                                                        'loggeduser' => $loggeduser]);
                break;
            } else {
                echo $templates->render('ryhmat_kirjautumaton');
                break;
            }
            break;
        case '/ryhma':
            if ($loggeduser) {
                require_once MODEL_DIR . 'miitti.php';
                require_once MODEL_DIR . 'ryhma.php';
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $ryhmatiedot = haeRyhmaIDlla($id);
                    $jasenet = haeJasenetIDlla($id);
                    $miitit = haeRyhmanMiitit($id);
                    $omatryhmat = haeOmatRyhmat($loggeduser['idhenkilo']);
                    echo $templates->render('ryhma', ['ryhmatiedot' => $ryhmatiedot,
                                                    'loggeduser' => $loggeduser,
                                                    'jasenet' => $jasenet,
                                                    'miitit' => $miitit,
                                                    'omatryhmat' => $omatryhmat]);
                    break;
                } else {
                    echo $templates->render('virhe');
                    break;
                }
            } else {
                echo $templates->render('ryhmat_kirjautumaton');
                break;
            }
            break;
        case '/lisaa_ryhma':
            if ($loggeduser) {
                require_once CONTROLLER_DIR . 'ryhma.php';
                require_once MODEL_DIR . 'henkilo.php';
                if (isset($_POST['laheta'])) {
                    $formdata = cleanArrayData($_POST);
                    $adminemail = haeAdminSahkoposti();
                    $tulos = luoRyhma($adminemail,$loggeduser,$formdata,$config['urls']['baseUrl']);
                    if ($tulos['status'] == "200") {
                        echo $templates->render('ryhma_luotu', ['formdata' => $formdata]);
                        break;
                    }
                    echo $templates->render('lisaa_ryhma', ['formdata' => $formdata, 'error' => $tulos['error']]);
                    break;
                } else {
                    echo $templates->render('lisaa_ryhma', ['formdata' => [], 'error' => []]);
                    break;
                }
            } else {
                echo $templates->render('ryhmat_kirjautumaton');
                break;
            }
            break;
        case '/muokkaa_ryhma':
            if ($loggeduser) {
                require_once CONTROLLER_DIR . 'ryhma.php';
                require_once MODEL_DIR . 'ryhma.php';
                require_once MODEL_DIR . 'henkilo.php';
                if (isset($_GET['id'])) {
                    $idryhma = $_GET['id'];
                    $ryhma = haeRyhmaIDlla($idryhma);
                    $yp = $ryhma['ypid'];
                    if ($yp == $loggeduser['idhenkilo']) {
                        if (isset($_POST['muokkaa'])) {
                            $formdata = cleanArrayData($_POST);
                            $adminemail = haeAdminSahkoposti();
                            $muokkaa = muokkaaRyhma($adminemail,$formdata,$ryhma,$config['urls']['baseUrl']);
                            if ($muokkaa['status'] == "200") {
                                echo $templates->render('ryhma_muokattu');
                                break;
                            } else {                        
                                echo $templates->render('muokkaa_ryhma', ['formdata' => $formdata, 'miitti' => $ryhma, 'error' => $muokkaa['error']]);
                                break;
                            }
                        } else {                        
                            echo $templates->render('muokkaa_ryhma', ['formdata' => $ryhma, 'error' => []]);
                            break;
                        }
                    } else {
                        echo $templates->render('oikeudet_ei_riita');
                        break;
                    }
                } else {
                    echo $templates->render('virhe');
                    break;
                } 
            } else {
                echo $templates->render('ryhmat_kirjautumaton');
                break;
            }  
            break;    
        case '/vahvista_ryhma':
            require_once CONTROLLER_DIR . 'ryhma.php';
            require_once MODEL_DIR . 'ryhma.php';
            require_once MODEL_DIR . 'henkilo.php';
            if (isset($_GET['key'])) {
                $key = $_GET['key'];
                if (vahvistaRyhma($key)) {
                    $idryhma = haeIdAvaimella($key);
                    $idhenkilo = haePerustajaSahkopostilla($idryhma['perustajaemail']);
                    $hyvaksytty = hyvaksyRyhma($idhenkilo['idhenkilo'],$idryhma['idryhma'],$idryhma['perustaja'],$idryhma['perustajaemail'],$idryhma['ryhmanimi']);
                    if ($hyvaksytty['status'] == "200") {
                        echo $templates->render('ryhma_hyvaksytty');
                            break;
                    }               
                } else {
                    echo $templates->render('ryhma_hyvaksynta_virhe');
                    break;
                }
            } else {
                header("Location: " . $config['urls']['baseUrl']);
                break;
            }    
        break;
        case '/vahvista_ryhma_muokkaus':
            require_once CONTROLLER_DIR . 'ryhma.php';
            require_once MODEL_DIR . 'ryhma.php';
            require_once MODEL_DIR . 'henkilo.php';
            if (isset($_GET['key'])) {
                $key = $_GET['key'];
                if (varmistaAvain($key)) {
                    $idryhma = haeIdAvaimella($key);
                    $hyvaksytty = hyvaksyRyhmaMuokkaus($idryhma['idryhma'],$idryhma['perustaja'],$idryhma['perustajaemail'],$idryhma['ryhmanimi']);
                    if ($hyvaksytty['status'] == "200") {
                        echo $templates->render('ryhma_muokkaus_hyvaksytty');
                        break;
                    }                    
                } else {
                    echo $templates->render('ryhma_hyvaksynta_virhe');
                    break;
                }
            } else {
                header("Location: " . $config['urls']['baseUrl']);
                break;
            }    
        break;
        case '/liity_ryhmaan':
            if ($loggeduser) {
                require_once CONTROLLER_DIR . 'ryhma.php';
                require_once MODEL_DIR . 'ryhma.php';
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $ryhmatiedot = haeRyhmaIDlla($id); 
                    $idhenkilo = $loggeduser['idhenkilo'];
                    $idryhma = $ryhmatiedot['idryhma'];    
                    $onkoHaettu = haeHakemus($idhenkilo,$idryhma);
                    if (!$onkoHaettu) {     
                        $nimimerkki = $loggeduser['nimimerkki'];   
                        $email = $loggeduser['email'];  
                        $ryhmanimi = $ryhmatiedot['ryhmanimi'];     
                        $perustaja = $ryhmatiedot['perustaja'];
                        $perustajaemail = $ryhmatiedot['perustajaemail'];      
                        $anomus = jasenHakemus($idhenkilo,$nimimerkki,$email,$idryhma,$ryhmanimi,$perustaja,$perustajaemail,$config['urls']['baseUrl']);
                        if ($anomus['status'] == "200") {
                            echo $templates->render('ryhma_anottu');
                            break;
                        } else {
                            echo $templates->render('ryhma_anomusvirhe');
                            break;
                        }
                    } else {
                        echo $templates->render('ryhma_anottu_jo');
                        break;
                    }
                } else {
                echo $templates->render('virhe');
                break;
                }
            } else {
                echo $templates->render('ryhmat_kirjautumaton');
                break;
            }
            break;
        case '/hyvaksytty_ryhmaan':
            require_once CONTROLLER_DIR . 'ryhma.php';
            require_once MODEL_DIR . 'ryhma.php';
            if (isset($_GET['key'])) {
                $key = $_GET['key'];
                $hakija = haeHakemusAvaimella($key);
                $idhenkilo = $hakija['idhenkilo'];
                $nimimerkki = $hakija['nimimerkki'];
                $email = $hakija['email'];
                $idryhma = $hakija['idryhma'];
                $ryhmatiedot = haeRyhmaIDlla($idryhma); 
                $ryhmanimi = $ryhmatiedot['ryhmanimi'];
                $perustaja = $ryhmatiedot['perustaja'];                
                $hyvaksytty = hakemusHyvaksytty($idhenkilo,$nimimerkki,$email,$idryhma,$ryhmanimi,$perustaja);
                if ($hyvaksytty['status'] == "200") {
                    $poistahakemus = poistaHakemus($key);
                    if ($poistahakemus) {
                        echo $templates->render('ryhma_anomus_hyvaksytty');   
                        break;                 
                    } else {
                        echo $templates->render('ryhma_anomusvirhe_yp');
                        break;
                    }           
                } else {
                    echo $templates->render('ryhma_anomusvirhe_yp');
                    break;
                }
            } else {
                header("Location: " . $config['urls']['baseUrl']);
                break;
            }
            break;
        case '/poistu_ryhmasta':
            if ($loggeduser) {
                require_once MODEL_DIR . 'ryhma.php';
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $ryhmatiedot = haeRyhmaIDlla($id);
                    $omatryhmat = haeOmatRyhmat($loggeduser['idhenkilo']);
                    echo $templates->render('poistu_ryhmasta', ['ryhma' => $ryhmatiedot,
                                                                'loggeduser' => $loggeduser,
                                                                'omatryhmat' => $omatryhmat]);
                    break;
                } else {
                    echo $templates->render('virhe');
                    break;
                }
            } else {
                echo $templates->render('ryhmat_kirjautumaton');
                break;
            }
            break;
        case '/poistu_ryhmasta_varma':
            if ($loggeduser) {
                require_once CONTROLLER_DIR . 'ryhma.php';
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $poista = poistaRyhmastaIDlla($id,$loggeduser['idhenkilo']);
                    if ($poista['status'] == "200") {
                        echo $templates->render('poistu_ryhmasta_varma');
                        break;
                    } else {
                        echo $templates->render('virhe');
                        break;
                    }
                } else {
                    echo $templates->render('virhe');
                    break;
                }
            } else {
                echo $templates->render('ryhmat_kirjautumaton');
                break;
            }
            break;
        case '/miitit':
            if ($loggeduser) {
                require_once MODEL_DIR . 'miitti.php';
                require_once MODEL_DIR . 'ryhma.php';
                $miitit = haeOmatMiitit($loggeduser['idhenkilo']);
                $ryhmatiedot = haeRyhmaTiedot();
                echo $templates->render('miitit', ['miitit' => $miitit,
                                                   'ryhmatiedot' => $ryhmatiedot,
                                                   'loggeduser' => $loggeduser]);
                break;
            } else {
                echo $templates->render('miitit_kirjautumaton');
                break;
            }
            break;
        case '/miitti':
            if ($loggeduser) {
                require_once MODEL_DIR . 'miitti.php';
                require_once MODEL_DIR . 'ilmo.php';
                require_once CONTROLLER_DIR . 'miitti.php';
                $id = $_GET['id'] ?? $_POST['id'] ?? null;
                if ($id) {
                    $miitti = haeMiitti($id);
                    $ypid = haeMiittiYp($id);
                    $idhenkilo = $loggeduser['idhenkilo'];
                    $idmiitti = $miitti['idmiitti'];
                    $jasen = tarkistaJasenyys($idhenkilo,$idmiitti);
                    if ($miitti && $jasen) {
                        $kommentit = haeKommentit($miitti['idmiitti']);
                        if (isset($_POST['kommentoi'])) {                        
                            $kommenttidata = cleanArrayData($_POST);  
                            $kommentti = tarkistaKommentti($kommenttidata,$id,$loggeduser);
                            if ($kommentti['status'] == "200") {
                                $kommentit = haeKommentit($miitti['idmiitti']);
                                if ($loggeduser) {
                                    $ilmoittautuminen = haeIlmoittautuminen($loggeduser['idhenkilo'],$miitti['idmiitti']);
                                } else {
                                    $ilmoittautuminen = NULL;
                                }
                                echo $templates->render('miitti', ['miitti' => $miitti,
                                                                   'ypid' => $ypid,
                                                                   'kommentit' => $kommentit,
                                                                   'ilmoittautuminen' => $ilmoittautuminen,
                                                                   'loggeduser' => $loggeduser]);
                                break;
                            } else {
                                if ($loggeduser) {
                                    $ilmoittautuminen = haeIlmoittautuminen($loggeduser['idhenkilo'],$miitti['idmiitti']);
                                } else {
                                    $ilmoittautuminen = NULL;
                                }
                                echo $templates->render('miitti', ['kommenttidata' => $kommenttidata, 
                                                                   'error' => $kommentti['error'],
                                                                   'miitti' => $miitti,
                                                                   'ypid' => $ypid,
                                                                   'kommentit' => $kommentit,
                                                                   'ilmoittautuminen' => $ilmoittautuminen,
                                                                   'loggeduser' => $loggeduser]);  
                                break;      
                            } 
                        } else {
                            if ($loggeduser) {
                                $ilmoittautuminen = haeIlmoittautuminen($loggeduser['idhenkilo'],$miitti['idmiitti']);
                            } else {
                                $ilmoittautuminen = NULL;
                            }
                            echo $templates->render('miitti', ['miitti' => $miitti,
                                                               'ypid' => $ypid,
                                                               'kommentit' => $kommentit,
                                                               'ilmoittautuminen' => $ilmoittautuminen,
                                                               'loggeduser' => $loggeduser]);
                            break;
                        } 
                    } else {
                        echo $templates->render('miitti_ei_loydy');
                        break;
                    }                    
                }
            } else {
                echo $templates->render('miitit_kirjautumaton');
                break;
            }   
            break;         
        case '/lisaa_miitti':
            if ($loggeduser) {
                require_once CONTROLLER_DIR . 'miitti.php';
                if (isset($_POST['laheta']) && isset($_GET['id'])) {
                    $formdata = cleanArrayData($_POST);
                    $idryhma = $_GET['id'];
                    $tulos = luoMiitti($formdata,$idryhma);
                    if ($tulos['status'] == "200") {
                        $kutsu = lahetaKutsut($idryhma,$tulos['id'],$formdata,$config['urls']['baseUrl']);
                        if ($kutsu['status'] == "200") {
                            echo $templates->render('miitti_luotu', ['formdata' => $formdata]);
                            break;
                        } else {
                            echo $templates->render('virhe');
                            break;
                        }
                    } else {
                        echo $templates->render('lisaa_miitti', ['formdata' => $formdata, 'error' => $tulos['error']]);
                        break;
                    }
                } else {
                    echo $templates->render('lisaa_miitti', ['formdata' => [], 'error' => []]);
                    break;
                }
            } else {
                echo $templates->render('miitit_kirjautumaton');
                break;
            }   
            break; 
        case '/muokkaa_miitti':
            if ($loggeduser) {
                require_once CONTROLLER_DIR . 'miitti.php';
                require_once MODEL_DIR . 'miitti.php';
                if (isset($_GET['id'])) {
                    $idmiitti = $_GET['id'];
                    $miitti = haeMiitti($idmiitti);
                    $yp = haeMiittiYp($idmiitti);
                    if ($yp['ypid'] == $loggeduser['idhenkilo']) {
                        if (isset($_POST['muokkaa'])) {
                            $formdata = cleanArrayData($_POST);
                            $muokkaa = muokkaaMiitti($formdata,$miitti);
                            if ($muokkaa['status'] == "200") {
                                echo $templates->render('miitti_muokattu');
                                break;
                            } else if ($muokkaa['status'] == "0") {                        
                                echo $templates->render('miitti_ei_muokattu');
                                break;
                            } else {                        
                                echo $templates->render('muokkaa_miitti', ['formdata' => $formdata, 'miitti' => $miitti, 'error' => $muokkaa['error']]);
                                break;
                            }
                        } else {                        
                            echo $templates->render('muokkaa_miitti', ['formdata' => $miitti, 'error' => []]);
                            break;
                        }
                    } else {
                        echo $templates->render('oikeudet_ei_riita');
                        break;
                    }
                } else {
                    echo $templates->render('virhe');
                    break;
                } 
            } else {
                echo $templates->render('miitit_kirjautumaton');
                break;
            }  
            break;    
        case '/tiedot':
            if ($loggeduser) {
                require_once MODEL_DIR . 'henkilo.php';
                require_once MODEL_DIR . 'ilmo.php';
                $id = $loggeduser['idhenkilo'];
                $tiedot = haeHenkiloNimimerkilla($loggeduser['nimimerkki']);
                $ilmot = haeHenkilonIlmoittautumisMaara($id);
                $ryhmat = haeHenkilonRyhmienMaara($id); 
                $ypt = haeHenkilonYllapidettavienMaara($id);
                $kommentit = haeHenkilonKommenttienMaara($id);       
                echo $templates->render('tiedot', ['tiedot' => $tiedot,
                                                   'ilmot' => $ilmot,
                                                   'ryhmat' => $ryhmat,
                                                   'ypt' => $ypt,
                                                   'kommentit' => $kommentit]);
                break;
            } else {
                echo $templates->render('tiedot_kirjautumaton');
                break;
            }
            break;
        case '/ilmoittaudu':
            if ($loggeduser) {
                require_once MODEL_DIR . 'ilmo.php';
                if ($_GET['id']) {
                    $idmiitti = $_GET['id'];
                    if ($loggeduser) {
                        lisaaIlmoittautuminen($loggeduser['idhenkilo'], $idmiitti);
                        lisaaMiittiIlmo($idmiitti);
                    }
                    header("Location: miitti?id=$idmiitti");
                    break;
                } else {
                    header("Location: miitit");
                    break;
                }
            } else {
                echo $templates->render('virhe');
                break;
            }  
            break;
        case '/peru':
            if ($loggeduser) {
                require_once MODEL_DIR . 'ilmo.php';
                if ($_GET['id']) {
                    $idmiitti = $_GET['id'];
                    if ($loggeduser) {
                        poistaIlmoittautuminen($loggeduser['idhenkilo'],$idmiitti);
                        poistaMiittiIlmo($idmiitti);
                    }
                    header("Location: miitti?id=$idmiitti");
                    break;
                } else {
                    header("Location: miitit");
                    break;
                }
            } else {
                echo $templates->render('virhe');
                break;
            }  
            break;
        case '/lisaa_tili':
            if (isset($_POST['laheta'])) {
                $formdata = cleanArrayData($_POST);
                require_once CONTROLLER_DIR . 'tili.php';
                $tulos = lisaaTili($formdata,$config['urls']['baseUrl']);
                if ($tulos['status'] == "200") {
                    echo $templates->render('tili_luotu', ['formdata' => $formdata]);
                    break;
                } else {
                    echo $templates->render('lisaa_tili', ['formdata' => $formdata, 'error' => $tulos['error']]);
                    break;
                }
            } else {
                echo $templates->render('lisaa_tili', ['formdata' => [], 'error' => []]);
                break;
            }   
            break;
        case '/vahvista':
            if (isset($_GET['key'])) {
                $key = $_GET['key'];
                require_once MODEL_DIR . 'henkilo.php';
                if (vahvistaTili($key)) {
                    echo $templates->render('tili_aktivoitu');
                    break;
                } else {
                    echo $templates->render('tili_aktivointi_virhe');
                    break;
                }
            } else {
                header("Location: " . $config['urls']['baseUrl']);
                break;
            }    
            break;
        case '/kirjaudu':
            if (isset($_POST['laheta'])) {
                require_once CONTROLLER_DIR . 'kirjaudu.php';
                if (tarkistaKirjautuminen($_POST['email'],$_POST['salasana'])) {
                    require_once MODEL_DIR . 'henkilo.php';
                    $user = haeHenkilo($_POST['email']);
                    $yp = haeHenkilonYp($user['idhenkilo']);
                    if ($user['vahvistettu']) {
                        session_regenerate_id();
                        $_SESSION['user'] = $user['email'];
                        $_SESSION['nimimerkki'] = $user['nimimerkki'];
                        $_SESSION['admin'] = $user['admin'];
                        if ($yp) {
                            $_SESSION['yllapitaja'] = $user['idhenkilo'];
                        }
                        header("Location: " . $config['urls']['baseUrl']);
                        break;
                    } else {
                        echo $templates->render('kirjaudu', ['error' => ['virhe' => 'Tili on vahvistamatta! Ole hyvä ja vahvista tili sähköpostissa olevalla linkillä.']]);
                        break;
                    }
                } else {
                    echo $templates->render('kirjaudu', ['error' => ['virhe' => 'Väärä käyttäjätunnus tai salasana!']]);
                    break;
                }      
            } else {
                echo $templates->render('kirjaudu', ['error' => []]);
                break;
            }
            break;
        case '/logout':
            require_once CONTROLLER_DIR . 'kirjaudu.php';
            logout();
            header("Location: " . $config['urls']['baseUrl']);
            break;  
        case '/tilaa_vaihtoavain':
            $formdata = cleanArrayData($_POST);
            if (isset($formdata['laheta'])) {
                require_once MODEL_DIR . 'henkilo.php';
                $user = haeHenkilo($formdata['email']);
                if ($user) {
                    require_once CONTROLLER_DIR . 'tili.php';
                    $tulos = luoVaihtoavain($formdata['email'],$config['urls']['baseUrl']);
                    if ($tulos['status'] == "200") {
                        echo $templates->render('tilaa_vaihtoavain_lahetetty');
                        break;
                    }
                    echo $templates->render('virhe');
                    break;
                } else {
                    echo $templates->render('tilaa_vaihtoavain_lahetetty');
                    break;
                }
            } else {
                echo $templates->render('tilaa_vaihtoavain_lomake');
                break;
            }
            break;
        case '/reset':
            $resetkey = $_GET['key'];
            require_once MODEL_DIR . 'henkilo.php';
            $rivi = tarkistaVaihtoavain($resetkey);
            if ($rivi) {
                if ($rivi['aikaikkuna'] < 0) {
                    echo $templates->render('reset_virhe');
                    break;
                }
            } else {
                echo $templates->render('reset_virhe');
                break;
            }

            $formdata = cleanArrayData($_POST);
            if (isset($formdata['laheta'])) {
                require_once CONTROLLER_DIR . 'tili.php';
                $tulos = resetoiSalasana($formdata,$resetkey);
                if ($tulos['status'] == "200") {
                    echo $templates->render('reset_valmis');
                    break;
                }
                echo $templates->render('reset_lomake', ['error' => $tulos['error']]);
                break;                
            } else {
                echo $templates->render('reset_lomake', ['error' => '']);
                break;
            }
            break;

        case (bool)preg_match('/\/yllapito$/', $request):
            if (isset($_SESSION["yllapitaja"]) && $_SESSION["yllapitaja"]) {
                require_once MODEL_DIR . 'henkilo.php';
                $idhenkilo = $_SESSION['yllapitaja']; 
                $ryhmat = haeHenkilonYpRyhmat($idhenkilo);                                   
                if (isset($_GET['ryhmavalinta'])) {
                    require_once MODEL_DIR . 'ryhma.php';
                    $idryhma = $_GET['ryhmavalinta'];
                    $ryhmatiedot = haeRyhmaIDlla($idryhma);
                    $jasenet = haeJasenetIDlla($idryhma);
                    $paivitystieto = tarkistaPaivitys($idryhma);
                } 
                echo $templates->render('yllapito', ['request' => $request,
                                                     'ryhmat' => $ryhmat,
                                                     'paivitystieto' => $paivitystieto ?? null, 
                                                     'ryhmatiedot' => $ryhmatiedot ?? null,
                                                     'jasenet' => $jasenet ?? null,
                                                     'idryhma' => $idryhma ?? null]);
            } else {
                echo $templates->render('admin_ei_oikeuksia');
            }
            break;
    
        default:
            echo $templates->render('notfound');
    }

?>
