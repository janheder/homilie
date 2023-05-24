<?
error_reporting(E_ERROR | E_PARSE | E_WARNING); 
/*
ini_set('display_errors', true);
ini_set('xdebug.max_nesting_level', 512);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 10240);
*/
setlocale(LC_ALL, 'cs_CZ.UTF-8');
if(!isset($_SESSION['prihlaseni']))
{
	session_name("H_Prezentacni_vrstva");
}
session_start();
$p = strip_tags(addslashes($_GET['p']));
if(!strip_tags(addslashes($_GET['lang'])))
{
define("__LANG__","cz");
}
else
{
define("__LANG__",strip_tags(addslashes($_GET['lang'])));
}

require_once("./skripty/db_connect.php");
require_once("./skripty/lang.php");
require_once("./skripty/funkce.php");
globalni_pr($spojeni);


if(!$p)
{
// str uvodni stranky
$p = 'uvod';
}

if(@in_array($p, array_flip($menu_db))) 
{
// pokud se jedna o stranku ze staticky stranek a ma vyplneny potrebne udaje, nahradime slogan, title a keywords
$menu_seo = MySQLi_Query($spojeni,"SELECT title, description, keywords, nadpis, podnadpis, pozadi FROM stranky WHERE str='".addslashes($p)."'");
$row_seo = MySQLi_fetch_object($menu_seo);
}
if($p=='aktuality' && $_GET['ida'])
{
$menu_seo_n = MySQLi_Query($spojeni,"SELECT title, description, keywords FROM aktuality WHERE id='".intval($_GET['ida'])."'");
$row_seo_n = MySQLi_fetch_object($menu_seo_n);
}

$nazev = "nazev_".__LANG__;
?>
<!DOCTYPE html>

<html class='visible' lang='cs'>

<head>

<meta charset='utf-8'>

<meta content='IE=edge' http-equiv='X-UA-Compatible'>

<meta content='width=device-width, initial-scale=1' name='viewport'>

<title><? 
if($row_seo->title)
{
echo stripslashes($row_seo->title);
}
elseif($p=='aktuality' && $_GET['ida'] && $row_seo_n->title)
{
echo stripslashes($row_seo_n->title);
}
elseif($p=='homilie' && $_GET['idh'])
{ 
	$menu_seo_h = MySQLi_Query($spojeni,"SELECT $nazev FROM homilie WHERE id='".intval($_GET['idh'])."'");
	$row_seo_h = MySQLi_fetch_object($menu_seo_h);
	echo stripslashes($row_seo_h->$nazev);
	echo " | Homilie.eu";
}
elseif($p=='texty' && $_GET['idt'])
{ 
	$menu_seo_h = MySQLi_Query($spojeni,"SELECT $nazev FROM texty WHERE id='".intval($_GET['idt'])."'");
	$row_seo_h = MySQLi_fetch_object($menu_seo_h);
	echo stripslashes($row_seo_h->$nazev);
	echo " | Homilie.eu";
}
else
{
get_title_header($p,$menu_all,$spojeni);
echo " | Homilie.eu";
}
?></title>

<meta content='index,follow,snippet,archive' name='robots'>

<meta content='<? 
if($row_seo->description)
{
echo stripslashes($row_seo->description);
}
elseif($p=='aktuality' && $_GET['ida'] && $row_seo_n->description)
{
echo stripslashes($row_seo_n->description);
}
elseif($p=='produkty' && $_GET['idk'] && $row_seo_k->description)
{
echo stripslashes($row_seo_k->description);
}
else
{
echo __DESCRIPTION__;
}
?>' name='description'>

<meta content='<? 
if($row_seo->keywords)
{
echo stripslashes($row_seo->keywords);
}
elseif($p=='aktuality' && $_GET['ida'] && $row_seo_n->keywords)
{
echo stripslashes($row_seo_n->keywords);
}
elseif($p=='produkty' && $_GET['idk'] && $row_seo_k->keywords)
{
echo stripslashes($row_seo_k->keywords);
}
else
{
echo __KEYWORDS__;
}
?>' lang='cs' name='keywords'>

<meta content='eline.cz' name='author'>

<meta content='width=device-width, initial-scale=1.0, maximum-scale=1, minimum-scale=1' name='viewport'>

<link href='https://homilie.eu' rel='canonical'>

<meta content='cs_CZ' property='og:locale'>

<meta content='website' property='og:type'>

<meta content='Homílie.eu - Homiletický web pro kněze' property='og:title'>

<meta content='Popis webové stránka' property='og:description'>

<meta content='https://homilie.eu' property='og:url'>

