<?php $this->layout('template', ['title' => 'Miitti luotu']) ?>

<div class="sivu">
    <div class="kontsa">
      <h1>Uusi miitti luotu!</h1>

      <?php 

      $tap_alkaa = getValue($formdata,'tap_alkaa');
      $tap_alkaa = str_replace('T', ' ', $tap_alkaa) . ':00';
      $tap_loppuu = getValue($formdata,'tap_loppuu');
      $tap_loppuu = str_replace('T', ' ', $tap_loppuu) . ':00';
      $ilm_alkaa = getValue($formdata,'ilm_alkaa');
      $ilm_alkaa = str_replace('T', ' ', $ilm_alkaa) . ':00';
      $ilm_loppuu = getValue($formdata,'ilm_loppuu');
      $ilm_loppuu = str_replace('T', ' ', $ilm_loppuu) . ':00';

      ?>

      <p>Tapahtuma: <?= getValue($formdata,'nimi') ?><br>
         Kuvaus: <?= getValue($formdata,'kuvaus') ?><br>
         Ajankohta: <?= date("H:i d.m.Y", strtotime($tap_alkaa)) ?> - <?= date("H:i d.m.Y", strtotime($tap_loppuu)) ?><br>
         Ilmoittautuminen: <?= date("H:i d.m.Y", strtotime($ilm_alkaa)) ?> - <?= date("H:i d.m.Y", strtotime($ilm_loppuu)) ?></p>
   </div>
</div>
