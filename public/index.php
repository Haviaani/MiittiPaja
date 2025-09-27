<?php
    // suoritetaan projektin aloitusskripti.
    require_once '../src/init.php';

    // Siistitään polku urlin alusta ja mahdolliset parametrit urlin lopusta.
    // Siistimisen jälkeen osoite /~p89565/lanify/tapahtuma?id=1 on lyhentynyt muotoon /tapahtuma.

    $request = str_replace($config['urls']['baseurl'],'',$_SERVER['REQUEST_URI']);
    $request = strtok($request, '?');

    // Luodaan uusi Plates-olio ja kytketään se sovelluksen sivupohjiin.
    
    $templates = new League\Plates\Engine(TEMPLATE_DIR);

    // Selvitetään mitä sivua on kutsuttu ja suoritetaan sivua vastaava käsittelijä.

    switch ($request) {
        case '/':
        case '/tapahtumat':
            require_once MODEL_DIR . 'tapahtuma.php';
            $tapahtumat = haeTapahtuma();
            echo $templates->render('tapahtumat', ['tapahtumat' => $tapahtumat]);
            break;
        case '/tapahtuma':
            require_once MODEL_DIR . 'tapahtuma.php';
            $tapahtuma = haeTapahtuma($_GET['id']);
            if ($tapahtuma) {
                echo $templates->render('tapahtuma', ['tapahtuma' => $tapahtuma]);
            } else {
                echo $templates->render('tapahtumanotfound');
            }
            break;
        case '/lisaa_tili':
            echo $templates->render('lisaa_tili');
            break;
        default:
            echo $templates->render('notfound');
    }

?>