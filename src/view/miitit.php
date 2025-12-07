<?php $this->layout('template', ['title' => 'Omat miitit']) ?>

<?php

    require_once MODEL_DIR . "ilmo.php";

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
    <div class="otsikko">
        <h1>Omat tulevat miitit</h1>
    </div>
    <div class="kontsa">
        <div class="miitit">
        <?php 
            foreach ($tulevatmiitit as $miitti) { 
                foreach ($ryhmatiedot as $ryhma) { 
                        $ajankohta = new DateTime($miitti['aika']);
                        $ajankohta = $ajankohta->format('d.m.Y \k\l\o H:i'); 
                        $ilmoittautuminen = haeIlmoittautuminen($loggeduser['idhenkilo'],$miitti['idmiitti']); ?>
                    <div class="lista">
                    <a href="miitti?id=<?= $miitti['idmiitti'] ?>">
                        <div class="lista_palkki">
                            <div><h2> <?= $miitti['miitti'] ?></h2></div>
                        </div>
                        <div class="lista_info">
                            <div><b>Ryhmä:</b> <?= $ryhma['ryhmanimi'] ?></div>
                            <div><b>Ajankohta:</b> <?= $ajankohta ?></div>
                            <div><b>Ilmoittautuneita:</b> <?=$miitti['osallistujia'] ?></div>
                            <div><b>Ilmoittautunut:</b> 
                                <?php if (!$ilmoittautuminen) { 
                                    echo "Ei";
                                } else if ($ilmoittautuminen) { 
                                    echo "Kyllä";
                                } ?>
                            </div>
                        </div>                    
                    </a>
                    </div>
        <?php } } ?>
    </div>
</div>
