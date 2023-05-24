<?php
/*
    @author Ondrej Laga
    @date 7.5.2011
*/
$mapBible = array(
    'Gn' => array ('01', 'genesis'), //'1. Moj��ova',
    'Ex' => array ('02', 'exodus'), //'2. Moj��ova',
    'Lv' => array ('03', 'leviticus'), //'3. Moj��ova',
    'Nm' => array ('04', 'numeri'), //'4. Moj��ova',
    'Dt' => array ('05', 'deuteronomium'), //'5. Moj��ova',
    'Joz' => array ('06', 'jozue'), //'Jozue',
    'Sd' => array ('07', 'soudcu'), //'Soudcu',
    'Rt' => array ('08', 'rut'), //'R�t',
    '1Sam' => array ('09', '1 samuelova'), //'1. Samuelova',
    '2Sam' => array ('10', '2 samuelova'), //'2. Samuelova',
    '1Kral' => array ('11', '1 kralovska'), //'1. Kr�lovsk�',
    '2Kral' => array ('12', '2 kralovska'), //'2. Kr�lovsk�',
    '1Kron' => array ('13', '1 paralipomenon'), //'1. Paralipomenon',
    '2Kron' => array ('14', '2 paralipomenon'), //'2. Paralipomenon',
    'Ez' => array ('15', 'ezdras'), //'Ezdr�',
    'Neh' => array ('16', 'nehemjas'), //'Nehemi�',
    'Est' => array ('17', 'ester'), //'Ester',   ???????????
    'Job' => array ('18', 'job'), //'J�b',
    'Zl' => array ('19', 'zalmy'), //'�almy',
    '�l' => array ('19', 'zalmy'), //'�almy',
    'Pr' => array ('20', 'prislovi'), //'Pr�slov�',
    'Kaz' => array ('21', 'kazatel'), //'Kazatel',  ?????????
    'P�s' => array ('22', 'pisen pisni'), //'P�sen �alomounova',
    'Iz' => array ('23', 'izajas'), //'Izaj�',
    'Jer' => array ('24', 'jeremjas'), //'Jeremj�',
    'PL' => array ('25', 'plac'), //'Pl�c Jeremj�uv', ?????????
    'Ez' => array ('26', 'ezechiel'), //'Ezechiel',
    'Dan' => array ('27', 'daniel'), //'Daniel',
    'Oz' => array ('28', 'ozeas'), //'Oze�',
    'Jl' => array ('29', 'joel'), //'J�el',
    'Am' => array ('30', 'amos'), //'�mos',
    'Abd' => array ('31', 'abdijas'), //'Abdij�', ???????????
    'Jon' => array ('32', 'jonas'), //'Jon�',
    'Mich' => array ('33', 'micheas'), //'Miche�',
    'Na' => array ('34', 'nahum'), //'Nahum',  ?????????????
    'Abk' => array ('35', 'abakuk'), //'Abakuk', ????????
    'Sof' => array ('36', 'sofonjas'), //'Sofonj�',
    'Ag' => array ('37', 'ageus'), //'Ageus', ??????????
    'Zach' => array ('38', 'zacharjas'), //'Zacharj�',
    'Mal' => array ('39', 'malachias'), //'Malachi�',
    /* --- Nov� z�kon ----*/
    'Mt' => array ('40', 'matous'), //'Matou�',
    'Mk' => array ('41', 'marek'), //'Marek',
    'Lk' => array ('42', 'lukas'), //'Luk�',
    'Jan' => array ('43', 'jan'), //'Jan',
    'Sk' => array ('44', 'skutky apostolu'), //'Skutky apo�tolu',
    'Rim' => array ('45', 'rimanum'), //'R�manum',
    '1Kor' => array ('46', '1 korintskym'), //'1. Korintsk�m',
    '2Kor' => array ('47', '2 korintskym'), //'2. Korintsk�m',
    'Gal' => array ('48', 'galatskym'), //'Galatsk�m',
    'Ef' => array ('49', 'efezskym'), //'Efezsk�m',
    'Flp' => array ('50', 'filipskym'), //'Filipsk�m',   http://www.bibleserver.com/go.php?lang=cz&bible=CEP&ref=%41%203     F 3
    'Kol' => array ('51', 'koloskym'), //'Kolosk�m',
    '1Sol' => array ('52', '1 tesalonickym'), //'1. Tesalonick�m',
    '2Sol' => array ('53', '2 tesalonickym'), //'2. Tesalonick�m',
    '1Tim' => array ('54', '1 timoteovi'), //'1. Timoteovi',
    '2Tim' => array ('55', '2 timoteovi'), //'2. Timoteovi',
    'Tit' => array ('56', 'titovi'), //'Titovi',
    'Fil' => array ('57', 'filemonovi'), //'Filemonovi', // ??????????
    'Fm' => array ('57', 'filemonovi'), //'Filemonovi', // ??????????
    'Zid' => array ('58', 'zidum'), //'�idum',
    'Zd' => array ('58', 'zidum'), //'�idum',
    'Jak' => array ('59', 'list jakubuv'), //'Jakub',
    '1Petr' => array ('60', '1 petruv'), //'1. Petr',
    '1Pt' => array ('60', '1 petruv'), //'1. Petr',
    '2Petr' => array ('61', '2 petruv'), //'2. Petr',
    '2Pt' => array ('61', '2 petruv'), //'2. Petr',
    '1Jan' => array ('62', '1 januv'), //'1. Jan',
    '2Jan' => array ('63', '2 januv'), //'2. Jan',
    '3Jan' => array ('64', '3 januv'), //'3. Jan',
    'Ju' => array ('65', 'list juduv'), //'Juda', ?????????
    'Jud' => array ('65', 'list juduv'), //'Juda', ?????????
    'Zj' => array ('66', 'zjeveni janovo'), //'Zjeven�',
    'Tob' => array ('90', ''),
    //'' => '91', //J�dit
    '1Mak' => array ('92', '1 makabejska'), //ok
    '2Mak' => array ('93', '2 makabejska'), //ok
    'Mdr' => array ('94', 'kniha moudrosti'), //ok
    'Sir' => array ('95', 'sirachovec'),
    'Bar' => array ('96', '')
);