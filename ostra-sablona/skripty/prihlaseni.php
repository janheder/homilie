<?
// prihlaseni


echo "<section class='page-heading-small'></section>
<main>
<div class='container'>
<article class='form'>
<h2>
".__PRIHLASENI__."
</h2>
<div class='row'>
<div class='col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12'>";

if($_GET['check'])
{
 if($_GET['check']=='false')
 {
 echo '<span class="r">Zadané údaje nesouhlasí.</span><br />Pokud si nepamatujete své heslo, můžete si ho nechat zaslat na svou e-mailovou adresu <a href="/'.__LANG__.'/zapomenute-heslo.html">zde</a>.';
 }
 else
 {
 echo __PRIHLASENI_PROBEHLO_USPESNE__;
 
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
 

 echo '<br><b><a href="/">'.__POKRACUJTE_NA_HOMEPAGE__.'</b></a>';
 }
}
else
{
echo "<form method='post' action='/skripty/prihlaseni2.php'>
<div class='form-group'>
<label for='form-uz-jmeno'>
".__UZ_JMENO__.":
</label>
<input class='form-control' id='form-uz-jmeno' name='prihlaseni_uz_jm' required type='text'>
</div>
<div class='form-group'>
<label for='form-password'>
Heslo:
</label>
<input class='form-control' id='form-password' name='prihlaseni_heslo' required type='password'>
</div>
<div class='form-group'>
<a href='/".__LANG__."/zapomenute-heslo.html'>
".__ZAPOMENUTE_HESLO__."?
</a>
</div>
<div class='form-group'>
<button class='btn' type='submit'>
".__PRIHLASIT__."
</button>
</div>
<div class='form-group'>
<a href='/".__LANG__."/registrace.html'>
".__NEMATE_UCET__."? ".__REGISTROVAT__."
</a>
</div>
</form>";
}


echo "</div>
</div>
</article>
</div>
</main>
";

?>
