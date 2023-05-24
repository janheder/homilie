<?
// platnost


echo "<section class='page-heading-small'></section>
<main>
<div class='container'>
<article class='form'>
<h2>
Platnost
</h2>
<div class='row'>
<div class='col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12'>";

 // úprava z 10.7.2020
 if($_SESSION['prihlaseni'])
 {
	list($uz_jm_sess,$heslo_sess,$jmeno_sess,$id_zak_sess) = explode("|",base64_decode($_SESSION['prihlaseni']));
	
	$query_pu = MySQLi_Query($spojeni,"SELECT * FROM registrace 
	WHERE id='".intval($id_zak_sess)."' AND aktivni=1") or die(err(1));
	$row_pu  = MySQLi_fetch_object($query_pu);
	
	 echo '<br>Vaše ID je: '.$row_pu->id2;
	 echo '<br>Váš účet bude aktivní do: '.date('d.m.Y',$row_pu->platnost_do);
 
 }	
 

echo "</div>
</div>
</article>
</div>
</main>
";

?>
