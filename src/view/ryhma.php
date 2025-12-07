<?php $this->layout('template', ['title' => $ryhmatiedot['ryhmanimi']]) ?>

<?php

    $jasen = false;

    foreach ($omatryhmat as $ryhma) {
        if ($ryhma['idryhma'] == $ryhmatiedot['idryhma']) {
            $jasen = true;
        }
    }

    $yllapitaja = false;

    if ($loggeduser['idhenkilo'] == $ryhmatiedot['ypid']) {
            $yllapitaja = true;
    }

    $nykyaika = new DateTime();
    $tulevatmiitit = [];
    
    foreach ($miitit as $miitti) {
        $aika = new DateTime($miitti['aika']);
        if ($aika > $nykyaika) {
            $tulevatmiitit[] = $miitti;
        }
    }

?>

<div class="sivu">
    <div class="kontsa">        
        <div class="tiedot">
            <div class="tiedot_palkki">
                <h1><?=$ryhmatiedot['ryhmanimi']?></h1>
            </div>
            <div class="tiedot_info">
                <div><b>Ylläpitäjä:</b> <?= $ryhmatiedot['perustaja'] ?> | <b>Jäsenet:</b> <?= $ryhmatiedot['jasenmaara'] ?> </div>
                <div class="jasenlista">
                    <?php foreach ($jasenet as $jasenlista) { ?>
                        <div class="jasenruutu"> <?= $jasenlista['nimimerkki'] ?></div>
                    <?php } ?> 
                </div>
                <div><b>Info:</b> <?= $ryhmatiedot['ryhmakuvaus'] ?></div>
            </div>
        </div>        
        <h2>Ryhmän tulevat miitit</h2>
        <div class="miitit">
            <?php 
                foreach ($tulevatmiitit as $miitti) { 
                    $ajankohta = new DateTime($miitti['aika']);
                    $ajankohta = $ajankohta->format('d.m.Y \k\l\o H:i');
                ?>   
                <div class="lista">                 
                <a href="miitti?id=<?= $miitti['idmiitti'] ?>">
                    <div>
                        <div class="lista_palkki">
                            <h2><?= $miitti['miitti'] ?></h2>
                        </div>
                        <div class="lista_info">
                            <div><b>Ajankohta:</b> <?= $ajankohta ?></div>
                            <div><b>Osallistujia:</b> <?= $miitti['osallistujia'] ?></div>
                        </div>
                    </div>
                </a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="alanapit">
        
        <?php

            if ($jasen) { ?>
                <div class="flexarea"><a href="lisaa_miitti?id=<?= $ryhmatiedot['idryhma'] ?>" class="button_ala">Luo uusi miitti</a></div>
                <?php
                if (!$yllapitaja) { ?>
                    <div class="flexarea"><a href="poistu_ryhmasta?id=<?= $ryhmatiedot['idryhma'] ?>" class="button_ala">Poistu ryhmästä</a></div>
                <?php
                }
            } else if (!$jasen) { ?>
                <div class="flexarea"><a href="liity_ryhmaan?id=<?= $ryhmatiedot['idryhma'] ?>" class="button_ala">Liity ryhmään</a></div>
            <?php
            }  
        ?>

    </div>
</div>