<meta content='Homílie.eu' property='og:site_name'>

<meta content='summary' name='twitter:card'>

<meta content='Homílie.eu - Homiletický web pro kněze' name='twitter:title'>

<meta content='Popis webové stránky' name='twitter:description'>

<link href='/images/favicons/favicon.png' rel='icon'>

<link href='/images/favicons/apple-touch-icon.png' rel='apple-touch-icon'>

<link href='/images/favicons/apple-touch-icon-57x57.png' rel='apple-touch-icon' sizes='57x57'>

<link href='/images/favicons/apple-touch-icon-72x72.png' rel='apple-touch-icon' sizes='72x72'>

<link href='/images/favicons/apple-touch-icon-76x76.png' rel='apple-touch-icon' sizes='76x76'>

<link href='/images/favicons/apple-touch-icon-114x114.png' rel='apple-touch-icon' sizes='114x114'>

<link href='/images/favicons/apple-touch-icon-120x120.png' rel='apple-touch-icon' sizes='120x120'>

<link href='/images/favicons/apple-touch-icon-144x144.png' rel='apple-touch-icon' sizes='144x144'>

<link href='/images/favicons/apple-touch-icon-152x152.png' rel='apple-touch-icon' sizes='152x152'>

<link href='/images/favicons/apple-touch-icon-180x180.png' rel='apple-touch-icon' sizes='180x180'>

<!-- Bootstrap -->

<link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css' id='loadBootstrap' onerror='loadBootstrap()' rel='stylesheet'>

<script>

  function loadBootstrap() {

  	$( "#loadBootstrap" ).append('<link href="/css/bootstrap.min.css" rel="stylesheet">');

  }

</script>

<!-- Font Awesome -->

<link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' id='loadFontAwesome' onerror='loadFontAwesome()' rel='stylesheet'>

<script>

  function loadFontAwesome() {

  	$( "#loadFontAwesome" ).append('<link href="/css/font-awesome.min.css" rel="stylesheet">');

  }

</script>

<!-- Custom CSS -->

<link href='/css/lightbox.min.css' rel='stylesheet'>

<link href='/css/jquery.cookiebar.css' rel='stylesheet'>
<link href='/css/swiffy-slider.min.css' rel='stylesheet'>
<link href='/css/style.css?v=13' rel='stylesheet'>


<style>
.slider-nav{
  visibility: visible;
  color: black;
  filter: none;
}
.slider-nav::after{
  background-color: black;
  box-shadow: none;
}
.dailyWord .slider-container{
  padding-bottom: 10px;
}

@media screen and (max-width: 991px){
  .slider-container{
    display: flex;
        align-items: flex-start;
        flex-direction: column;
  }
  .slider-container> div{
    width: 100%;
    flex-shrink: 0;
  }
}

</style>

<!-- Fonts -->

<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&amp;subset=latin-ext" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Lora:400i' rel='stylesheet'>
<link href='https://fonts.googleapis.com/css?family=PT+Sans:400' rel='stylesheet'> 


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->

<link href='/css/ie10-viewport-bug-workaround.css' rel='stylesheet'>

<script src='/js/ie-emulation-modes-warning.js'></script>


<script src='/js/swiffy-slider.min.js'></script>
<script src='/js/smoothscroll.min.js'></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->

<!--[if lt IE 9]>

<script src='https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js'></script>

<script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>

<![endif]-->

<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>

<script>

  window.jQuery || document.write('<script src="/js/jquery.min.js">\x3C/script>')

</script>


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113358726-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-113358726-1');
</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script> 
  </head>

  <body>

    
<header>

<nav class='navbar navbar-expand-lg'>

<div class='navbar-brand-wrap'>

<a class='navbar-brand' href='/'>

<img alt='Homílie.eu' src='/images/logo.svg' title='Homílie.eu'>

</a>

</div>

<div class='search-wrap-responsive btn btn-ghost-white btn-search' id='search-toggler-responsive'>

<span class='search-toggler-icon'></span>

</div>

<div aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation' class='navbar-toggler' data-target='#navbarNav' data-toggle='collapse' role='button'>

<span class='navbar-toggler-icon'></span>

</div>

<div class='collapse navbar-collapse' id='navbarNav'>

<ul class='navbar-nav'>
<?
odkazy($p,$spojeni);
?>
</ul>

<div class='nav-right'>

<div class='search-wrap'>

<div class='btn btn-ghost-white btn-search' id='search-toggler'>

<span class='search-toggler-icon'></span>

</div>

</div>

<div class='lang-wrap'>

<div class='dropdown'>

