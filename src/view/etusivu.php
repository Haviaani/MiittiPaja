<?php $this->layout('template', ['title' => 'Etusivu']) ?>

<div class="sivu">
    <div class="kontsa">
        <?php
            if (isset($_SESSION['user'])) { ?>
                <div class="etusivu_info">
                    <h1>Tervetuloa <?= $_SESSION['nimimerkki'] ?>! </h1>
                    <h3>MiittiPaja on erilaisille ryhmille miittien ja 
                    tapahtumien aikatauluttamiseen ja ilmoittautumiseen 
                    tarkoitettu sivusto.</h3>
                </div>
                <div class="etusivu_ruutu">
                    <div class="tiedot_palkki">
                        <h2>MiittiPaja pähkinänkuoressa</h2>
                    </div>
                    <div class="tiedot_info">
                        <p>- <b>Ryhmät</b>-sivulla näet sivustolle luodut ryhmät. Ryhmäruutua 
                            klikkaamalla pääset ryhmän sivulle, jossa näet ryhmän tietoja, 
                            miittejä ja voit hakea liittymistä ryhmään.</p>
                            <br>
                        <p>- <b>Omat ryhmät</b>-sivulla näet omat luodut tai liitytyt ryhmät.
                            Ryhmäruutua klikkaamalla pääset sivulle, jossa näet ryhmän tietoja,
                            miittejä ja voit poistua liitytystä ryhmästä. Ryhmän ylläpitäjä
                            voi ryhmän sivulla luoda uusia miittejä.</p>
                            <br>
                        <p>- <b>Omat miitit</b>-sivulla näet omien ryhmiesi luodut tulevat miitit.
                            Miittiruutua klikkaamalla pääset sivulle, jossa näet miitin tietoja,
                            ja voit ilmoittaa osallistumisesi miittiin tai kommentoida miittiä.
                            Ylläpitäjä voi myös muokata miittiä tällä sivulla.</p>
                            <br>
                        <p>- <b>Omat tiedot</b>-sivulla näet omat tietosi ja "tilastosi". Voit myös
                            tilata salasanan vaihtoavaimen tällä sivulla.</p>
                            <br>
                    </div>
                </div>
                        <?php } else { ?>
                <div class="etusivu_info">
                    <h1>Tervetuloa!</h1>
                    <h3>MiittiPaja on erilaisille ryhmille miittien ja tapahtumien 
                    aikatauluttamiseen ja ilmoittautumiseen tarkoitettu sivusto. 
                    Rekisteröi itsellesi omat MiittiPaja-tunnukset alla olevasta
                    painikkeesta.</h3>
                    <div class="varmistusnapit">
                        <a class="button_ala" href="/~p89565/miittipaja/lisaa_tili">Rekisteröidy</a>
                    </div>
                </div>
        <?php } ?> 
    </div>
</div>