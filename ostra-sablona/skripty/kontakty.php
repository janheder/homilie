<?
// kontakty
		 $query_nadpis = MySQLi_Query($spojeni,"SELECT id, nadpis, obsah, fotogalerie, pozadi FROM stranky WHERE str='kontakty' AND lang='".__LANG__."'") or die(err(1));
         $row_nadpis = MySQLi_fetch_object($query_nadpis);
         
         MySQLi_Query ($spojeni,"UPDATE stranky SET precteno=precteno+1 WHERE str='kontakty' AND lang='".__LANG__."'") or die(err(3));
      
        
        
        
        if($row_nadpis->pozadi)
		{
			$pozadi = "/prilohy/".$row_nadpis->pozadi;
		}
		else
		{
			$pozadi = "/img/page-header.jpg";
		}
         
         
         echo "<section class='page-heading post__image-container'>
			<div class='container'>
			<h1>
			".stripslashes($row_nadpis->nadpis)."
			</h1>
			</div>
			<img alt='Stránka' class='post__featured-image' data-src='".$pozadi."' src='/img/lazy/lazy-page-header.jpg'>
			<section class='breadcrumb-wrap'>
			<div class='container'>";
			
			drobinka($p,$menu_all,$spojeni);
			
			
			echo "</div>
			</section>
			</section>";
			
			
			echo "<main>
			<div class='container'>
			<article class='kontakty'>
			<div class='row'>
			<div class='col-12 col-xs-12 col-sm-12 col-md-4 col-lg-4'>";
		 
		 echo stripslashes($row_nadpis->obsah);
		  
		  
		  echo "</div>";
		  
		  $err = false;
if($_POST['pokracovat_1'])
{
  // kontrola vyplneni
	if(!$_POST['jmeno'])
	{
	$err = __NEVYPLNILI_JSTE_JMENO__."<br />";
	}
	if(!is_email($_POST['email']))
	{
	$err .= __VAMI_ZADANY_EMAIL_JE_NEVALIDNI__."<br />";
	}
	if(!$_POST['zprava'])
	{
	$err .= __NEVYPLNILI_JSTE_ZPRAVU__."<br />";
	}
	
	//antispam
	if(!strip_tags($_POST['as_hlog_k']) || !strip_tags($_POST['as_hlog_v']))
	{
	$err .= "Nevyplnili jste kontrolní otázku<br />";
	}
	
	if(!array_key_exists(strip_tags($_POST['as_hlog_k']), $otazky_as))
	{
	$err .= "Chybně vyplněná kontrolní otázka<br />";
	}
	
	foreach ($otazky_as as $key => $value)
	{
		if($key==strip_tags($_POST['as_hlog_k']) && $value!=strip_tags($_POST['as_hlog_v']))
		{
		$err .= "Chybně vyplněná kontrolní otázka<br />";
		}
	}
	
	if(!$err)
	{
	  // zpracovani a odeslani formulare na email
	  
	  // odeslani na email
	  
	     $ip_adr = getip();
	     $headers = "From:".strip_tags($_POST['email'])."\n";
		 $headers .= "Return-Path :".__EMAIL_1__."\n";
		 $headers .= "Reply-To :".strip_tags($_POST['email'])."\n";
		 $headers .= "MIME-Version: 1.0\n";
		 $headers .= "Content-type: text/plain; charset=utf-8\n";
		 $headers .= "Content-Transfer-Encoding: 8bit\n";
		 $headers .= "X-Mailer: w-shop powered by PHP / ".phpversion()."\n";
		 $headers .= "bcc:".strip_tags($_POST['email']); // BCC
		 
		 $body = "Datum: ".date("d.m.Y H:i:s")."\nJméno: ".strip_tags($_POST['jmeno'])."\nE-mail: ".strip_tags($_POST['email'])."\nZpráva: ".strip_tags($_POST['zprava'])."\nIP: ".$ip_adr;
		 
		  if(!mail(__EMAIL_1__, 'Dotaz z webu '.__URL__, $body,$headers))
			  {

			     echo '<br /><br /><span style="color: red">'.__CHYBA_PRI_ODESILANI_EMAILU__.'</span><br />'; 
			  }
			  else
			  { 

			     echo '<br /><br /><span style="color: red">'.__ODESLANO__.'</span><br />'; 
			  }
    
    
    }
    else
	{
		 // vypis chyb
		echo '<span style="color: red">'.$err.'</span><br />';
	}
	 
}
		  
		  
		if(!$_POST['pokracovat_1'] || ($_POST['pokracovat_1'] && $err))
		{
		  echo "<div class='col-12 col-xs-12 col-sm-12 col-md-8 col-lg-8'>
			<form method='post'>
			<div class='form-group'>
			<label for='form-jmeno'>
			".__JMENO_PRIJMENI__."
			</label>
			<input class='form-control' id='form-jmeno' name='jmeno' type='text' required='' >
			</div>
			<div class='form-group'>
			<label for='form-email'>
			Email
			</label>
			<input class='form-control' id='form-email' name='email' type='email' required=''>
			</div>
			<div class='form-group'>
			<label for='form-text'>
			".__TEXT_ZPRAVY__."
			</label>
			<textarea class='form-control' id='form-text' name='zprava' rows='6' required=''></textarea>
			</div>";
			
			echo "<div class='form-group'>
			<label for='form-jmeno'>
			Antispam
			</label>
			".antispam($otazky_as)."
			</div>";
			
			echo "<div class='form-group'>
			<button class='btn' type='submit'>
			".__ODESLAT_ZPRAVU__."
			</button>
			<input type=\"hidden\" name=\"pokracovat_1\" value=\"".time()."\" />
			</div>
			</form>
			</div>";
		}
			
			
			
			echo "</div>
			</article>
				</div>
				</main>";

?>