<button aria-expanded='false' aria-haspopup='true' class='btn btn-ghost-white dropdown-toggle' data-toggle='dropdown' id='langDropdown' type='button'>

<img alt='Jazyk' src='/images/lang-icon.svg'>

<img alt='Jazyk' class='lang-icon-black' src='/images/lang-icon-black.svg'>
<?
if(__LANG__=='sk'){echo 'Slovenčina';}
else{ echo 'Čeština';}
?>


</button>

<div aria-labelledby='langDropdown' class='dropdown-menu'>

<a class='dropdown-item' href='/'>

Čeština

</a>

<a class='dropdown-item' href='/sk/uvod.html'>

Slovenčina

</a>

</div>

</div>

</div>

<div class='user-wrap'>

<div class='dropdown'>

<button aria-expanded='false' aria-haspopup='true' class='btn btn-white dropdown-toggle' data-toggle='dropdown' id='userDropdown' type='button'>

<img alt='Uživatel' src='/images/user-icon.svg'>
<?
if($_SESSION['prihlaseni'])
{
	list($uz_jm_sess,$heslo_sess,$jmeno_sess,$id_zak_sess) = explode("|",base64_decode($_SESSION['prihlaseni']));
	
	echo strip_tags($jmeno_sess);
	
echo "</button>

<div aria-labelledby='userDropdown' class='dropdown-menu dropdown-menu-right'>

<a class='dropdown-item' href=\"/".__LANG__."/uprava-reg-udaju.html\">".__UPRAVA_UDAJU__."</a>
<a class='dropdown-item' href=\"/".__LANG__."/platnost.html\">ID a platnost</a>
<a class='dropdown-item' href=\"/skripty/odhlasit.php\">".__ODHLASENI__."</a>



</div>	";
}
else
{
echo __UZIVATEL__;
echo "</button>

<div aria-labelledby='userDropdown' class='dropdown-menu dropdown-menu-right'>

<a class='dropdown-item' href=\"/".__LANG__."/prihlaseni.html\">".__PRIHLASENI__."


</a>

<a class='dropdown-item' href=\"/".__LANG__."/registrace.html\">".__REGISTRACE__."

</a>

<a class='dropdown-item' href=\"/".__LANG__."/zapomenute-heslo.html\">".__ZAPOMENUTE_UDAJE__."

</a>

</div>	";
}
?>



</div>

</div>

</div>

</div>

</nav>

<div class='search-form-wrap'>

<div class='search-form'>

<div class='container'>

<form method='post' action="/<? echo __LANG__;?>/vyhledavani.html">

<input id='search-box' name='h_text' placeholder='Zde napište, co hledáte...' type='text'>

<button class='btn' id='search-button' name='search-button' type='submit'>

<? echo __VYHLEDAT__;?>

</button>

</form>

</div>

</div>

</div>

<span aria-label='Toggle navigation' data-target='#navbarNav' data-toggle='collapse' id='dark-overlay'></span>

</header>

<?
 
if($p=='uvod')
{
      include_once("./skripty/uvod.php");
      MySQLi_Query ($spojeni,"UPDATE stranky SET precteno=precteno+1 WHERE str='$p' AND lang='".__LANG__."'") or die(err(3));
     	
	
}
else
{
	include_once("./skripty/zbytek.php");
}
?>



<section class='quote post__image-container'>

<div class='container'>

<div class='quote-wrap'>

<?
// nahodny uryvek
$query_ur = MySQLi_Query($spojeni,"SELECT * FROM uryvky WHERE aktivni=1 AND lang='".__LANG__."' ORDER BY razeni ASC LIMIT 1") or die(err(1));
$row_ur = MySQLi_fetch_object($query_ur);

echo "<h4>

".stripslashes($row_ur->uryvek)."

</h4>

<p>
".stripslashes($row_ur->autor)."

</p>";

?>


</div>

</div>

<img alt='Úryvek' class='post__featured-image' data-src='/img/quote-bg.jpg' src='/img/lazy/lazy-quote-bg.jpg'>

</section>



<section class='news'>

<div class='container'>

<div class='section-heading'>

<h2>

<? echo __POSLEDNI_HOMILIE__;?>

</h2>

<img alt='Homilie' src='/images/separator-dove.svg'>

</div>

<div class='row'>

<div class='col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6'>

<figure class='news-img'>

<img alt='Novinky' data-src='/img/news-img.jpg' src='/img/lazy/lazy-news-img.jpg' title='Novinky'>

</figure>

</div>

<div class='col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6'>

<div class='articles-wrap'>

<?

