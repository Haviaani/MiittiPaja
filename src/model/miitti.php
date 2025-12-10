<?php
    require_once HELPERS_DIR . 'DB.php';

    function haeMiitit() {
        return DB::run('SELECT * FROM mp_miitti ORDER BY aika;')->fetchAll();
    }

    function haeMiitti($idmiitti) {
        return DB::run('SELECT * FROM mp_miitti WHERE idmiitti = ?;',[$idmiitti])->fetch();
    }

    function haeMiittiTiedot() {
        return DB::run('SELECT * FROM mp_miitti;')->fetchAll();
    }

    function lisaaMiitti($idryhma,$miitti,$aika,$paikka,$osoite,$ilmoloppuu,$info) {
        DB::run('INSERT INTO mp_miitti (idryhma, miitti, aika, paikka, osoite, ilmoloppuu, info) VALUE (?,?,?,?,?,?,?);',[$idryhma,$miitti,$aika,$paikka,$osoite,$ilmoloppuu,$info]);
        return DB::lastInsertId();
    }

    function paivitaMiitti($miitti,$aika,$paikka,$osoite,$ilmoloppuu,$info,$idmiitti) {
        return DB::run('UPDATE mp_miitti SET miitti = ?, aika = ?, paikka = ?, osoite = ?, ilmoloppuu = ?, info = ? WHERE idmiitti = ?',[$miitti,$aika,$paikka,$osoite,$ilmoloppuu,$info,$idmiitti])->rowCount();
    }

    function haeMiittiNimella($miitti) {
        return DB::run('SELECT * FROM mp_miitti WHERE miitti = ?;', [$miitti])->fetchAll();
    }

    function haeMiittiMaara($idryhma) {
        return DB::run('SELECT * FROM mp_miitti WHERE idryhma = ?', [$idryhma])->rowCount();
    }

    function haeOmatMiitit($idhenkilo) {
        return DB::run('SELECT * from mp_miitti WHERE idryhma IN (SELECT idryhma FROM mp_henkiloryhma WHERE idhenkilo = ?) ORDER BY aika ASC',[$idhenkilo])->fetchAll();
    }

    function haeMiittiYp($id) {
        return DB::run('SELECT ypid FROM mp_ryhma WHERE idryhma IN (SELECT idryhma FROM mp_miitti WHERE idmiitti = ?)', [$id])->fetch();
    }

    function haeRyhmanMiitit($idryhma) {
        return DB::run('SELECT * from mp_miitti WHERE idryhma = ? ORDER BY aika ASC',[$idryhma])->fetchAll();
    }

    function haeKommentti($kommenttidata,$loggeduser,$id) {
        return DB::run ('SELECT * FROM mp_kommentti WHERE kommentti = ? AND idhenkilo = ? AND idmiitti = ?',[$kommenttidata,$loggeduser,$id])->fetch();
    }

    function lisaaKommentti($idmiitti,$idhenkilo,$nimimerkki,$kommmentti) {
        DB::run('INSERT INTO mp_kommentti (idmiitti, idhenkilo, nimimerkki, kommentti) VALUE (?,?,?,?);',[$idmiitti,$idhenkilo,$nimimerkki,$kommmentti]);
        return DB::lastInsertId();
    }

    function haeKommentit($idmiitti) {
        return DB::run('SELECT * FROM mp_kommentti WHERE idmiitti = ?',[$idmiitti])->fetchAll();
    }

    function tarkistaJasenyys($idhenkilo,$idmiitti) { 
        return DB::run('SELECT * FROM mp_henkiloryhma WHERE idhenkilo = ? AND idryhma IN (SELECT idryhma FROM mp_miitti WHERE idmiitti = ?)',[$idhenkilo,$idmiitti])->rowCount();
    }
?>