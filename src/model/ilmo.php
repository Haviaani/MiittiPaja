<?php

    require_once HELPERS_DIR . 'DB.php';

    function haeIlmoittautuminen($idhenkilo, $idmiitti) {
        return DB::run('SELECT * FROM mp_ilmo WHERE idhenkilo = ? AND idmiitti = ?',
                       [$idhenkilo, $idmiitti])->fetchAll();
    }

    function lisaaIlmoittautuminen($idhenkilo, $idmiitti) {
        DB::run('INSERT INTO mp_ilmo (idhenkilo, idmiitti) VALUE (?,?)',
                [$idhenkilo, $idmiitti]);
        return DB::lastInsertId();
    }

    function haeOmatIlmotID($idhenkilo) {
        return DB::run('SELECT * FROM mp_ilmo WHERE idhenkilo = ?;',[$idhenkilo])->fetchAll();
    }

    function poistaIlmoittautuminen($idhenkilo, $idmiitti) {
        return DB::run('DELETE FROM mp_ilmo WHERE idhenkilo = ? AND idmiitti = ?',
                       [$idhenkilo, $idmiitti])->rowCount();
    }

    function haeHenkilonIlmoittautumiset($idhenkilo) {
        return DB::run('SELECT * FROM mp_ilmo WHERE idhenkilo = ?', [$idhenkilo])->fetchAll();
    }

    function lisaaMiittiIlmo($idmiitti) {
        return DB::run('UPDATE mp_miitti SET osallistujia = osallistujia + 1 WHERE idmiitti = ?',[$idmiitti])->rowCount();
    }

    function poistaMiittiIlmo($idmiitti) {
        return DB::run('UPDATE mp_miitti SET osallistujia = osallistujia - 1 WHERE idmiitti = ?',[$idmiitti])->rowCount();
    }

?>