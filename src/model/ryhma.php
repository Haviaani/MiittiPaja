<?php
    require_once HELPERS_DIR . 'DB.php';

    function haeRyhmat() {
        return DB::run('SELECT * FROM mp_miitti ORDER BY aika;')->fetchAll();
    }

    function haeRyhma($idhenkilo) {
        return DB::run('SELECT * FROM mp_henkiloryhma WHERE idhenkilo = ?;',[$idhenkilo])->fetch();
    }
    
    function haeOmatRyhmaID($idmiitti) {
        return DB::run('SELECT * FROM mp_miitti WHERE idmiitti = ?;',[$idmiitti])->fetch();
    }

    function haeOmatRyhmat($idhenkilo) {
        return DB::run('SELECT * FROM mp_ryhma WHERE idryhma IN (SELECT idryhma FROM mp_henkiloryhma WHERE idhenkilo = ?)',[$idhenkilo])->fetchAll();
    }

    function haeRyhmaTiedot() {
        return DB::run('SELECT * FROM mp_ryhma ORDER BY ryhmanimi;')->fetchAll();
    }

    function haeRyhmaIDlla($id) {
        return DB::run('SELECT * from mp_ryhma WHERE idryhma = ?', [$id])->fetch();
    }

    function haeRyhmaYp($id) {
        return DB::run('SELECT * FROM mp_ryhma WHERE idryhma =?', [$id])->fetch();
    }

    function lisaaRyhma($ypid,$ryhmanimi,$ryhmakuvaus,$jasenmaara,$perustaja,$perustajaemail) {
        DB::run('INSERT INTO mp_ryhma (ypid, ryhmanimi, ryhmakuvaus, jasenmaara, perustaja, perustajaemail) VALUE (?,?,?,?,?,?);',[$ypid,$ryhmanimi,$ryhmakuvaus,$jasenmaara,$perustaja,$perustajaemail]);
        return DB::lastInsertId();
    }

    function lisaaMuokattuRyhma($ryhmanimi,$ryhmakuvaus,$idryhma) {
        return DB::run('UPDATE mp_ryhma SET uusinimi = ?, uusikuvaus = ? WHERE idryhma = ?',[$ryhmanimi,$ryhmakuvaus,$idryhma])->rowCount();
    }

    function paivitaRyhma($ryhmanimi,$ryhmakuvaus,$idryhma) {
        return DB::run('UPDATE mp_ryhma SET ryhmanimi = ?, ryhmakuvaus = ? WHERE idryhma = ?',[$ryhmanimi,$ryhmakuvaus,$idryhma])->rowCount();
    }

    function tyhjennaRyhmaPaivitystieto($idryhma) {
        return DB::run('UPDATE mp_ryhma SET uusinimi = NULL, uusikuvaus = NULL WHERE idryhma = ?',[$idryhma])->rowCount();
    }

    function haeRyhmaNimella($ryhmanimi) {
        return DB::run('SELECT * FROM mp_ryhma WHERE ryhmanimi = ?;', [$ryhmanimi])->fetchAll();
    }

    function paivitaHyvavain($avain,$ryhmanimi) {
        return DB::run('UPDATE mp_ryhma SET hyvavain = ? WHERE ryhmanimi = ?', [$avain,$ryhmanimi])->rowCount();
    }

    function vahvistaRyhma($avain) {
        return DB::run('UPDATE mp_ryhma SET hyvaksytty = TRUE WHERE hyvavain = ?', [$avain])->rowCount();
    }

    function varmistaAvain($avain) {
        return DB::run('SELECT * FROM mp_ryhma WHERE hyvavain = ?', [$avain])->rowCount();
    }

    function tarkistaPaivitys($idryhma) {
        return DB::run('SELECT * FROM mp_ryhma WHERE idryhma = ? AND uusinimi IS NULL AND uusikuvaus IS NULL',[$idryhma])->rowCount();
    }

    function haeIdAvaimella($avain) {
        return DB::run('SELECT * FROM mp_ryhma WHERE hyvavain = ?', [$avain])->fetch();
    }

    function lisaaHakemus($idhenkilo,$nimimerkki,$email,$idryhma,$avain) {
        DB::run('INSERT INTO mp_hakemus (idhenkilo,nimimerkki,email,idryhma,avain) VALUE (?,?,?,?,?);',[$idhenkilo,$nimimerkki,$email,$idryhma,$avain]);
        return DB::lastInsertId();
    }

    function haeHakemus($idhenkilo,$idryhma) {
        return DB::run('SELECT * FROM mp_hakemus WHERE idhenkilo = ? AND idryhma = ?',[$idhenkilo,$idryhma])->rowCount();
    }

    function haeHakemusAvaimella($avain) {
        return DB::run('SELECT * FROM mp_hakemus WHERE avain = ?', [$avain])->fetch();
    }

    function poistaHakemus($avain) {
        return DB::run('DELETE FROM mp_hakemus WHERE avain = ?', [$avain])->rowCount();
    }

    function haeJasenetIDlla($id) {
        return DB::run('SELECT * from mp_henkilo WHERE idhenkilo IN (SELECT idhenkilo FROM mp_henkiloryhma WHERE idryhma = ?)', [$id])->fetchAll();
    }

    function lisaaJasenmaara($idryhma) {
        return DB::run('UPDATE mp_ryhma SET jasenmaara = jasenmaara + 1 WHERE idryhma = ?', [$idryhma])->rowCount();
    }

    function vahennaJasenmaara($idryhma) {
        return DB::run('UPDATE mp_ryhma SET jasenmaara = jasenmaara - 1 WHERE idryhma = ?', [$idryhma])->rowCount();
    }
?>