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

    if ($request === '/' || $request === '/tapahtumat') {
        echo $templates->render('tapahtumat');
    } else if ($request === '/tapahtuma') {
        echo $templates->render('tapahtuma');
    } else {
        echo $templates->render('notfound');
    }
    
?>