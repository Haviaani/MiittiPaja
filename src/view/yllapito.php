<?php $this->layout('template', ['title' => "Ylläpitosivut", 'request' => $request]) ?>

<div class="sivu">
    <div class="kontsa">
        <h1>Ylläpidon hallintasivut</h1>
        <div class="yllapitovalinta">
            <form action="" method="GET">
                <select name="ryhmavalinta" onchange="this.form.submit()">
                    <option value="">Valitse ylläpidettävä ryhmä</option>
                    <?php foreach ($ryhmat as $ryhma) { ?>            
                        <option value="<?= $ryhma['idryhma']?>"> 
                            <?= $ryhma['ryhmanimi'] ?> 
                        </option>
                    <?php } ?>
                </select>
            </form>
        </div>
        <div class="yllapitotiedot">
            <?php if (!$idryhma) { ?>
                <div>

                </div>
            <?php } else { ?>
                <div class="ryhmaotsikko">
                    Valittu ryhmä: <b> <?= $ryhmatiedot['ryhmanimi'] ?> </b>
                </div>
                    <table>
                        <colgroup>
                            <col>
                            <col>
                            <col>
                        </colgroup>
                        <thead>
                            <tr>
                                <th><b>Nimimerkki</b></th>
                                <th><b>Kirjautunut viimeksi</b></th>
                                <th><b>Tilitieto</b></th>
                            </tr>      
                        </thead>
                        <tbody>                                 
                            <?php foreach ($jasenet as $jasen) { ?>
                                <tr>
                                    <th><?= $jasen['nimimerkki'] ?></th>
                                    <th><?php 
                                        $kirjautunut = new DateTime($jasen['kirjautunut']);
                                        $kirjautunut = $kirjautunut->format('d.m.Y \k\l\o H:i');
                                        echo $kirjautunut; ?> </th>
                                    <th><?php 
                                        if ($jasen['vahvistettu']) {
                                            echo "Vahvistettu";
                                        } else {
                                            echo "Vahvistamaton";
                                        } ?> </th>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="alanapit_yllapito">
        <?php if (!$idryhma) { ?>
        <div></div>
        <?php } else if ($paivitystieto == 1) { ?>
        <div class="flexarea"><a href="muokkaa_ryhma?id=<?= $idryhma ?>" class="button_ala">Muokkaa ryhmää</a></div>
        <div class="infoteksti">
            (Muokkaus hyväksytetään ensin MiittiPajan ylläpidolla)
        </div>
        <?php } else if ($paivitystieto == 0) { ?>
        <div class="flexarea"><a href="muokkaa_ryhma?id=<?= $idryhma ?>" class="button_ala">Estetty</a></div>
        <div class="infoteksti">
            (Edellinen muokkauksesi on vielä hyväksyttävissä ylläpidolla)
        </div>   
        <?php } ?>
    </div>



</div>