<?php
    require_once HELPERS_DIR . 'DB.php';

    function lisaaHenkilo($etunimi,$sukunimi,$nimimerkki,$email,$salasana) {
        DB::run('INSERT INTO mp_henkilo (etunimi, sukunimi, nimimerkki, email, salasana) VALUE (?,?,?,?,?);',[$etunimi,$sukunimi,$nimimerkki,$email,$salasana]);
        return DB::lastInsertId();
    }

    function haeHenkiloSahkopostilla($email) {
        return DB::run('SELECT * FROM mp_henkilo WHERE email = ?;', [$email])->fetchAll();
    }

    function haeHenkiloNimimerkilla($nimimerkki) {
        return DB::run('SELECT * FROM mp_henkilo WHERE nimimerkki = ?;', [$nimimerkki])->fetch();
    }

    function haeHenkilo($email) {
        return DB::run('SELECT * FROM mp_henkilo WHERE email = ?;', [$email])->fetch();
    }

    function paivitaVahvavain($email,$avain) {
        return DB::run('UPDATE mp_henkilo SET vahvavain = ? WHERE email = ?', [$avain,$email])->rowCount();
    }

    function vahvistaTili($avain) {
        return DB::run('UPDATE mp_henkilo SET vahvistettu = TRUE WHERE vahvavain = ?', [$avain])->rowCount();
    }

    function asetaVaihtoavain($email,$avain) {
        return DB::run('UPDATE mp_henkilo SET nollausavain = ?, nollausaika = NOW() + INTERVAL 30 MINUTE WHERE email = ?', [$avain,$email])->rowCount();
    }

    function tarkistaVaihtoavain($avain) {
        return DB::run('SELECT nollausavain, nollausaika-NOW() AS aikaikkuna FROM mp_henkilo WHERE nollausavain = ?', [$avain])->fetch();
    }

    function vaihdaSalasanaAvaimella($salasana,$avain) {
        return DB::run('UPDATE mp_henkilo SET salasana = ?, nollausavain = NULL, nollausaika = NULL WHERE nollausavain = ?', [$salasana,$avain])->rowCount();
    }

    function haeKaikkiHenkilot() {
        return DB::run('SELECT * FROM mp_henkilo;')->fetchAll();
    }

    function haeHenkilonRyhmat($idhenkilo) {
        return DB::run('SELECT * FROM mp_henkiloryhma WHERE idhenkilo = ?', [$idhenkilo])->fetch();
    }

    function haeHenkilonRyhmienMaara($idhenkilo) {
        return DB::run('SELECT COUNT(*) FROM mp_henkiloryhma WHERE idhenkilo = ?', [$idhenkilo])->fetchColumn();
    }

    function haeHenkilonYllapidettavienMaara($idhenkilo) {
        return DB::run('SELECT COUNT(*) FROM mp_yllapitaja WHERE idhenkilo = ?', [$idhenkilo])->fetchColumn();
    }

    function haeHenkilonKommenttienMaara($idhenkilo) {
        return DB::run('SELECT COUNT(*) FROM mp_kommentti WHERE idhenkilo = ?', [$idhenkilo])->fetchColumn();
    }

    function haeHenkilonIlmoittautumisMaara($idhenkilo) {
        return DB::run('SELECT COUNT(*) FROM mp_ilmo WHERE idhenkilo = ?', [$idhenkilo])->fetchColumn();
    }

    function haeAdminSahkoposti() {
        $row = DB::run('SELECT email FROM mp_henkilo WHERE admin = True')->fetch();
        return $row['email'];
    }

    function haePerustajaSahkopostilla($email) {
        return DB::run('SELECT * FROM mp_henkilo WHERE email = ?;', [$email])->fetch();
    }

    function lisaaHenkiloRyhmaan($idhenkilo,$idryhma) {
        DB::run('INSERT INTO mp_henkiloryhma (idhenkilo, idryhma) VALUE (?,?);',[$idhenkilo,$idryhma]);
        return DB::lastInsertId();
    }

    function poistaIDllaRyhmasta($idryhma,$idhenkilo) {
        return DB::run('DELETE FROM mp_henkiloryhma WHERE idryhma = ? AND idhenkilo = ?',[$idryhma,$idhenkilo])->rowCount();
    }

    function lisaaHenkiloYllapitajiin($idhenkilo,$idryhma) {
        DB::run('INSERT INTO mp_yllapitaja (idhenkilo, idryhma) VALUE (?,?);',[$idhenkilo,$idryhma]);
        return DB::lastInsertId();
    }

    function haeHenkilonYp($idhenkilo) {
        return DB::run('SELECT * FROM mp_yllapitaja WHERE idhenkilo = ?',[$idhenkilo])->fetch();
    }

    function haeHenkilonYpRyhmat($idhenkilo) {
        return DB::run('SELECT * FROM mp_ryhma WHERE ypid = ?',[$idhenkilo])->fetchAll();
    }

    function haeHenkilonYpJasenet($idhenkilo) {
        return DB::run('SELECT * from mp_henkilo WHERE idhenkilo IN (SELECT idhenkilo FROM mp_henkiloryhma WHERE idryhma IN (SELECT idryhma FROM mp_ryhma WHERE ypid = ?))',[$idhenkilo])->fetchAll();
    }
?>