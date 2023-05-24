<?
error_reporting(E_ERROR | E_WARNING | E_PARSE);
setlocale(LC_ALL, 'cs_CZ.UTF-8');
session_name("H_Prezentacni_vrstva");
session_start();
 
  if($_SESSION['prihlaseni'])
  {
  // odhlaseni
  unset($_SESSION['prihlaseni']);
  header("Location: ".$_SERVER['HTTP_REFERER']);
  exit();
  
  }
?>
