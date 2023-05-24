<?
// registrace
//die();

echo "<section class='page-heading-small'></section>
<main>
<div class='container'>
<article class='form'>
<h2>
".__VYTVORENI_NOVEHO_UCTU__."
</h2>
<div class='row'>
<div class='col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12'>";

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

// kontrola uzivatele, jestli uz neni zaregistrovan (podle uz.jm)
$query_kontrola = MySQLi_Query($spojeni,"SELECT id FROM registrace WHERE uz_jmeno='".addslashes($_POST['uz_jmeno'])."'") or die(err(1));
if(mysqli_num_rows($query_kontrola))
{
$err .= "Uživatel s uživatelským jménem  <b>".strip_tags($_POST['uz_jmeno'])."</b> je již zaregistrován. Vyberte prosím jiné uživatelské jméno.<br />";
}

// kontrola uzivatele, jestli uz neni zaregistrovan (podle emailu)
$query_kontrola2 = MySQLi_Query($spojeni,"SELECT id FROM registrace WHERE email='".addslashes($_POST['email'])."'") or die(err(1));
if(mysqli_num_rows($query_kontrola2))
{
$err .= "Uživatel s emailem  <b>".strip_tags($_POST['email'])."</b> je již zaregistrován. Pokud jste zapomněli heslo ke svému účtu, pak si můžete nechat vygenerovat nové 
zde: <a href=\"".__LANG__."/zapomenute-heslo.html\"></a><br />";
}

$captcha_secret_key = __CAPTCHA_SECRET_KEY__;
$captcha = $_POST['g-recaptcha-response'];
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$captcha_secret_key."&response=".$captcha);
$response = json_decode($verify, true);
 
if($response['success']==1)
{
  // OK
}
else
{
   $err .= 'Chyba při ověření captcha<br>';
}

if(!$err)
{
$ip_adr = getip();
// kontakt /////////////////////////////////////////////////////////////////////
MySQLi_Query($spojeni,"INSERT INTO registrace
(jmeno_prijmeni,dieceze,email,uz_jmeno,heslo,aktivni,platnost_do,ip,datum, souhlas_ou)
VALUES (
'".addslashes(strip_tags($_POST['jmeno_prijmeni']))."',
'".addslashes(strip_tags($_POST['dieceze']))."',
'".addslashes(strip_tags($_POST['email']))."',
'".addslashes(strip_tags($_POST['uz_jmeno']))."',
'".md5(addslashes($_POST['heslo']))."',
'0',
'0',
'".addslashes($ip_adr)."',
UNIX_TIMESTAMP(),
'".intval($_POST['souhlas_ou'])."'
)") or die(err(2));

$last_id = mysqli_insert_id($spojeni);

// odeslani emailu uzivateli s rekapitulaci zadanych udaju

 $headers = "From:".__EMAIL_1__."\n";
 $headers .= "Return-Path :".__EMAIL_1__."\n";
 $headers .= "Reply-To :".__EMAIL_1__."\n";
 $headers .= "MIME-Version: 1.0\n";
 $headers .= "Content-type: text/plain; charset=utf-8\n";
 $headers .= "Content-Transfer-Encoding: 8bit\n";
 $headers .= "X-Mailer: w-shop powered by PHP / ".phpversion()."\n";
 $headers .= "bcc:".__EMAIL_1__; // BCC
 
 if(__LANG__=='sk')
 {
	 $body = __TEXT_REG_SK__."\n\n";
 }
 else
 {
	 $body = __TEXT_REG_CZ__."\n\n";
 }
 
 $body .= "Datum registrace: ".date("d.m.Y H:i:s")."\nJméno: ".strip_tags($_POST['jmeno_prijmeni'])."\nE-mail: ".strip_tags($_POST['email'])."\nUživatelské jméno: ".strip_tags($_POST['uz_jmeno'])."\nHeslo: ".strip_tags($_POST['heslo'])."\nDiecéze: ".strip_tags($_POST['dieceze'])."\n\n";
 
 if(__LANG__=='sk')
 {
   if($_POST['souhlas_ou']==1)
   {
	   $body .= __REGISTRACE_SOUHLAS_SE_ZPRAC_OS_UD_SK__.": ÁNO";
   }
   else
   {
	   $body .= __REGISTRACE_SOUHLAS_SE_ZPRAC_OS_UD_SK__.": NIE";
   }
 }
 else
 {
	
	if($_POST['souhlas_ou']==1)
   {
	   $body .= __REGISTRACE_SOUHLAS_SE_ZPRAC_OS_UD_CZ__.": ANO";
   }
   else
   {
	   $body .= __REGISTRACE_SOUHLAS_SE_ZPRAC_OS_UD_CZ__.": NE";
   }
 }



 if(!mail(strip_tags($_POST['email']), "Registrace na ".__URL__, $body,$headers))
  {
    //echo  "<br /><span class=\"r\"><br />Chyba při odesílání e-mailu<br /></span>";
    echo "<p class='alert red'>Chyba při odesílání e-mailu<br /></p>";
  }
  else
  {
	  	
	
	
		 echo "<p class='alert green'>Registrace byla úspěšná. Vyčkejte než administrátor registraci zkontroluje a zaktivní. O tomto kroku budete informováni emailem.</p>";

	


	
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
	
echo "<form method='post'>
<div class='form-group'>
<label for='form-uz-jmeno'>
".__UZ_JMENO__.":
</label>
<input class='form-control' id='form-uz-jmeno' name='uz_jmeno' required type='text'>
</div>
<div class='form-group'>
<label for='form-jmeno'>
".__JMENO_PRIJMENI__.":
</label>
<input class='form-control' id='form-jmeno' name='jmeno_prijmeni' required type='text'>
</div>
<div class='form-group'>
<label for='form-email'>
Email:
</label>
<input class='form-control' id='form-email' name='email' required type='email'>
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
<input class='form-control' id='form-dieceze' name='dieceze' required type='text'>
</div>
<div class='form-group'>
  <label class='form-checkbox'>
    <input class='form-control' id='souhlas' name='souhlas_ou' value='1' required type='checkbox'>
    <span>";
    
    if(__LANG__=='sk')
    {
        echo __REGISTRACE_SOUHLAS_SE_ZPRAC_OS_UD_SK__;
    }
    else
    {
		echo __REGISTRACE_SOUHLAS_SE_ZPRAC_OS_UD_CZ__;
	}
    
    echo "</span>
  </label>
</div>

<div class='form-group'>
<div class=\"g-recaptcha\" data-sitekey=\"".__CAPTCHA_SITE_KEY__."\"></div>
</div>

<div class='form-group'>
<button class='btn' type='submit'>
".__REGISTROVAT__."
</button>
<input type=\"hidden\" name=\"pokracovat_1\" value=\"1\" />
</div>
</form>";

}

echo "</div>
<div class='col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6'>
<p>
".__REGISTRACE_TEXT1__."
</p>

<h3>
".__REGISTRACE_TEXT7__."
</h3>
<p>
".__REGISTRACE_TEXT8__."
</p>
</div>
<div class='col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6'>
<h3>
".__REGISTRACE_TEXT2__.":
</h3>
<p>
".__REGISTRACE_TEXT3__."
</p>
<p>
".__REGISTRACE_TEXT4__."
</p>
<p>
".__REGISTRACE_TEXT5__."
</p>
<p>
".__REGISTRACE_TEXT6__.":
</p>
</div>
</div>
</article>
</div>
</main>";


?>
