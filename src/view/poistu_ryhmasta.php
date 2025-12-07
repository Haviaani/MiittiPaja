<?php $this->layout('template', ['title' => 'Poistu ryhmästä']) ?>

<?php

    $jasen = false;

    foreach ($omatryhmat as $ryhmat) {
        if ($ryhmat['idryhma'] == $ryhma['idryhma']) {
            $jasen = true;
        }
    }

?>

<div class="sivu">
    <div class="kontsa_varmistus">

        <h1>Olet poistumassa ryhmästä <?= $ryhma['ryhmanimi'] ?></h1>
        <div>Oletko varma?</div>
        <div class="varmistusnapit">
            <a href="poistu_ryhmasta_varma?id=<?= $ryhma['idryhma'] ?>" class="button_ala">Kyllä</a>
            <a href="ryhma?id=<?= $ryhma['idryhma'] ?>" class="button_ala">Peruuta</a>
        </div>        

    </div>
    <div class="alanapit">
        
        <?php

            if ($jasen) { ?>
                <div class="flexarea"><a href="lisaa_miitti" class="button_ala">Luo uusi miitti</a></div>            
                <div class="flexarea"><a href="poistu_ryhmasta?id=<?= $ryhma['idryhma'] ?>" class="button_ala">Poistu ryhmästä</a></div>
            <?php 
            } else if (!$jasen) { ?>
                <div class="flexarea"><a href="liity_ryhmaan?id=<?= $ryhma['idryhma'] ?>" class="button_ala">Liity ryhmään</a></div>
            <?php
            }  
        ?>

    </div>
</div>