novinky_uvod(2,$spojeni);

?>


<a class='btn btn-ghost' href='/<? echo __LANG__;?>/homilie.html'><? echo __VICE_HOMILII__;?></a>

</div>

</div>

</div>

</div>

</section>



<section class='cta post__image-container'>

<div class='container'>

<div class='row'>

<div class='col-12 col-xs-12 col-sm-12 col-md-12 col-lg-6'>

<h2>

<b>

<? echo __ZAREGISTRUJTE_SE__;?>

</b>

<? echo __A_ZISKEJTE_PRISTUP__;?>

</h2>

<p>

<? echo __KAZDY_REGISTROVANY_CLEN__;?>

</p>

<a class='btn btn-white' href='/<? echo __LANG__;?>/registrace.html'>

<? echo __VYTVORIT_UCET__;?>

</a>

<a class='btn btn-ghost-white' href='/<? echo __LANG__;?>/uvodem.html'>

<? echo __VICE_INFORMACI__;?>

</a>

</div>

</div>

</div>

<img alt='Zaregistrujte se' class='post__featured-image' data-src='/img/cta-bg.jpg' src='/img/lazy/lazy-cta-bg.jpg'>

</section>



<footer>

<div class='container'>

<div class='row'>

<div class='col col-12 col-xs-12 col-sm-12 col-md-5 col-lg-5'>
<?
// o projektu 
$query_op = MySQLi_Query($spojeni,"SELECT * FROM stranky WHERE aktivni=1 AND lang='".__LANG__."' AND str='o-projektu' ") or die(err(1));
$row_op = MySQLi_fetch_object($query_op);

echo "<h5>

".stripslashes($row_op->nadpis)."

</h5>

<b>";

if(__LANG__=='sk')
{
	echo stripslashes(__O_PROJEKTU_SK__);
}
else
{
	echo stripslashes(__O_PROJEKTU_CZ__);
}



echo "</b>";

?>


</div>

<div class='col'>

<h5>

<? echo __NAVIGACE__;?>
</h5>
<?
$query_nav = MySQLi_Query($spojeni,"SELECT * FROM stranky WHERE aktivni=1 AND lang='".__LANG__."' ORDER BY razeni") or die(err(1));
while($row_nav = MySQLi_fetch_object($query_nav))
{
  
  echo "<a href='/".__LANG__."/".$row_nav->str.".html'>".stripslashes($row_nav->nadpis_menu)."</a>";
  	
}
?>


</div>

<div class='col'>

<h5>

<? echo __UZIVATEL__;?>

</h5>

<a href='/<? echo __LANG__;?>/prihlaseni.html'>

<? echo __PRIHLASENI__;?>

</a>

<a href='/<? echo __LANG__;?>/registrace.html'>

<? echo __REGISTRACE__;?>

</a>

<a href='/<? echo __LANG__;?>/zapomenute-heslo.html'>

<? echo __ZAPOMENUTE_UDAJE__;?>

</a>

</div>

<div class='col'>

<h5>

Kontakt

</h5>

<p>

Jan Lisowski

</p>

<p>

420 720 366 277

</p>

<p>

info@homilie.eu

</p>

<br>

<a href='/<? echo __LANG__;?>/kontakty.html'>

<? echo __VICE_KONTAKTU__;?>

</a>

</div>

</div>

</div>

</footer>

<section class='copyright'>

<div class='container'>

<h5>

Copyright © 2017

<a href='/'>

homilie.eu

</a>

Všechna práva vyhrazena.

</h5>

<h5 class='copyright-pull'>

Vytvořeno v

<a href='http://www.eline.cz'>

eline.cz

</a>

</h5>

</div>

</section>



<!-- Popper for Bootstrap support -->

<script src='/js/popper.min.js'></script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/unveil/1.3.0/jquery.unveil.min.js'></script>

<script>

  if(typeof($.fn.unveil) === 'undefined') {

  document.write('<script src="/js/jquery.unveil.js">\x3C/script>')}

</script>

<!-- Bootstrap core JavaScript -->

<script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js'></script>

<script>

  if(typeof($.fn.modal) === 'undefined') {

  document.write('<script src="/js/bootstrap.min.js">\x3C/script>')}

</script>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->

<script src='/js/ie10-viewport-bug-workaround.js'></script>

<!-- Custom js libraries -->

<script src='/js/lightbox.min.js'></script>

<script src='/js/jquery.cookiebar.js'></script>

<!-- Custom script for this website -->

<script src='/js/functions.min.js?v=19'></script>



</body>

</html>


<?
mysqli_Close($spojeni);
?>
