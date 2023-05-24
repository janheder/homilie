<?
error_reporting(E_ERROR | E_WARNING | E_PARSE);
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

require_once("./db_connect.php");
require_once("./lang.php");
require_once("./funkce.php");
globalni_pr($spojeni);


// prihlaseni
if($_POST['prihlaseni_uz_jm'] && $_POST['prihlaseni_heslo'])
{ 
// kontrola uzivatele
$query_kontrola = MySQLi_Query($spojeni,"SELECT * FROM registrace 
WHERE uz_jmeno='".addslashes($_POST['prihlaseni_uz_jm'])."' AND heslo=MD5('".addslashes($_POST['prihlaseni_heslo'])."') AND aktivni=1") or die(err(1));
$row_kontrola  = MySQLi_fetch_object($query_kontrola);
  if(mysqli_num_rows($query_kontrola))
  {	

	 $prihlaseni_sess = base64_encode(strip_tags($_POST['prihlaseni_uz_jm'])."|".md5(strip_tags($_POST['prihlaseni_heslo']))."|".$row_kontrola->jmeno_prijmeni."|".$row_kontrola->id);
	 $_SESSION['prihlaseni'] = $prihlaseni_sess;

  $check = time();
  }
  else
  {
  $check = 'false';
  }

Header('Location: '.$_SERVER['HTTP_REFERER'].'?check='.$check);
}
?>
