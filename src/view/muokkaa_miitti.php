<?php $this->layout('template', ['title' => 'Miitin muokkaus']) ?>

<div class="sivu">
    <div class="kontsa">
        <h1>Miitin muokkaus</h1>

        <form action="" method="POST">
            <div>
                <label for="miitti">Nimi:</label>
                <input id="miitti" type="text" name="miitti" value="<?= getValue($formdata, 'miitti') ?>">
                <div class="error"><?= getValue($error,'miitti'); ?></div>
            </div>
            <div>
                <label for="aika">Ajankohta:</label>
                <input id="aika" type="datetime-local" name="aika" value="<?= getValue($formdata, 'aika') ?>">
                <div class="error"><?= getValue($error,'aika'); ?></div>
            </div>
            <div>
                <label for="paikka">Paikka:</label>
                <input id="paikka" type="text" name="paikka" value="<?= getValue($formdata, 'paikka') ?>">
                <div class="error"><?= getValue($error,'paikka'); ?></div>
            </div>
            <div>
                <label for="osoite">Osoite:</label>
                <input id="osoite" type="text" name="osoite" value="<?= getValue($formdata, 'osoite') ?>">
                <div class="error"><?= getValue($error,'osoite'); ?></div>
            </div>
            <div>
                <label for="info">Info:</label>
                <textarea id="info" name="info" rows="3" cols="30"><?= getValue($formdata, 'info') ?></textarea>
                <div class="error"><?= getValue($error,'info'); ?></div>
            </div>
            <div>
                <label for="ilmoloppuu">Ilmoittautuminen päättyy:</label>
                <input id="ilmoloppuu" type="datetime-local" name="ilmoloppuu" value="<?= getValue($formdata, 'ilmoloppuu') ?>">
                <div class="error"><?= getValue($error,'ilmoloppuu'); ?></div>
            </div>
            <div>
                <input type="hidden" name="id" value="<?= $miitti['idmiitti'] ?>">
                <input type="submit" name="muokkaa" value="Päivitä">
            </div>
        </form>
    </div>
</div>