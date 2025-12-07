<?php $this->layout('template', ['title' => 'Omat tiedot']) ?>

<div class="sivu">
  <div class="kontsa">
    <div class="tiedot">
      <div class="tiedot_palkki">
        <div><h1><?= $tiedot['nimimerkki'] ?></h1></div>
      </div>
      <div class="tiedot_info">
        <div><b>Etunimi:</b> <?= $tiedot['etunimi'] ?></div>
        <div><b>Sukunimi:</b> <?= $tiedot['sukunimi'] ?></div>
        <div><b>Sähköposti:</b> <?= $tiedot['email'] ?></div>
        <div><b>Ryhmien määrä:</b> <?= $ryhmat ?></div>
        <div><b>Ylläpidettävien ryhmien määrä:</b> <?= $ypt ?></div>
        <div><b>Ilmoittautumisten määrä:</b> <?= $ilmot ?></div>
        <div><b>Kommenttien määrä</b>: <?=$kommentit ?></div>
      </div>
    </div>
  </div>
  <div class="alanapit">
    <a href="tilaa_vaihtoavain" class="button_ala">Vaihda salasana</a>.
  </div>
</div>

