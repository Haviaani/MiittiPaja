<?php $this->layout('template', ['title' => 'Uuden ryhmän luonti']) ?>

<div class="sivu">
    <div class="kontsa">
        <h1>Uuden ryhmän luonti</h1>

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
                <input type="submit" name="laheta" value="Luo ryhmä">
            </div>
        </form>

    </div>
</div>