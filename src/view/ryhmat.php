<?php $this->layout('template', ['title' => 'Ryhmät']) ?>

<div class="sivu">
    <div class="kontsa">
        <h1> MiittiPajaan luodut ryhmät </h1>
        <div class="ryhmat">
            <?php foreach ($ryhmat as $ryhma) { 
                if ($ryhma['hyvaksytty']) { ?>
                <div class="lista">
                    <a href="ryhma?id=<?=$ryhma['idryhma']?>">
                    <div class="lista_palkki">
                        <h2> <?= $ryhma['ryhmanimi'] ?> </h2>
                    </div>
                    <div class="lista_info">
                        <div><b>Perustaja:</b> <?= $ryhma['perustaja'] ?> </div>
                        <div><b>Jäseniä:</b> <?= $ryhma['jasenmaara'] ?> </div>
                    </div>
                    </a>
                </div>
            <?php } } ?>
        </div>
    </div>

    <div class="alanapit">
        <?php
            if ($loggeduser) { ?>
            <div class="flexarea"><a href="lisaa_ryhma" class="button_ala">Luo uusi ryhmä</a></div>
        <?php } ?>
    </div>
</div>


