<?php $this->layout('template', ['title' => $miitti['miitti']]) ?>

<?php

$aika = new DateTime($miitti['aika']);
$aikamuotoiltu = $aika->format('d.m.Y \k\l\o H:i');

$ilmoloppuu = new DateTime($miitti['ilmoloppuu']);
$ilmoloppuumuotoiltu = $ilmoloppuu->format('d.m.Y \k\l\o H:i');

$ilmokiinni = false;

$nykyaika = new DateTime();

if ($nykyaika > $ilmoloppuu) {
  $ilmokiinni = true;
}

$yllapitaja = false;

if ($ypid['ypid'] == $loggeduser['idhenkilo']) {
  $yllapitaja = true;
}

$kommenttidata = $kommenttidata ?? [];
$error = $error ?? [];

?>


<div class="sivu">
  <div class="kontsa">    

    <div class="tiedot">
      <div class="tiedot_palkki">
      <div><h1><?= $miitti['miitti'] ?></h1></div>
      </div>
      <div class="tiedot_info">
        <div><b>Ajankohta:</b> <?= $aikamuotoiltu ?> </div> 
        <div><b>Paikka:</b> <?= $miitti['paikka'] ?> - <?= $miitti['osoite'] ?></div>        
        <div><b>Ilmoittautunut:</b> 
        <?php if (!$ilmoittautuminen) { 
          echo "Ei";
          } else if ($ilmoittautuminen) {
             echo "Kyllä";
          } ?>
        | <b>Ilmoittautuneita:</b> <?= $miitti['osallistujia'] ?></div>
        <?php if (!$ilmokiinni) { ?>
          <div><b>Ilmoittautuminen päättyy:</b> <?= $ilmoloppuumuotoiltu ?> </div>
        <?php } else { ?>
          <div><b>Ilmoittautuminen päättynyt</b></div>
        <?php } ?>
        <div><b>Info:</b> <?= $miitti['info'] ?></div>
      </div>
    </div>
      <div class="kommentti_form"> 
        <form class="kommentoi" action="" method="POST">
        <div class="kommentti_teksti">
          <textarea id="kommentti" name="kommentti" rows="2" cols="50" placeholder="Kirjoita kommenttisi tähän..."><?= getValue($kommenttidata, 'kommentti') ?></textarea>
          <div class="error"><?= getValue($error,'kommentti'); ?></div>
          <input type="hidden" name="id" value="<?= $miitti['idmiitti'] ?>">
          <input type="submit" name="kommentoi" value="Kommentoi">
        </div>  
        </form>
      </div>

      <div class="kommentit"> 
        <?php
          foreach ($kommentit as $kommentti) { 
            $aikaKom = new DateTime($kommentti['aika']);
            $aikaKom = $aikaKom->format('d.m.Y \k\l\o H:i') ?>
            <div class="kommenttikentta">
              <div class="kommentti_rivi">
                <div class="kommentti_aika"> <?= $aikaKom ?> </div>
                <div class="kommentti_nimi"> <b><?= $kommentti['nimimerkki'] ?></b> kommentoi: </div>
              </div>
              <div class="kommentti_rivi">
                <div class="kommentti"> <?= $kommentti['kommentti'] ?> </div>
              </div>
            </div>
        <?php } ?>
      </div>

  </div>
  <div class="alanapit">

    <?php
      if ($loggeduser) {
        if ($yllapitaja) { ?>
          <div class="flexarea"><a href="muokkaa_miitti?id=<?= $miitti['idmiitti'] ?>" class="button_ala">Muokkaa</a></div>
        <?php
        } 
          if (!$ilmokiinni) {
            if (!$ilmoittautuminen) { ?>
              <div class="flexarea"><a href="ilmoittaudu?id=<?= $miitti['idmiitti'] ?>" class="button_ala">Ilmoittaudu</a></div>
            <?php 
            } else { ?>
              <div class="flexarea"><a href="peru?id=<?= $miitti['idmiitti'] ?>" class="button_ala">Peru ilmoittautuminen</a></div>
            <?php 
            } 
          } else { ?>
              <div class="flexarea"><div class="button_ala">Suljettu</button></div>
          <?php 
          }           
      } 
    ?>

  </div>
</div>