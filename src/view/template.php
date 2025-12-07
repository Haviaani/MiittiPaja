<!DOCTYPE html>
<html lang="fi">
    <head>
        <title>MiittiPaja - <?=$this->e($title)?></title>
        <meta charset="UTF-8">
        <link href="/~p89565/miittipaja/styles/styles.css" rel="stylesheet">
    </head>
    <body>
        
        <header>
            <div class="tyhja"></div>
            <a href="<?=BASEURL?>"><img src="/~p89565/miittipaja/images/miittipaja2.jpg"></a>
            <div class="profile">
                <?php
                    if (isset($_SESSION['user'])) { ?>
                        <div><?= $_SESSION['nimimerkki'] ?></div>
                        <div><a href="logout">Kirjaudu ulos</a></div>
                        <?php
                        if (isset($_SESSION['yllapitaja']) && $_SESSION['yllapitaja']) { ?>
                            <div><a href="/~p89565/miittipaja/yllapito">Ylläpitosivut</a></div>
                        <?php
                        }
                    } else { ?>
                        <div><a href='kirjaudu'>Kirjaudu</a></div>
                    <?php
                    }
                ?>
            </div> 
        </header>
        <menu>
            
            <div class="menu">
                <a class="button_menu <?= $request === '/' ? 'active' : '' ?>" href="/~p89565/miittipaja/">ETUSIVU</a>
                <a class="button_menu <?= $request === '/ryhmat' ? 'active' : '' ?>" href="/~p89565/miittipaja/ryhmat">RYHMÄT</a>
                <a class="button_menu <?= $request === '/omat_ryhmat' ? 'active' : '' ?>" href="/~p89565/miittipaja/omat_ryhmat">OMAT RYHMÄT</a>
                <a class="button_menu <?= $request === '/miitit' ? 'active' : '' ?>" href="/~p89565/miittipaja/miitit">OMAT MIITIT</a>
                <a class="button_menu <?= $request === '/tiedot' ? 'active' : '' ?>" href="/~p89565/miittipaja/tiedot">OMAT TIEDOT</a>
            </div>
            
        </menu>
        <section>
                <?=$this->section('content')?>
        </section>
        <footer>
            <hr>
            <div>MiittiPaja by Haviaani</div>
        </footer>
        
    </body>
</html>