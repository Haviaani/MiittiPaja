<?php $this->layout('template', ['title' => 'Ryhmän muokkaus']) ?>

<div class="sivu">
    <div class="kontsa">
        <h1>Ryhmän muokkaus</h1>

        <form action="" method="POST">
            <div>
                <label for="ryhmanimi">Ryhmän nimi:</label>
                <input id="ryhmanimi" type="text" name="ryhmanimi" value="<?= getValue($formdata, 'ryhmanimi') ?>">
                <div class="error"><?= getValue($error,'ryhmanimi'); ?></div>
            </div>
            <div>
                <label for="ryhmakuvaus">Ryhmän kuvaus:</label>
                <textarea id="ryhmakuvaus" name="ryhmakuvaus" rows="4" cols="50"><?= htmlspecialchars(getValue($formdata, 'ryhmakuvaus')) ?></textarea>
                <div class="error"><?= htmlspecialchars(getValue($error,'ryhmakuvaus')); ?></div>
            </div>
            <div>
                <input type="submit" name="muokkaa" value="Päivitä">
            </div>
        </form>

    </div>
</div>