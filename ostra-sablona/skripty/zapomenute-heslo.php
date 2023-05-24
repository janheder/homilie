<?
// prihlaseni


echo "<section class='page-heading-small'></section>
<main>
<div class='container'>
<article class='form'>
<h2>
".__ZAPOMENUTE_HESLO__."
</h2>
<div class='row'>
<div class='col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12'>";


if($_POST['email_z'])
{
		$err = false;
		// zpracovani

		// kontrola vyplneni
		if(!$_POST['email_z'])
		{
		$err .= "Nevyplnili jste e-mail<br />";
		}

		if($err)
		{
		$zaslat_heslo .= "<br /><br /><span class='r'>".$err."</span><br />";
		}
		else
		{
			kontrola_ref();

			$count = 8;
			$chars = 62;
			$return = "";
				for ($i=0; $i < $count; $i++) 
				{
				$rand = rand(0, $chars - 1);
				$return .= chr($rand + ($rand < 10 ? ord('0') : ($rand < 36 ? ord('a') - 10 : ord('A') - 36)));
				}




			// ulozeni do db

			$query_kontrola = MySQLi_Query($spojeni,"UPDATE registrace SET
			heslo='".md5($return)."'
			WHERE email='".addslashes($_POST['email_z'])."'  LIMIT 1") or die(err(3));

			if(mysqli_affected_rows($spojeni))
			{
			// odeslani emailu uzivateli


			 $headers = "From:".__EMAIL_1__."\n";
			 $headers .= "Return-Path :".__EMAIL_1__."\n";
			 $headers .= "Reply-To :".__EMAIL_1__."\n";
			 $headers .= "MIME-Version: 1.0\n";
			 $headers .= "Content-type: text/plain; charset=utf-8\n";
			 $headers .= "Content-Transfer-Encoding: 8bit\n";
			 $headers .= "X-Mailer: w-shop powered by PHP / ".phpversion();

			 $body = "Na webu ".__URL__." jste právě požádali o vygenerování nového hesla.\nVaše nové heslo: $return";

			 if(!mail(strip_tags($_POST['email_z']), "Nove heslo", $body,$headers))
			  {
			  $zaslat_heslo .= "<span class=\"r\"><br />Chyba při odesílání e-mailu<br /></span>";
			  exit();
			  }
			$zaslat_heslo .= "<br />Na adresu <b>".sanitize($_POST['email_z'])."</b> bylo právě odesláno nově vygenerované heslo.";	
			}
			else
			{
			$zaslat_heslo .= "<br /><span class=\"r\">Vámi zadané údaje nejsou správné!</span>";
			}

		}
}


echo $zaslat_heslo;

if(!$_POST['email_z'])
{
echo 'Pokud jste heslo zapomněli, můžeme Vám na Váš e-mail, který jste uvedli při registraci, zaslat nové vygenerované heslo.<br />';

echo "<form method='post' >
<div class='form-group'>
<label for='form-uz-jmeno'>
Zadejte Váš e-mail:
</label>
<input class='form-control' id='form-uz-jmeno' name='email_z' required type='text'>
</div>


<div class='form-group'>
<button class='btn' type='submit'>
".__OBNOVIT_HESLO__."
</button>
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
