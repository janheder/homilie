<?
$dbuser	= "c2homilieeu";
$dbpasswd = "rcnjsNFY#F2S3";
$db = "c2homilieeu";

$db_host = "localhost";
@$spojeni = mysqli_Connect($db_host , $dbuser , $dbpasswd, $db) or die("Nepodarilo se pripojit k databazi.");



mysqli_set_charset ($spojeni , 'utf8' );

/*
MySQL_Select_DB($db);

mysql_query("SET character_set_results=UTF8");
mysql_query("SET character_set_connection=UTF8");
mysql_query("SET character_set_client=UTF8");
mysql_query("SET NAMES 'utf8'");
*/
?>
