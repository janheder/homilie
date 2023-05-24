<?
// registrace


echo "<section class='page-heading-small'></section>
<main>
<div class='container'>
<article class='form'>
<h2>
Úprava údajů
</h2>
<div class='row'>
<div class='col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
if(!$_SESSION['prihlaseni'])
{
   echo  'Pro přístup na neveřejnou stránku se musíte nejdříve přihlásit.';
}
else
{


$err = false;

// zpracovani
if($_POST['pokracovat_1'])
{
// kontrola vyplneni

if(!$_POST['jmeno_prijmeni'])
{
$err .= "Nevyplnili jste jméno a příjmení<br />";
}

if(!is_email($_POST['email']))
{
$err .= "Vámi zadaný e-mail je nevalidní<br />";
}

if(!$_POST['heslo'])
{
$err .= "Nevyplnili jste heslo<br />";
}

if($_POST['heslo']!=$_POST['heslo2'])
{
$err .= "Heslo a potvrzení hesla se neshodují<br />";
}



if(!$_POST['dieceze'])
{
$err .= "Nevyplnili jste diecezi<br />";
}





if(!$err)
{
$ip_adr = getip();
// kontakt /////////////////////////////////////////////////////////////////////
MySQLi_Query($spojeni,"UPDATE registrace SET
jmeno_prijmeni='".addslashes($_POST['jmeno_prijmeni'])."',
dieceze='".addslashes($_POST['dieceze'])."',
email='".addslashes($_POST['email'])."',
heslo='".md5(addslashes($_POST['heslo']))."',
ip='$ip_adr'
WHERE id='".intval($id_zak_sess)."'  LIMIT 1") or die(err(3));




// odeslani emailu uzivateli s rekapitulaci zadanych udaju

 $headers = "From:".__EMAIL_1__."\n";
 $headers .= "Return-Path :".__EMAIL_1__."\n";
 $headers .= "Reply-To :".__EMAIL_1__."\n";
 $headers .= "MIME-Version: 1.0\n";
 $headers .= "Content-type: text/plain; charset=utf-8\n";
 $headers .= "Content-Transfer-Encoding: 8bit\n";
 $headers .= "X-Mailer: w-shop powered by PHP / ".phpversion()."\n";
 $headers .= "bcc:".__EMAIL_1__; // BCC
 
 $body = "Datum úpravy registrace: ".date("d.m.Y H:i:s")."\nJméno: ".strip_tags($_POST['jmeno_prijmeni'])."\nE-mail: ".strip_tags($_POST['email'])."\nUživatelské jméno: ".strip_tags($_POST['uz_jmeno'])."\nHeslo: ".strip_tags($_POST['heslo'])."\nDiecéze: ".strip_tags($_POST['dieceze']);



 if(!mail(strip_tags($_POST['email']), "Uprava registrace na ".__URL__, $body,$headers))
  {
    //echo  "<br /><span class=\"r\"><br />Chyba při odesílání e-mailu<br /></span>";
    echo "<p class='alert red'>Chyba při odesílání e-mailu<br /></p>";
  }
  else
  {
	  	
	
	
		 echo "<p class='alert green'>Úprava registrace byla úspěšná.</p>";

	


	
  }
}
else
{  // vypis chyb
	echo "<p class='alert red'>Nastala chyba při vyplňování formuláře<br />".$err."</p>";
  //echo  "<br /><span class=\"r\">".$err."</span><br />";
}

}


if(!$_POST['pokracovat_1'] || ($_POST['pokracovat_1'] && $err))
{
	
	$result3 = mysqli_query($spojeni,"SELECT * FROM registrace WHERE id='".intval($id_zak_sess)."'") or die(err(1));
    $row3 = mysqli_fetch_object($result3);	
	
echo "<form method='post'>
<div class='form-group'>
<label for='form-uz-jmeno'>
".__UZ_JMENO__.":
</label>
<input class='form-control' id='form-uz-jmeno' name='uz_jmeno' value='".$row3->uz_jmeno."' required type='text' readonly >
</div>
<div class='form-group'>
<label for='form-jmeno'>
".__JMENO_PRIJMENI__.":
</label>
<input class='form-control' id='form-jmeno' name='jmeno_prijmeni' value='".$row3->jmeno_prijmeni."' required type='text'>
</div>
<div class='form-group'>
<label for='form-email'>
Email:
</label>
<input class='form-control' id='form-email' name='email' value='".$row3->email."' required type='email'>
</div>
<div class='form-group'>
<label for='form-password'>
Heslo:
</label>
<input class='form-control' id='form-password' name='heslo' required type='password'>
</div>
<div class='form-group'>
<label for='form-password-repeat'>
".__ZOPAKOVAT_HESLO__.":
</label>
<input class='form-control' id='form-password-repeat' name='heslo2' required type='password'>
</div>
<div class='form-group'>
<label for='form-dieceze'>
".__DIECEZE__.":
</label>
<input class='form-control' id='form-dieceze' name='dieceze' value='".$row3->dieceze."'   required type='text'>
</div>
<div class='form-group'>
<button class='btn' type='submit'>
".__ULOZIT__."
</button>
<input type=\"hidden\" name=\"pokracovat_1\" value=\"1\" />
</div>
</form>";


echo '<a href="/skripty/smazat-ucet.php" 
onClick="if(confirm(\'Opravdu chcete nenávratně smazat svůj účet?\'))
return true;
else return false;" style=\"font-size: 14px; font-weight: bold;">SMAZAT ÚČET</a> - Kompletně zrušit účet a smazat všechny mé data.';

}

}

echo "</div>

</div>
</article>
</div>
</main>";


?>
