<?
########################################################
# RS W-Publicator - v. 3.02 - (c) Robert Hlobilek 2008 #
#                  funkce                              #
########################################################

define("__URL__","https://".$_SERVER['SERVER_NAME']);
define("salt", "w6efdx45i8ursxgOWgTe"); 
define("imgdir", "/captcha"); 
define("codefile", imgdir."/codes.txt"); 
define("__TR_BG__"," onmouseover=\"this.style.background='#FFF8DB'\" onmouseout=\"this.style.background='#ffffff'\" ");
$tr_bg = "onmouseover=\"this.style.background='#FFF8DB'\" onmouseout=\"this.style.background='#ffffff'\"";

if(strstr($_SERVER['HTTP_USER_AGENT'],"MSIE"))
{
	define("__IE__",1);
}
else
{
    define("__IE__",0);
}

if(strstr($_SERVER['HTTP_USER_AGENT'],"Safari"))
{
	define("__SAFARI__",1);
}
else
{
    define("__SAFARI__",0);
}

function globalni_pr($spojeni)
{
$query_nastaveni = MySQLi_Query($spojeni,"SELECT str,obsah FROM obecne_nastaveni") or die(err(1));
	while($row_nastaveni = mysqli_fetch_object($query_nastaveni))
	{
	  define("__".$row_nastaveni->str."__",$row_nastaveni->obsah);
	}

}

/* Menu array */
$menu_db = array();
$query_menu = MySQLi_Query($spojeni,"SELECT id, nadpis_menu, str FROM stranky WHERE lang='".__LANG__."' ORDER BY vnor, razeni") or die(err(1));
while($row_menu = mysqli_fetch_object($query_menu))
{
$menu_db[$row_menu->str] = $row_menu->nadpis_menu;
}

// ostatni stranky, ktere nemaji odkaz
$menu_s = array
(
'vyhledavani'=>'Vyhledávání',
'uvod'=>'Úvod',
'produkty'=>'Produkty',
'aktuality'=>'Aktuality',
'novinky'=>'Novinky',
'texty'=>'Texty',
'zapomenute-heslo'=>'Zapomenuté heslo',
'prihlaseni'=>'Přihlášení',
'registrace'=>'Registrace',
'homilie'=>'Homílie',
'doporucujeme'=>'Doporučujeme',
'fotogalerie'=>'Fotogalerie',
'odeslat-vzkaz'=>'Odeslat vzkaz',
'uprava-reg-udaju'=>'Osobní údaje',
'mapa-webu'=>'Mapa webu'
);


$arr_staty = array
(
1=>'ČESKÁ REPUBLIKA',
2=>'SLOVENSKO'
);

$arr_adresa = array
(
1=>'stejná jako fakturační',
2=>'stejná jako lékárna',
3=>'odloučené pracoviště',
);


$menu_all = array_add($menu_db,$menu_s);

function array_add( $array1, $array2 )
{
	foreach( $array2 AS $key => $value )
	{
	   while( array_key_exists( $key, $array1 ) )
	   $key .= "_";
	   $array1[ $key ] = $value;
	}

return $array1;
}


function odkazy($p,$spojeni)
{
	$query_menu = MySQLi_Query($spojeni,"SELECT id, nadpis_menu, str FROM stranky WHERE top=1 AND vnor=1 AND aktivni=1 AND lang='".__LANG__."' ORDER BY vnor, razeni") or die(err(1));
    while($row_menu = mysqli_fetch_object($query_menu))
    {
	    $query_menu2 = MySQLi_Query($spojeni,"SELECT id, nadpis_menu, str FROM stranky WHERE top=1 AND vnor=2 AND id_nadrazeneho=".$row_menu->id." AND aktivni=1 AND lang='".__LANG__."' ORDER BY vnor, razeni") or die(err(1));
	    
	    if(mysqli_num_rows($query_menu2))
	    {
			// vysouvaci submenu
			echo '<li class="nav-item">

					<div class="dropdown">
					
					<button aria-expanded="false" aria-haspopup="true" class="btn btn-ghost-white btn-invisible dropdown-toggle" data-toggle="dropdown" id="navDropdown'.$row_menu->id.'" type="button">
					
					'.stripslashes($row_menu->nadpis_menu).'
					
					</button>
					
					<div aria-labelledby="navDropdown'.$row_menu->id.'" class="dropdown-menu">';
					
					while($row_menu2 = mysqli_fetch_object($query_menu2))
					{
					
					  echo '<a class="dropdown-item" href="/'.__LANG__.'/'.$row_menu->str.'/'.$row_menu2->str.'.html">'.stripslashes($row_menu2->nadpis_menu).'</a>';		
				    }			

					
					echo '</div>
					
					</div>
					
					</li>';
		}
		else
		{
			echo '<li class="nav-item"><a class="nav-link" href="/'.__LANG__.'/'.$row_menu->str.'.html">'.stripslashes($row_menu->nadpis_menu).'</a></li>';
		}
	    
	}
}


function get_title_header($p,$menu_all,$spojeni)
{
	$nazev = "nazev_".__LANG__;
	$nadpis = "nadpis_".__LANG__;
	$str = "str_".__LANG__;
	
		foreach ($menu_all as $key => $value)
		{
			if($key==$p)
			{
			
			if($p=='produkty' && $_GET['idk'] && $_GET['idp'])
			  {
			   //detail pruduktu
			   $query_r = MySQLi_Query($spojeni,"SELECT $nadpis FROM kategorie WHERE $str='".addslashes($_GET['idk'])."'") or die(err(1));
			   $row_r = MySQLi_fetch_object($query_r);
			   echo stripslashes($row_r->$nadpis)." | ";
			   
			    // produkty
			   $query_p = MySQLi_Query($spojeni,"SELECT $nazev FROM produkty WHERE id='".intval($_GET['idp'])."'") or die(err(1));
			   $row_p = MySQLi_fetch_object($query_p);
			   echo stripslashes($row_p->$nazev)." | ";
			  }
			 if($p=='produkty' && $_GET['idk'] && !$_GET['idp'])
			  {
			   // kategorie
			   $query_r = MySQLi_Query($spojeni,"SELECT $nadpis FROM kategorie WHERE $str='".addslashes($_GET['idk'])."'") or die(err(1));
			   $row_r = MySQLi_fetch_object($query_r);
			   echo stripslashes($row_r->$nadpis)." | ";
			  }
			 if($_GET['ida'])
			  {
			   //detail novinky
			   $query_n = MySQLi_Query($spojeni,"SELECT nadpis FROM aktuality WHERE id='".intval($_GET['ida'])."'") or die(err(1));
			   $row_n = MySQLi_fetch_object($query_n);
			   echo stripslashes($row_n->nadpis)." | ";
			   
			  } 
			  

			  echo $value;

				 if($p==!"uvod")
				  {
				  define(__NADPIS__,$value);
				  }


			 }

         }
}

/////////////////////////////////////////////////////////////////////       menu






function nadpis($p,$menu_all)
{


	foreach($menu_all as $k => $v)
	{
	 if($k==$p)
	 {
	  echo '<h1>'.stripslashes($v).'</h1>';
	 }
	}
	
}



function today()
{
$today = strftime(" %A %e. %B %Y");
$today .= ", svátek má: ";
return $today;
}


function kontrola_ref()
{
$server = "https://".$_SERVER['SERVER_NAME'];
$referer = $_SERVER['HTTP_REFERER'];

if(!preg_match("#^".$server."#", $referer))
{
 die ("špatny referer<br />originální stránky jsou na <a href=\"".__URL__."\">".__URL__."</a>");
}
}


function getip() {
    if ($_SERVER) {
        if ( $_SERVER["HTTP_X_FORWARDED_FOR"] ) {
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif ( $_SERVER["HTTP_CLIENT_IP"] ) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }

    } else {
        if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
            $realip = getenv( 'HTTP_X_FORWARDED_FOR' );
        } elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
            $realip = getenv( 'HTTP_CLIENT_IP' );
        } else {
            $realip = getenv( 'REMOTE_ADDR' );
        }
    }

    return $realip;
}


function is_email($autor_eml) {
      $autor_eml = strtolower($autor_eml);

    if (strlen($autor_eml) < 6)
    {
        return false;
      }
    if (strpos($autor_eml, "@") != strrpos($autor_eml, "@"))
    {
        return false;
      }
    if ((strpos($autor_eml, "@") < 1) || (strpos($autor_eml, "@") > (strlen($autor_eml) - 4)))
    {
        return false;
      }
    if (strrpos($autor_eml, ".") < strpos($autor_eml, "@"))
    {
        return false;
      }
    if (((strlen($autor_eml) - strrpos($autor_eml, ".") - 1) < 2) ||
   ((strlen($autor_eml) - strrpos($autor_eml, ".") - 1) > 4))
    {
        return false;
      }
    return true;
}


function err($e)
{
// chybove hlasky
switch ($e)
{
case 1:
   $hlaska = "Nepodařilo se vybrat data z databáze";
   break;
case 2:
   $hlaska = "Nepodařilo se uložit záznam";
   break;
case 3:
   $hlaska = "Nepodařilo se updatovat záznam";
   break;
case 4:
   $hlaska = "Nepodařilo se připojit k databázi";
   break;
case 5:
   $hlaska = "Nepodařilo se smazat záznam";
   break;
case 6:
   $hlaska = "Nepodařilo se optimalizovat tabulku";
   break;
default:
   $hlaska = "Nespecifikovaná chyba";
}

return $hlaska.mysql_error();
}






function novinky_uvod($x,$spojeni)
{
	
 if(__LANG__=='cz')
 {
    $aktivni = 'aktivni_cz';
    $str = 'str_cz';
    $nazev = 'nazev_cz';
    $obsah = 'obsah_cz';
 }
 else
 {
	$aktivni = 'aktivni_sk';
	$str = 'str_sk';
	$nazev = 'nazev_sk';
	$nazev = 'nazev_sk';
	$obsah = 'obsah_sk';
 }	
	
 //$query_novinky = MySQLi_Query($spojeni,"SELECT * FROM aktuality WHERE aktivni=1 AND lang='".__LANG__."' AND na_uvod=1 AND typ=1 ORDER BY datum DESC, id DESC LIMIT 0,$x") or die(err(1));
 $query_novinky = MySQLi_Query($spojeni,"SELECT * FROM homilie WHERE ".$aktivni."=1 AND typ=0 AND typ2=1 ORDER BY id DESC LIMIT 0,$x") or die(err(1));
 while ($row_novinky = MySQLi_fetch_object($query_novinky))
 {

	 
if($row_novinky->vnor==4)
{
		$n2 = MySQLi_Query($spojeni,"SELECT id, $str, id_nadrazeneho FROM homilie WHERE id='".$row_novinky->id_nadrazeneho."' ");
		$row_n2 = MySQLi_fetch_object($n2);
		
		$n3 = MySQLi_Query($spojeni,"SELECT id, $str, id_nadrazeneho FROM homilie WHERE id='".$row_n2->id_nadrazeneho."' ");
		$row_n3 = MySQLi_fetch_object($n3);
		
		$n4 = MySQLi_Query($spojeni,"SELECT id, $str, id_nadrazeneho FROM homilie WHERE id='".$row_n3->id_nadrazeneho."' ");
		$row_n4 = MySQLi_fetch_object($n4);
		
		//$odkaz = "<a class='homilie-single' href='/".__LANG__."/homilie/".$row_n4->$str."/".$row_n3->$str."/".$row_n2->$str."/".$row_novinky->$str."/".$row_novinky->id.".html'>";
		$odkaz = "<a class='homilie-single' href='/".__LANG__."/homilie/".$row_n4->$str."/".$row_n3->$str."/".$row_n2->$str."/".$row_n2->id.".html'>";
}	
elseif($row_novinky->vnor==3)
{
		$n2 = MySQLi_Query($spojeni,"SELECT id, $str, id_nadrazeneho FROM homilie WHERE id='".$row_novinky->id_nadrazeneho."' ");
		$row_n2 = MySQLi_fetch_object($n2);
		
		$n3 = MySQLi_Query($spojeni,"SELECT id, $str, id_nadrazeneho FROM homilie WHERE id='".$row_n2->id_nadrazeneho."' ");
		$row_n3 = MySQLi_fetch_object($n3);
		
		$odkaz = "<a class='homilie-single' href='/".__LANG__."/homilie/".$row_n3->$str."/".$row_n2->$str."/".$row_n2->id.".html'>";
} 
elseif($row_novinky->vnor==2)
{
		$odkaz = "<a class='homilie-single' href='/".__LANG__."/homilie/".$row_novinky->$str."/".$row_novinky->id.".html'>";
} 
	 
	 
echo "<article>
".$odkaz."
<h4>".stripslashes($row_novinky->$nazev)."</h4><p>
".cut_text(strip_tags(stripslashes($row_novinky->$obsah)),__DELKA__)." ...
</p>
<b class='text-muted'>
<i class='fa fa-calendar'></i>".$row_novinky->datum."</b>
<span>
".__CELA_HOMILIE__."
</span>
</a>
</article><br>";
	 
	 
	 

 
	  
 }

}

function novinky_zbytek($spojeni)
{
	
  $limit = intval($_GET['limit']);
  if(!$limit)
  {
  $limit = 0;
  }
  
$query_novinky = MySQLi_Query($spojeni,"SELECT * FROM aktuality WHERE aktivni=1 AND lang='".__LANG__."'  ORDER BY datum DESC, id DESC LIMIT $limit,10") or die(err(1));
 while ($row_novinky = MySQLi_fetch_object($query_novinky))
 {
	 /*if($row_novinky->foto)
	 {
	   $foto = $row_novinky->foto;
	 }
	 else
	 {
	   $foto = 'news-thumb.jpg';
	 }*/
	 
	 
	 echo "<div class='col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6'>
<div class='articles-wrap'>
<article>
<a class='news-single' href='/".__LANG__."/novinky/".$row_novinky->str."/".$row_novinky->id.".html'>
<h4>
".stripslashes($row_novinky->nadpis)."
</h4>
<p>
".stripslashes($row_novinky->perex)."
</p>
<b class='text-muted'>
<i class='fa fa-calendar'></i>
".date("d. m. Y",$row_novinky->datum)."
</b>
<span>
".__CELY_CLANEK__."
</span>
</a>
</article>
</div>
</div>";
	 


 
	  
 }
 
 echo "</div>";
 
  // strankovani

 
 $query_pocet = mysqli_query($spojeni,"SELECT id FROM aktuality WHERE typ=1") or die(err(1));
 $pocet = mysqli_num_rows($query_pocet);
 
 get_links_novinky5($pocet,$limit);




}










function get_links_novinky5($pocet,$limit)
{
	echo "<div class='article-pagination'>
<nav aria-label='Page navigation example'>
<ul class='pagination'>";
   
	
	for ($px=0;$px<ceil($pocet/10);$px++)
	{
	
		echo ' <li ';
		if($limit==$limit2) { echo ' class="page-item active" ';}
		else{ ' class="page-item" ';}
		echo '><a class="page-link" href="?limit='.$limit2.'">'.($px+1).'</a></li>';
		
		$limit2 = $limit2+10;
		
	}
	
	echo "</ul></nav></div>";
	
}



function cut_text($text,$delka)
{
	$pocet_znaku = mb_strlen($text,'UTF-8');
	if($delka<$pocet_znaku)
	{
	$c_text = substr($text, 0, $delka); 
	if(!preg_match('//u', $c_text)) 
	{
	/* Odstraníme poslední půlznak */
	$c_text = preg_replace('/[\xc0-\xfd][\x80-\xbf]*$/', '', $c_text);
	} 
	}
	else
	{
	$c_text = $text;
	}
	return $c_text;
}


function bez_diakritiky($s)
{
$a = array("á","ä","č","ď","é","ě","ë","í","ň","ó","ö","ř","š","ť","ú","ů","ü","ý","ž","Á","Ä","Č","Ď","É","Ě","Ë","Í","Ň","Ó","Ö","Ř","Š","Ť","Ú","Ů","Ü","Ý","Ž");
$b = array("a","a","c","d","e","e","e","i","n","o","o","r","s","t","u","u","u","y","z","A","A","C","D","E","E","E","I","N","O","O","R","S","T","U","U","U","Y","Z");
$sb = strtolower(str_replace($a, $b, $s));
$sb = str_replace('\"','',$sb);
$sb = str_replace('/','-',$sb);
$sb = str_replace('+','-',$sb);
$sb = str_replace(',','',$sb);
$sb = str_replace('.','',$sb);
$sb = str_replace('"','',$sb);
$sb = str_replace('&','-',$sb);
$sb = str_replace('?','-',$sb);
$sb = str_replace(')','-',$sb);
$sb = str_replace('(','-',$sb);
$sb = str_replace('“','',$sb);
$sb = str_replace('&nbsp;','-',$sb);
$sb = str_replace(' ','-',$sb);
return  $sb; 
}




function eval_buffer($string) {
   ob_start();
   eval("$string[2];");
   $return = ob_get_contents();
   ob_end_clean();
   return $return;
}

function eval_print_buffer($string) {
   ob_start();
   eval("print $string[2];");
   $return = ob_get_contents();
   ob_end_clean();
   return $return;
}

function eval_html($string) {
   $string = preg_replace_callback("/(<\?=)(.*?)\?>/si",
                                   "eval_print_buffer",$string);
   return preg_replace_callback("/(<\?php|<\?)(.*?)\?>/si",
                                 "eval_buffer",$string);
}


if(__LANG__=='cz')
{
$otazky_as = array(
'Kolik je dva plus dva'=>'4',
'Kolik je tři plus šest'=>'9',
'Kolik je pět mínus dva'=>'3',
'Kolik je deset plus deset'=>'20',
'Kolik je osm mínus čtyři'=>'4',
'Kolik je šest plus šest'=>'12'
);
}

if(__LANG__=='en')
{
$otazky_as = array(
'Kolik je dva plus dva'=>'4',
'Kolik je tři plus šest'=>'9',
'Kolik je pět mínus dva'=>'3',
'Kolik je deset plus deset'=>'20',
'Kolik je osm mínus čtyři'=>'4',
'Kolik je šest plus šest'=>'12'
);
}






function antispam($otazky_as)
{
$rand_key = array_rand ($otazky_as);
$inputy = "<input type=\"hidden\" name=\"as_hlog_k\" value=\"".$rand_key."\" />
<input type=\"text\" class='form-control' name=\"as_hlog_v\" id=\"check-reg\" placeholder=\"".$rand_key." (".__NAPISTE_CISLOVKU__.")\" required=\"\">";
	
return $inputy;
}




function drobinka($p,$menu_3,$spojeni)
{
$nazev = "nazev_".__LANG__;
$nazev_kat = 'nadpis_'.__LANG__;
$popis_kat = 'popis_'.__LANG__;
$str_kat = 'str_'.__LANG__;	
$nazev_pr = 'nazev_'.__LANG__;

$url_arr = explode("/",$_SERVER['REQUEST_URI']);

//var_dump($url_arr);

echo '<ol class="breadcrumb">';


echo "<li class='breadcrumb-item'>
<a href='/'>
<i class='fa fa-home'></i>
</a></li>";
if($url_arr[6])
{ 
   if($url_arr[2]=="texty")
	{ 
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">Texty</a></li>";
	
	$n = MySQLi_Query($spojeni,"SELECT id, $nazev, id_nadrazeneho FROM texty WHERE  $str_kat='".addslashes($url_arr[3])."'");
	$row_n = MySQLi_fetch_object($n);
	
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2]."/".$url_arr[3]."/".$row_n->id.".html\">".stripslashes($row_n->$nazev)."</a></li>";
	
	
	$n2 = MySQLi_Query($spojeni,"SELECT id, $nazev, id_nadrazeneho FROM texty WHERE id_nadrazeneho=".$row_n->id." AND $str_kat='".addslashes($url_arr[4])."' ");
	$row_n2 = MySQLi_fetch_object($n2);
	
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2]."/".$url_arr[3]."/".$url_arr[4]."/".$row_n2->id.".html\">".stripslashes($row_n2->$nazev)."</a></li>";
	
	
	
	$nx = MySQLi_Query($spojeni,"SELECT $nazev FROM texty WHERE id='".intval($url_arr[6])."'");
	$row_nx = MySQLi_fetch_object($nx);
	
	echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_nx->$nazev)."</h5></li>";
	}
	elseif($url_arr[2]=="homilie")
	{ 
		
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">Homílie</a></li>";
	
	$n = MySQLi_Query($spojeni,"SELECT id, $nazev, id_nadrazeneho FROM homilie WHERE  $str_kat='".addslashes($url_arr[3])."'");
	$row_n = MySQLi_fetch_object($n);
	
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2]."/".$url_arr[3]."/".$row_n->id.".html\">".stripslashes($row_n->$nazev)." </a></li>";
	
	$n2 = MySQLi_Query($spojeni,"SELECT id, $nazev, id_nadrazeneho FROM homilie WHERE  $str_kat='".addslashes($url_arr[4])."' AND id_nadrazeneho=".intval($row_n->id)." ");
	$row_n2 = MySQLi_fetch_object($n2);
	
	
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2]."/".$url_arr[3]."/".$url_arr[4]."/".$row_n2->id.".html\">".stripslashes($row_n2->$nazev)."</a></li>";
	
	
	
	$nx = MySQLi_Query($spojeni,"SELECT $nazev FROM homilie WHERE id='".intval($url_arr[6])."'");
	$row_nx = MySQLi_fetch_object($nx);
	
	echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_nx->$nazev)."</h5></li>";
	}
	elseif($url_arr[2]=="homilie-deti")
	{ 
		
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">".__HOMILIE_DETI__."</a></li>";
	
	$n = MySQLi_Query($spojeni,"SELECT id, $nazev, id_nadrazeneho FROM homilie_deti WHERE  $str_kat='".addslashes($url_arr[3])."'");
	$row_n = MySQLi_fetch_object($n);
	
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2]."/".$url_arr[3]."/".$row_n->id.".html\">".stripslashes($row_n->$nazev)." </a></li>";
	
	$n2 = MySQLi_Query($spojeni,"SELECT id, $nazev, id_nadrazeneho FROM homilie_deti WHERE  $str_kat='".addslashes($url_arr[4])."' AND id_nadrazeneho=".intval($row_n->id)." ");
	$row_n2 = MySQLi_fetch_object($n2);
	
	
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2]."/".$url_arr[3]."/".$url_arr[4]."/".$row_n2->id.".html\">".stripslashes($row_n2->$nazev)."</a></li>";
	
	
	
	$nx = MySQLi_Query($spojeni,"SELECT $nazev FROM homilie_deti WHERE id='".intval($url_arr[6])."'");
	$row_nx = MySQLi_fetch_object($nx);
	
	echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_nx->$nazev)."</h5></li>";
	}
	elseif($url_arr[2]=="homileticke-publikace")
	{ 
		
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">".__HOMILETICKE_PUBLIKACE__."</a></li>";
	
	$n = MySQLi_Query($spojeni,"SELECT id, $nazev, id_nadrazeneho FROM homileticke_publikace WHERE  $str_kat='".addslashes($url_arr[3])."'");
	$row_n = MySQLi_fetch_object($n);
	
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2]."/".$url_arr[3]."/".$row_n->id.".html\">".stripslashes($row_n->$nazev)." </a></li>";
	
	$n2 = MySQLi_Query($spojeni,"SELECT id, $nazev, id_nadrazeneho FROM homileticke_publikace WHERE  $str_kat='".addslashes($url_arr[4])."' AND id_nadrazeneho=".intval($row_n->id)." ");
	$row_n2 = MySQLi_fetch_object($n2);
	
	
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2]."/".$url_arr[3]."/".$url_arr[4]."/".$row_n2->id.".html\">".stripslashes($row_n2->$nazev)."</a></li>";
	
	
	
	$nx = MySQLi_Query($spojeni,"SELECT $nazev FROM homileticke_publikace WHERE id='".intval($url_arr[6])."'");
	$row_nx = MySQLi_fetch_object($nx);
	
	echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_nx->$nazev)."</h5></li>";
	}
}
elseif($url_arr[5])
{ 

	if($url_arr[2]=="texty")
	{ 
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">Texty</a></li>";
	
	$n = MySQLi_Query($spojeni,"SELECT id, $nazev FROM texty WHERE vnor=1 AND  $str_kat='".addslashes($url_arr[3])."'");
	$row_n = MySQLi_fetch_object($n);
	
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2]."/".$url_arr[3]."/".$row_n->id.".html\">".stripslashes($row_n->$nazev)."</a></li>";
	
	$nx = MySQLi_Query($spojeni,"SELECT $nazev FROM texty WHERE id='".intval($url_arr[5])."'");
	$row_nx = MySQLi_fetch_object($nx);
	
	echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_nx->$nazev)."</h5></li>";
	}
	elseif($url_arr[2]=="homilie")
	{ 
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">Homílie</a></li>";
	
	$n = MySQLi_Query($spojeni,"SELECT id, $nazev FROM homilie WHERE vnor=1 AND  $str_kat='".addslashes($url_arr[3])."'");
	$row_n = MySQLi_fetch_object($n);
	
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2]."/".$url_arr[3]."/".$row_n->id.".html\">".stripslashes($row_n->$nazev)."</a></li>";
	
	$nx = MySQLi_Query($spojeni,"SELECT $nazev FROM homilie WHERE id='".intval($url_arr[5])."'");
	$row_nx = MySQLi_fetch_object($nx);
	
	echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_nx->$nazev)."</h5></li>";
	}
	elseif($url_arr[2]=="homilie-deti")
	{ 
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">".__HOMILETICKE_PUBLIKACE__."</a></li>";
	
	$n = MySQLi_Query($spojeni,"SELECT id, $nazev FROM homilie_deti WHERE vnor=1 AND  $str_kat='".addslashes($url_arr[3])."'");
	$row_n = MySQLi_fetch_object($n);
	
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2]."/".$url_arr[3]."/".$row_n->id.".html\">".stripslashes($row_n->$nazev)."</a></li>";
	
	$nx = MySQLi_Query($spojeni,"SELECT $nazev FROM homilie_deti WHERE id='".intval($url_arr[5])."'");
	$row_nx = MySQLi_fetch_object($nx);
	
	echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_nx->$nazev)."</h5></li>";
	}
	elseif($url_arr[2]=="homileticke-publikace")
	{ 
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">".__HOMILETICKE_PUBLIKACE__."</a></li>";
	
	$n = MySQLi_Query($spojeni,"SELECT id, $nazev FROM homileticke_publikace WHERE vnor=1 AND  $str_kat='".addslashes($url_arr[3])."'");
	$row_n = MySQLi_fetch_object($n);
	
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2]."/".$url_arr[3]."/".$row_n->id.".html\">".stripslashes($row_n->$nazev)."</a></li>";
	
	$nx = MySQLi_Query($spojeni,"SELECT $nazev FROM homileticke_publikace WHERE id='".intval($url_arr[5])."'");
	$row_nx = MySQLi_fetch_object($nx);
	
	echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_nx->$nazev)."</h5></li>";
	}

}
elseif($url_arr[4])
{ 


// treti uroven
// novinky 
if($url_arr[2]=="aktuality")
{ 
echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">Aktuality</a></li>";

$n = MySQLi_Query($spojeni,"SELECT nadpis FROM aktuality WHERE id='".intval($url_arr[4])."'");
$row_n = MySQLi_fetch_object($n);

echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_n->nadpis)."</h5></li>";
}
elseif($url_arr[2]=="texty")
{ 
echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">Texty</a></li>";

$n = MySQLi_Query($spojeni,"SELECT $nazev FROM texty WHERE id='".intval($url_arr[4])."'");
$row_n = MySQLi_fetch_object($n);

echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_n->$nazev)."</h5></li>";
}
elseif($url_arr[2]=="homilie")
{ 
echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">Homílie</a></li>";

$n = MySQLi_Query($spojeni,"SELECT $nazev FROM homilie WHERE id='".intval($url_arr[4])."'");
$row_n = MySQLi_fetch_object($n);

echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_n->$nazev)."</h5></li>";
}
elseif($url_arr[2]=="homilie-deti")
{ 
echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">".__HOMILIE_DETI__."</a></li>";

$n = MySQLi_Query($spojeni,"SELECT $nazev FROM homilie_deti WHERE id='".intval($url_arr[4])."'");
$row_n = MySQLi_fetch_object($n);

echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_n->$nazev)."</h5></li>";
}
elseif($url_arr[2]=="homileticke-publikace")
{ 
echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">".__HOMILETICKE_PUBLIKACE__."</a></li>";

$n = MySQLi_Query($spojeni,"SELECT $nazev FROM homileticke_publikace WHERE id='".intval($url_arr[4])."'");
$row_n = MySQLi_fetch_object($n);

echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_n->$nazev)."</h5></li>";
}
elseif($url_arr[2]=="fotogalerie")
{
echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">Fotogalerie</a></li>";

$f = MySQLi_Query($spojeni,"SELECT nazev_cz FROM galerie WHERE id='".intval($url_arr[4])."'");
$row_f = MySQLi_fetch_object($f);

echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_f->nazev_cz)."</h5></li>";
}
else
{
$d1 = MySQLi_Query($spojeni,"SELECT id, url, nadpis FROM stranky WHERE str='".addslashes($url_arr[2])."' AND vnor=1");
$row_d1 = MySQLi_fetch_object($d1);

$d2 = MySQLi_Query($spojeni,"SELECT id, url, nadpis FROM stranky WHERE str='".addslashes($url_arr[3])."' AND vnor=2 AND id_nadrazeneho='".$row_d1->id."'");
$row_d2 = MySQLi_fetch_object($d2);

$url_3 = explode(".",$url_arr[4]);
$d3 = MySQLi_Query($spojeni,"SELECT nadpis FROM stranky WHERE str='".addslashes($url_3[0])."' AND vnor=3 AND id_nadrazeneho='".$row_d2->id."'");
$row_d3 = MySQLi_fetch_object($d3);

if($row_d1->url)
{
echo " <li class='breadcrumb-item'><a href=\"".stripslashes($row_d1->url)."\">".stripslashes($row_d1->nadpis)."</a></li>";
}
else
{
echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">".stripslashes($row_d1->nadpis)."</a></li>";
}

if($row_d2->url)
{
echo " <li class='breadcrumb-item'><a href=\"".stripslashes($row_d2->url)."\">".stripslashes($row_d2->nadpis)."</a></li>";
}
else
{
echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2]."/".$url_arr[3].".html\">".stripslashes($row_d2->nadpis)."</a></li>";
}

echo " <li class=\"breadcrumb-item active\"><h5>".stripslashes($row_d3->nadpis)."</h5></li>";
}

}
elseif($url_arr[3])
{ 
	if($url_arr[2]=='produkty')
	{
    echo " / <a href=\"/cz/produkty.html\">Produkty</a>";
	
	        $query_kat = MySQLi_Query($spojeni,"SELECT $nazev_kat  FROM kategorie WHERE $str_kat='".addslashes($_GET['idk'])."'  ") or die(err(1));
			$row_kat = MySQLi_fetch_object($query_kat);	
			echo ' / '.stripslashes($row_kat->$nazev_kat);	
	
	}
	else
	{
			// druha uroven
	$d1 = MySQLi_Query($spojeni,"SELECT id, url, nadpis FROM stranky WHERE str='".addslashes($url_arr[2])."' AND vnor=1");
	$row_d1 = MySQLi_fetch_object($d1);

	$url_2 = explode(".",$url_arr[3]);
	$d2 = MySQLi_Query($spojeni,"SELECT nadpis FROM stranky WHERE str='".addslashes($url_2[0])."' AND vnor=2 AND id_nadrazeneho='".$row_d1->id."'");
	$row_d2 = MySQLi_fetch_object($d2);
	if($row_d1->url)
	{
	echo " <li class='breadcrumb-item'><a href=\"".stripslashes($row_d1->url)."\">".stripslashes($row_d1->nadpis)."</a></li>";
	}
	else
	{
	echo " <li class='breadcrumb-item'><a href=\"".__URL__."/".__LANG__."/".$url_arr[2].".html\">".stripslashes($row_d1->nadpis)."</a></li>";
	}

	echo "<li class=\"breadcrumb-item active\"><h5>".stripslashes($row_d2->nadpis)."</h5></li>";
	}
}
elseif($url_arr[2] && $_SERVER['REQUEST_URI']!="/")
{ // prvni uroven 
$url_1 = explode(".",$url_arr[2]);


if($url_1[0]=="mapa-webu")
	{
	echo " <li class='breadcrumb-item'>Mapa webu</li>";
	}	

elseif($url_1[0]=="aktuality")
	{
	echo " <li class='breadcrumb-item'>Aktuality</li>";
	}		
elseif($url_1[0]=="novinky")
	{
	echo " <li class='breadcrumb-item active'><h5>Novinky</h5></li>";
	}					
elseif($url_1[0]=="prihlaseni")
	{
	echo " <li class='breadcrumb-item active'><h5>Přihlášení</h5></li>";
	}	
elseif($url_1[0]=="registrace")
	{
	echo " <li class='breadcrumb-item active'><h5>Registrace</h5></li>";
	}		
elseif($url_1[0]=="zapomenute-heslo")
	{
	echo " <li class='breadcrumb-item active'><h5>Zapomenuté heslo</h5></li>";
	}		
elseif($url_1[0]=="uprava-reg-udaju")
	{
	echo " <li class='breadcrumb-item active'><h5>Úprava údajů</h5></li>";
	}	
elseif($url_1[0]=="vyhledavani")
	{
	echo " <li class='breadcrumb-item active'><h5>Vyhledávání</h5></li>";
	}			
elseif($url_1[0]=="formular")
	{
	echo "<li class='breadcrumb-item'>Formulář</li>";
	}	
elseif($url_1[0]=="texty")
	{
	echo "<li class='breadcrumb-item active'><h5>Texty</h5></li>";
	}	
elseif($url_1[0]=="homilie")
	{
	echo "<li class='breadcrumb-item active'><h5>Homílie</h5></li>";
	}	
elseif($url_1[0]=="homilie-deti")
	{
	echo "<li class='breadcrumb-item active'><h5>".__HOMILIE_DETI__."</h5></li>";
	}	
elseif($url_1[0]=="homileticke-publikace")
	{
	echo "<li class='breadcrumb-item active'><h5>".__HOMILETICKE_PUBLIKACE__."</h5></li>";
	}					
else
{
	$d1 = MySQLi_Query($spojeni,"SELECT nadpis FROM stranky WHERE str='".addslashes($url_1[0])."' AND lang='".__LANG__."' ");
	$row_d1 = MySQLi_fetch_object($d1);
	echo " <li class='breadcrumb-item active'><h5>".stripslashes($row_d1->nadpis)."</h5></li>";
}		
/*
else
{
$d1 = MySQLi_Query($spojeni,"SELECT nadpis FROM stranky WHERE str='".addslashes($url_1[0])."' AND vnor=1");
$row_d1 = MySQLi_fetch_object($d1);
echo " <li class='breadcrumb-item active'><h5>".stripslashes($row_d1->nadpis)."</h5></li>";
}*/
}
else
{}

echo '</ol>   ';

}



function get_links3a($pocet,$limit)
{

  if($limit>0)
  {
  echo '<a href="?limit='.($_GET['limit']-20).'">';
  }
   echo '&laquo;';
  if($limit>0)
  {
  echo '</a>';
  } 

  echo '&nbsp;&nbsp;';

  for ($px=0;$px<ceil($pocet/20);$px++)
  {
  
	  if($limit==$limit2) 
	  {
	  echo " <span class='aktivni_strankovani'>".($px+1)."</span> ";
	  }
	  else
	  {
	  echo " <a href=\"?limit=".$limit2."\">";
	  echo $px+1;
	  echo "</a> ";
	  }
	  $limit2 = $limit2+20;
  }
  
  echo '&nbsp;&nbsp;';
  
  if($limit+20<$pocet)
  {
  echo '<a href="?limit='.($_GET['limit']+20).'" >';
  }
   echo '&raquo;';
  if($limit+20<$pocet)
  {
  echo '</a>';
  } 
}

function prilohy_podstranka($ids,$spojeni)
{
	$query_n2 = MySQLi_Query($spojeni,"SELECT * FROM prilohy WHERE id_stranky=".intval($ids)." ORDER BY razeni") or die(err(1));
	if(mysqli_num_rows($query_n2))
	{
		//echo '<div class="text-field-inner"><div class="col-md-9">';
		
		echo ' <div class="col-md-12 text-field"><div class="text-field-inner">';
		
		echo '<div class="prilohy">';
		echo '<h2>Přílohy</h2>';

        
		$x = 1;
		while($row_n2 = MySQLi_fetch_object($query_n2))
		{	
		 $velikost = round(filesize("./prilohy/".$row_n2->priloha)/1048576,2);	
		 $pripona_arr = explode(".",$row_n2->priloha);
		 $pripona_e = array_reverse($pripona_arr);
		 

		 echo '<div class="obal_priloha"><a href="/prilohy/'.$row_n2->priloha.'" target="_blank">'.stripslashes($row_n2->nazev).'</a> ('.$pripona_e[0].' / '.$velikost.' MB)</div>';
		 $x++;
        }
 

		echo '</div>';
		echo '</div></div>';

		
	}
}


function prilohy_novinka($ids,$spojeni)
{
	$query_n2 = MySQLi_Query($spojeni,"SELECT * FROM prilohy_novinky WHERE id_novinky=".intval($ids)." ORDER BY razeni") or die(err(1));
	if(mysqli_num_rows($query_n2))
	{
		echo '<div class="clear" style="height: 20px;"></div>';
		echo '<h2>Přílohy</h2>';
		echo '<div class="clear" style="height: 10px;"></div>';
		
		while($row_n2 = MySQLi_fetch_object($query_n2))
		{
		 $velikost = round(filesize("./prilohy/".$row_n2->priloha)/1048576,2);	
		 $pripona_arr = explode(".",$row_n2->priloha);
		 $pripona_e = array_reverse($pripona_arr);
		  echo '<div class="det_priloha"><a href="/prilohy/'.$row_n2->priloha.'" target="_blank">'.stripslashes($row_n2->nazev).'</a> ('.$pripona_e[0].' / '.$velikost.' MB)</div>';
		 echo '<div class="clear"></div>';
        }
		
	}
}


function prepocet_euro($k)
{
if($k>0)
 {
  $prepocet = round(($k/__EURO_KURZ__),2);
 }
 else
 {
 $prepocet = '';
 }	

return $prepocet;
}






function novinky_podstranka($id,$spojeni)
{

	$query_novinky = MySQLi_Query($spojeni,"SELECT * FROM aktuality WHERE aktivni=1 AND typ=5 ORDER BY datum DESC ") or die(err(1));
 

		 $x = 1;
		  echo '<div class="row">';

		 while ($row_novinky = MySQLi_fetch_object($query_novinky))
		 {
           
			   if($row_novinky->do_stranek)
			   {
				   $pole = unserialize($row_novinky->do_stranek);
				   
				   if(in_array($id,$pole))
				   {
				   
				   
				  
					 
				   
					  echo '
						<div class="col-md-6">
						<div class="private-article">
						<a href="/'.__LANG__.'/aktuality2/'.$row_novinky->str.'/'.$row_novinky->id.'.html">Detail článku</a>
						  <h6>'.stripslashes($row_novinky->nadpis).'</h6>
						<span>Datum: '.date("d.m.Y",$row_novinky->datum).'  </span>
						</div>
						</div>';
						
						
	
				  
				  
				  $x++;
	             }
		       }
		 }
		 
		 echo '</div>';
	 
 
}



function dej_mesic($mesicx)
                {
        if($mesicx=="1" || $mesicx=="01") {$mesic_2="leden";}
        if($mesicx=="2" || $mesicx=="02") {$mesic_2="únor"; }
        if($mesicx=="3" || $mesicx=="03") {$mesic_2="březen"; }
        if($mesicx=="4" || $mesicx=="04") {$mesic_2="duben"; }
        if($mesicx=="5" || $mesicx=="05") {$mesic_2="květen";}
        if($mesicx=="6" || $mesicx=="06") {$mesic_2="červen"; }
        if($mesicx=="7" || $mesicx=="07") {$mesic_2="červenec";}
        if($mesicx=="8" || $mesicx=="08") {$mesic_2="srpen"; }
        if($mesicx=="9" || $mesicx=="09") {$mesic_2="září";}
        if($mesicx=="10") {$mesic_2="říjen";}
        if($mesicx=="11") {$mesic_2="listopad";}
        if($mesicx=="12") {$mesic_2="prosinec";}
             return $mesic_2;
             }



function strankovani_homilie($idk,$spojeni)
{

$nazev = "nazev_".__LANG__;
$obsah = "obsah_".__LANG__;
$str = "str_".__LANG__;
$aktivni = "aktivni_".__LANG__;

echo "<div class='article-pagination'>
<nav aria-label='Page navigation example'>
<ul class='pagination'>";

        $query_tx = MySQLi_Query($spojeni,"SELECT id FROM homilie WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=1 ") or die(err(1));
	    if(mysqli_num_rows($query_tx))
	    {
			// mame cteni
			if(!$_GET['t'])
			{
				echo "<li class='page-item active'><a class='page-link' href='#'>".__CTENI__."</a></li>";
			}
			else
			{
				echo "<li class='page-item'><a class='page-link' href='".$_SERVER["SCRIPT_URL"]."'>".__CTENI__."</a></li>";
			}
		}
		
	
		
			/*$x = 1;
			
			$query_tx2 = MySQLi_Query($spojeni,"SELECT id FROM homilie WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=0 ORDER BY nazev_cz ") or die(err(1));
	        while($row_tx2 = MySQLi_fetch_object($query_tx2))
	        {
				echo "<li class='page-item";
				  if((mysqli_num_rows($query_tx)<1 && !$_GET['t'] && $x==1) || ($_GET['t']==$row_tx2->id)){ echo " active";}
				echo "'><a class='page-link' href='?t=".$row_tx2->id."'>".$x."</a></li>";
				
				$x++;
			}*/
	        
	$query_tx2 = MySQLi_Query($spojeni,"SELECT id FROM homilie WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=0 ORDER BY nazev_cz ") or die(err(1));        
	        
	        
	$ps = mysqli_num_rows($query_tx2);
	
	if(!$_GET['strana'])
	{
	$ps2=1;
	}
	else
	{
	$ps2 = intval($_GET['strana']);
	}

	$leva = intval(max(1,$ps2-5));
	$prava = intval(min($ps,$ps2+5));
	$leva_pocet = $ps2 - $leva;
	$prava_pocet = $prava - $ps2;

	if ( $leva_pocet + $prava_pocet != 10 )
	{
		if ( $leva_pocet < 5 )
			$prava = min($ps, $prava + ( 5 - $leva_pocet ));

		if ( $prava_pocet < 5 )
			$leva = max(1, $leva - ( 5 - $prava_pocet ));
	}


	if($leva>1)
	{
		$query_tx3a = MySQLi_Query($spojeni,"SELECT id FROM homilie WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=0 ORDER BY nazev_cz ASC LIMIT 1  ") or die(err(1)); 
		$row_tx3a = MySQLi_fetch_object($query_tx3a);
		if((mysqli_num_rows($query_tx)<1 && !$_GET['t']) || ($_GET['t']==$row_tx3a->id))
		{
			echo "<li class='page-item'><a class='page-link active' href='?t=".$row_tx3a->id."&strana=1'>1</a></li>...";
		}
		else
		{
			echo "<li class='page-item'><a class='page-link' href='?t=".$row_tx3a->id."&strana=1'>1</a></li>...";
		}
	    
	}
	
	
	
	$px=$leva;
	$x = 1;
	//if($leva == 1){ $leva2 = 0;}
	$leva2 = $leva - 1;
	$query_tx3 = MySQLi_Query($spojeni,"SELECT id FROM homilie WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=0 ORDER BY nazev_cz LIMIT $leva2, ".($prava - $leva2)."  ") or die(err(1)); 
	while($row_tx3 = MySQLi_fetch_object($query_tx3))   
	{

		if((mysqli_num_rows($query_tx)<1 && !$_GET['t'] && $x==1) || ($_GET['t']==$row_tx3->id))
		{

		echo "<li class='page-item active'><a class='page-link' href='?t=".$row_tx3->id."&strana=".$px."'>".$px."</a></li>";
	
		}
		else
		{
			
	    echo "<li class='page-item'><a class='page-link' href='?t=".$row_tx3->id."&strana=".$px."'>".$px."</a></li>";
	
		}
	
		$limit2 = $px*$kolik;
		$px++;
		$x++;
	}

	if($prava<$ps)
	{
	    $query_tx3b = MySQLi_Query($spojeni,"SELECT id FROM homilie WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=0 ORDER BY nazev_cz DESC LIMIT 1  ") or die(err(1)); 
		$row_tx3b = MySQLi_fetch_object($query_tx3b);
		if((mysqli_num_rows($query_tx)<1 && !$_GET['t']) || ($_GET['t']==$row_tx3a->id))
		{
			echo "...<li class='page-item'><a class='page-link active' href='?t=".$row_tx3b->id."&strana=".$ps."'>".$ps."</a></li>";
		}
		else
		{
			echo "...<li class='page-item'><a class='page-link' href='?t=".$row_tx3b->id."&strana=".$ps."'>".$ps."</a></li>";
		}
	}
			
		
		
echo "</ul>
</nav>
</div>";

}



function strankovani_homilie_deti($idk,$spojeni)
{

$nazev = "nazev_".__LANG__;
$obsah = "obsah_".__LANG__;
$str = "str_".__LANG__;
$aktivni = "aktivni_".__LANG__;

echo "<div class='article-pagination'>
<nav aria-label='Page navigation example'>
<ul class='pagination'>";

        $query_tx = MySQLi_Query($spojeni,"SELECT id FROM homilie_deti WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=1 ") or die(err(1));
	    if(mysqli_num_rows($query_tx))
	    {
			// mame cteni
			if(!$_GET['t'])
			{
				echo "<li class='page-item active'><a class='page-link' href='#'>".__CTENI__."</a></li>";
			}
			else
			{
				echo "<li class='page-item'><a class='page-link' href='".$_SERVER["SCRIPT_URL"]."'>".__CTENI__."</a></li>";
			}
		}
		
	
		
			/*$x = 1;
			
			$query_tx2 = MySQLi_Query($spojeni,"SELECT id FROM homilie WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=0 ORDER BY nazev_cz ") or die(err(1));
	        while($row_tx2 = MySQLi_fetch_object($query_tx2))
	        {
				echo "<li class='page-item";
				  if((mysqli_num_rows($query_tx)<1 && !$_GET['t'] && $x==1) || ($_GET['t']==$row_tx2->id)){ echo " active";}
				echo "'><a class='page-link' href='?t=".$row_tx2->id."'>".$x."</a></li>";
				
				$x++;
			}*/
	        
	$query_tx2 = MySQLi_Query($spojeni,"SELECT id FROM homilie_deti WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=0 ORDER BY nazev_cz ") or die(err(1));        
	        
	        
	$ps = mysqli_num_rows($query_tx2);
	
	if(!$_GET['strana'])
	{
	$ps2=1;
	}
	else
	{
	$ps2 = intval($_GET['strana']);
	}

	$leva = intval(max(1,$ps2-5));
	$prava = intval(min($ps,$ps2+5));
	$leva_pocet = $ps2 - $leva;
	$prava_pocet = $prava - $ps2;

	if ( $leva_pocet + $prava_pocet != 10 )
	{
		if ( $leva_pocet < 5 )
			$prava = min($ps, $prava + ( 5 - $leva_pocet ));

		if ( $prava_pocet < 5 )
			$leva = max(1, $leva - ( 5 - $prava_pocet ));
	}


	if($leva>1)
	{
		$query_tx3a = MySQLi_Query($spojeni,"SELECT id FROM homilie_deti WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=0 ORDER BY nazev_cz ASC LIMIT 1  ") or die(err(1)); 
		$row_tx3a = MySQLi_fetch_object($query_tx3a);
		if((mysqli_num_rows($query_tx)<1 && !$_GET['t']) || ($_GET['t']==$row_tx3a->id))
		{
			echo "<li class='page-item'><a class='page-link active' href='?t=".$row_tx3a->id."&strana=1'>1</a></li>...";
		}
		else
		{
			echo "<li class='page-item'><a class='page-link' href='?t=".$row_tx3a->id."&strana=1'>1</a></li>...";
		}
	    
	}
	
	
	
	$px=$leva;
	$x = 1;
	//if($leva == 1){ $leva2 = 0;}
	$leva2 = $leva - 1;
	$query_tx3 = MySQLi_Query($spojeni,"SELECT id FROM homilie_deti WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=0 ORDER BY nazev_cz LIMIT $leva2, ".($prava - $leva2)."  ") or die(err(1)); 
	while($row_tx3 = MySQLi_fetch_object($query_tx3))   
	{

		if((mysqli_num_rows($query_tx)<1 && !$_GET['t'] && $x==1) || ($_GET['t']==$row_tx3->id))
		{

		echo "<li class='page-item active'><a class='page-link' href='?t=".$row_tx3->id."&strana=".$px."'>".$px."</a></li>";
	
		}
		else
		{
			
	    echo "<li class='page-item'><a class='page-link' href='?t=".$row_tx3->id."&strana=".$px."'>".$px."</a></li>";
	
		}
	
		$limit2 = $px*$kolik;
		$px++;
		$x++;
	}

	if($prava<$ps)
	{
	    $query_tx3b = MySQLi_Query($spojeni,"SELECT id FROM homilie_deti WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=0 ORDER BY nazev_cz DESC LIMIT 1  ") or die(err(1)); 
		$row_tx3b = MySQLi_fetch_object($query_tx3b);
		if((mysqli_num_rows($query_tx)<1 && !$_GET['t']) || ($_GET['t']==$row_tx3a->id))
		{
			echo "...<li class='page-item'><a class='page-link active' href='?t=".$row_tx3b->id."&strana=".$ps."'>".$ps."</a></li>";
		}
		else
		{
			echo "...<li class='page-item'><a class='page-link' href='?t=".$row_tx3b->id."&strana=".$ps."'>".$ps."</a></li>";
		}
	}
			
		
		
echo "</ul>
</nav>
</div>";

}



function strankovani_homilie_pub($idk,$spojeni)
{

$nazev = "nazev_".__LANG__;
$obsah = "obsah_".__LANG__;
$str = "str_".__LANG__;
$aktivni = "aktivni_".__LANG__;

echo "<div class='article-pagination'>
<nav aria-label='Page navigation example'>
<ul class='pagination'>";

        $query_tx = MySQLi_Query($spojeni,"SELECT id FROM homileticke_publikace WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=1 ") or die(err(1));
	    if(mysqli_num_rows($query_tx))
	    {
			// mame cteni
			if(!$_GET['t'])
			{
				echo "<li class='page-item active'><a class='page-link' href='#'>".__CTENI__."</a></li>";
			}
			else
			{
				echo "<li class='page-item'><a class='page-link' href='".$_SERVER["SCRIPT_URL"]."'>".__CTENI__."</a></li>";
			}
		}
		
	
 
	        
	$query_tx2 = MySQLi_Query($spojeni,"SELECT id FROM homileticke_publikace WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=0 ORDER BY nazev_cz ") or die(err(1));        
	        
	        
	$ps = mysqli_num_rows($query_tx2);
	
	if(!$_GET['strana'])
	{
	$ps2=1;
	}
	else
	{
	$ps2 = intval($_GET['strana']);
	}

	$leva = intval(max(1,$ps2-5));
	$prava = intval(min($ps,$ps2+5));
	$leva_pocet = $ps2 - $leva;
	$prava_pocet = $prava - $ps2;

	if ( $leva_pocet + $prava_pocet != 10 )
	{
		if ( $leva_pocet < 5 )
			$prava = min($ps, $prava + ( 5 - $leva_pocet ));

		if ( $prava_pocet < 5 )
			$leva = max(1, $leva - ( 5 - $prava_pocet ));
	}


	if($leva>1)
	{
		$query_tx3a = MySQLi_Query($spojeni,"SELECT id FROM homileticke_publikace WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=0 ORDER BY nazev_cz ASC LIMIT 1  ") or die(err(1)); 
		$row_tx3a = MySQLi_fetch_object($query_tx3a);
		if((mysqli_num_rows($query_tx)<1 && !$_GET['t']) || ($_GET['t']==$row_tx3a->id))
		{
			echo "<li class='page-item'><a class='page-link active' href='?t=".$row_tx3a->id."&strana=1'>1</a></li>...";
		}
		else
		{
			echo "<li class='page-item'><a class='page-link' href='?t=".$row_tx3a->id."&strana=1'>1</a></li>...";
		}
	    
	}
	
	
	
	$px=$leva;
	$x = 1;
	//if($leva == 1){ $leva2 = 0;}
	$leva2 = $leva - 1;
	$query_tx3 = MySQLi_Query($spojeni,"SELECT id FROM homileticke_publikace WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=0 ORDER BY nazev_cz LIMIT $leva2, ".($prava - $leva2)."  ") or die(err(1)); 
	while($row_tx3 = MySQLi_fetch_object($query_tx3))   
	{

		if((mysqli_num_rows($query_tx)<1 && !$_GET['t'] && $x==1) || ($_GET['t']==$row_tx3->id))
		{

		echo "<li class='page-item active'><a class='page-link' href='?t=".$row_tx3->id."&strana=".$px."'>".$px."</a></li>";
	
		}
		else
		{
			
	    echo "<li class='page-item'><a class='page-link' href='?t=".$row_tx3->id."&strana=".$px."'>".$px."</a></li>";
	
		}
	
		$limit2 = $px*$kolik;
		$px++;
		$x++;
	}

	if($prava<$ps)
	{
	    $query_tx3b = MySQLi_Query($spojeni,"SELECT id FROM homileticke_publikace WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=0 ORDER BY nazev_cz DESC LIMIT 1  ") or die(err(1)); 
		$row_tx3b = MySQLi_fetch_object($query_tx3b);
		if((mysqli_num_rows($query_tx)<1 && !$_GET['t']) || ($_GET['t']==$row_tx3a->id))
		{
			echo "...<li class='page-item'><a class='page-link active' href='?t=".$row_tx3b->id."&strana=".$ps."'>".$ps."</a></li>";
		}
		else
		{
			echo "...<li class='page-item'><a class='page-link' href='?t=".$row_tx3b->id."&strana=".$ps."'>".$ps."</a></li>";
		}
	}
			
		
		
echo "</ul>
</nav>
</div>";

}


function texty_homilie($idk,$spojeni)
{
$nazev = "nazev_".__LANG__;
$obsah = "obsah_".__LANG__;
$str = "str_".__LANG__;
$aktivni = "aktivni_".__LANG__;
$autor = "autor_".__LANG__;	
$prelozil = "prelozil_".__LANG__;	
$zdroj = "zdroj_".__LANG__;

if(__LANG__=='sk')
{
    $prelozil_nazev = "Slovensky překlad";
}
else
{
	$prelozil_nazev = "Český překlad";
}


	if($_GET['t'])
	{
		    $query_t = MySQLi_Query($spojeni,"SELECT * FROM homilie WHERE $aktivni=1 AND id='".intval($_GET['t'])."'  ") or die(err(1));
			$row_t = MySQLi_fetch_object($query_t);
			
			if($_SESSION['prihlaseni'])
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo stripslashes($row_t->$obsah);
				echo "</p></div>";
				
				
				echo "<div class='article-bottom'>
					<a class='btn btn-small' href='/".__LANG__."/homilie.html'>
					<i class='fa fa-reply'></i>
					".__ZPET_NA_VYPIS__."
					</a>
					<a class='btn btn-ghost btn-small float-right' href='' onclick='window.print();'>
					<i class='fa fa-print'></i>
					Tisk
					</a>
					</div>";
				
				strankovani_homilie($_GET['idh'],$spojeni);
				
				
				
			}
			else
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b>".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo cut_text(stripslashes($row_t->$obsah),__DELKA__);
				echo "...</p>
				<div class='private-content'>
					<h3>".__CELY_TEXT_HOMILIE__."</h3>
					<a class='btn btn-small' href='/".__LANG__."/prihlaseni.html'>
					".__PRIHLASIT_SE__."
					</a>
					<a class='btn btn-ghost btn-small' href='/".__LANG__."/registrace.html'>
					".__REGISTROVAT_SE__."
					</a>
					</div>
					</div>
					
					
					
					<div class='article-bottom'>
					<a class='btn btn-small' href='/".__LANG__."/homilie.html'>
					<i class='fa fa-reply'></i>
					".__ZPET_NA_VYPIS__."
					</a>
					<a class='btn btn-ghost btn-small float-right' href='' onclick='window.print();'>
					<i class='fa fa-print'></i>
					Tisk
					</a>
					</div>";
			}
	}
	else
	{
		$query_tx = MySQLi_Query($spojeni,"SELECT id FROM homilie WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=1 ") or die(err(1));
	    if(mysqli_num_rows($query_tx))
	    {
			$query_t = MySQLi_Query($spojeni,"SELECT * FROM homilie WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=1 ") or die(err(1));
			$row_t = MySQLi_fetch_object($query_t);
			
			if($_SESSION['prihlaseni'])
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo stripslashes($row_t->$obsah);
				echo "</p></div>";
				
				strankovani_homilie($_GET['idh'],$spojeni);
				
				
			}
			else
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo cut_text(stripslashes($row_t->$obsah),__DELKA__);
				echo "...</p><div class='private-content'>
					<h3>".__CELY_TEXT_HOMILIE__."</h3>
					<a class='btn btn-small' href='/".__LANG__."/prihlaseni.html'>
					".__PRIHLASIT_SE__."
					</a>
					<a class='btn btn-ghost btn-small' href='/".__LANG__."/registrace.html'>
					".__REGISTROVAT_SE__."
					</a>
					</div>
					</div>
					
					
					
					<div class='article-bottom'>
					<a class='btn btn-small' href='/".__LANG__."/homilie.html'>
					<i class='fa fa-reply'></i>
					".__ZPET_NA_VYPIS__."
					</a>
					<a class='btn btn-ghost btn-small float-right' href='' onclick='window.print();'>
					<i class='fa fa-print'></i>
					Tisk
					</a>
					</div>";
			}
			
		}
		else
		{
			$query_t = MySQLi_Query($spojeni,"SELECT * FROM homilie WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' ORDER BY nazev_cz, id ") or die(err(1));
			$row_t = MySQLi_fetch_object($query_t);
			
			if($_SESSION['prihlaseni'])
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo stripslashes($row_t->$obsah);
				echo "</p></div>";
				
				strankovani_homilie($_GET['idh'],$spojeni);
				
				
			}
			else
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
					
				echo "<div class='print-area'><p>";
				echo cut_text(stripslashes($row_t->$obsah),__DELKA__);
				echo "...</p><div class='private-content'>
					<h3>".__CELY_TEXT_HOMILIE__."</h3>
					<a class='btn btn-small' href='/".__LANG__."/prihlaseni.html'>
					".__PRIHLASIT_SE__."
					</a>
					<a class='btn btn-ghost btn-small' href='/".__LANG__."/registrace.html'>
					".__REGISTROVAT_SE__."
					</a>
					</div>
					</div>
					
					
					
					<div class='article-bottom'>
					<a class='btn btn-small' href='/".__LANG__."/homilie.html'>
					<i class='fa fa-reply'></i>
					".__ZPET_NA_VYPIS__."
					</a>
					<a class='btn btn-ghost btn-small float-right' href='' onclick='window.print();'>
					<i class='fa fa-print'></i>
					Tisk
					</a>
					</div>";
			}
		}
		

		
	 
    }
}



function texty_homilie_deti($idk,$spojeni)
{
$nazev = "nazev_".__LANG__;
$obsah = "obsah_".__LANG__;
$str = "str_".__LANG__;
$aktivni = "aktivni_".__LANG__;
$autor = "autor_".__LANG__;	
$prelozil = "prelozil_".__LANG__;	
$zdroj = "zdroj_".__LANG__;

if(__LANG__=='sk')
{
    $prelozil_nazev = "Slovensky překlad";
}
else
{
	$prelozil_nazev = "Český překlad";
}


	if($_GET['t'])
	{
		    $query_t = MySQLi_Query($spojeni,"SELECT * FROM homilie_deti WHERE $aktivni=1 AND id='".intval($_GET['t'])."'  ") or die(err(1));
			$row_t = MySQLi_fetch_object($query_t);
			
			if($_SESSION['prihlaseni'])
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo stripslashes($row_t->$obsah);
				echo "</p></div>";
				
				
				echo "<div class='article-bottom'>
					<a class='btn btn-small' href='/".__LANG__."/homilie-deti.html'>
					<i class='fa fa-reply'></i>
					".__ZPET_NA_VYPIS__."
					</a>
					<a class='btn btn-ghost btn-small float-right' href='' onclick='window.print();'>
					<i class='fa fa-print'></i>
					Tisk
					</a>
					</div>";
				
				strankovani_homilie($_GET['idh'],$spojeni);
				
				
				
			}
			else
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b>".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo cut_text(stripslashes($row_t->$obsah),__DELKA__);
				echo "...</p>
				<div class='private-content'>
					<h3>".__CELY_TEXT_HOMILIE__."</h3>
					<a class='btn btn-small' href='/".__LANG__."/prihlaseni.html'>
					".__PRIHLASIT_SE__."
					</a>
					<a class='btn btn-ghost btn-small' href='/".__LANG__."/registrace.html'>
					".__REGISTROVAT_SE__."
					</a>
					</div>
					</div>
					
					
					
					<div class='article-bottom'>
					<a class='btn btn-small' href='/".__LANG__."/homilie-deti.html'>
					<i class='fa fa-reply'></i>
					".__ZPET_NA_VYPIS__."
					</a>
					<a class='btn btn-ghost btn-small float-right' href='' onclick='window.print();'>
					<i class='fa fa-print'></i>
					Tisk
					</a>
					</div>";
			}
	}
	else
	{
		$query_tx = MySQLi_Query($spojeni,"SELECT id FROM homilie_deti WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=1 ") or die(err(1));
	    if(mysqli_num_rows($query_tx))
	    {
			$query_t = MySQLi_Query($spojeni,"SELECT * FROM homilie_deti WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=1 ") or die(err(1));
			$row_t = MySQLi_fetch_object($query_t);
			
			if($_SESSION['prihlaseni'])
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo stripslashes($row_t->$obsah);
				echo "</p></div>";
				
				strankovani_homilie_deti($_GET['idh'],$spojeni);
				
				
			}
			else
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo cut_text(stripslashes($row_t->$obsah),__DELKA__);
				echo "...</p><div class='private-content'>
					<h3>".__CELY_TEXT_HOMILIE__."</h3>
					<a class='btn btn-small' href='/".__LANG__."/prihlaseni.html'>
					".__PRIHLASIT_SE__."
					</a>
					<a class='btn btn-ghost btn-small' href='/".__LANG__."/registrace.html'>
					".__REGISTROVAT_SE__."
					</a>
					</div>
					</div>
					
					
					
					<div class='article-bottom'>
					<a class='btn btn-small' href='/".__LANG__."/homilie-deti.html'>
					<i class='fa fa-reply'></i>
					".__ZPET_NA_VYPIS__."
					</a>
					<a class='btn btn-ghost btn-small float-right' href='' onclick='window.print();'>
					<i class='fa fa-print'></i>
					Tisk
					</a>
					</div>";
			}
			
		}
		else
		{
			$query_t = MySQLi_Query($spojeni,"SELECT * FROM homilie_deti WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' ORDER BY nazev_cz, id ") or die(err(1));
			$row_t = MySQLi_fetch_object($query_t);
			
			if($_SESSION['prihlaseni'])
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo stripslashes($row_t->$obsah);
				echo "</p></div>";
				
				strankovani_homilie($_GET['idh'],$spojeni);
				
				
			}
			else
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
					
				echo "<div class='print-area'><p>";
				echo cut_text(stripslashes($row_t->$obsah),__DELKA__);
				echo "...</p><div class='private-content'>
					<h3>".__CELY_TEXT_HOMILIE__."</h3>
					<a class='btn btn-small' href='/".__LANG__."/prihlaseni.html'>
					".__PRIHLASIT_SE__."
					</a>
					<a class='btn btn-ghost btn-small' href='/".__LANG__."/registrace.html'>
					".__REGISTROVAT_SE__."
					</a>
					</div>
					</div>
					
					
					
					<div class='article-bottom'>
					<a class='btn btn-small' href='/".__LANG__."/homilie-deti.html'>
					<i class='fa fa-reply'></i>
					".__ZPET_NA_VYPIS__."
					</a>
					<a class='btn btn-ghost btn-small float-right' href='' onclick='window.print();'>
					<i class='fa fa-print'></i>
					Tisk
					</a>
					</div>";
			}
		}
		

		
	 
    }
}



function texty_homilie_pub($idk,$spojeni)
{
$nazev = "nazev_".__LANG__;
$obsah = "obsah_".__LANG__;
$str = "str_".__LANG__;
$aktivni = "aktivni_".__LANG__;
$autor = "autor_".__LANG__;	
$prelozil = "prelozil_".__LANG__;	
$zdroj = "zdroj_".__LANG__;

if(__LANG__=='sk')
{
    $prelozil_nazev = "Slovensky překlad";
}
else
{
	$prelozil_nazev = "Český překlad";
}


	if($_GET['t'])
	{
		    $query_t = MySQLi_Query($spojeni,"SELECT * FROM homileticke_publikace WHERE $aktivni=1 AND id='".intval($_GET['t'])."'  ") or die(err(1));
			$row_t = MySQLi_fetch_object($query_t);
			
			if($_SESSION['prihlaseni'])
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo stripslashes($row_t->$obsah);
				echo "</p></div>";
				
				
				echo "<div class='article-bottom'>
					<a class='btn btn-small' href='/".__LANG__."/homileticke-publikace.html'>
					<i class='fa fa-reply'></i>
					".__ZPET_NA_VYPIS__."
					</a>
					<a class='btn btn-ghost btn-small float-right' href='' onclick='window.print();'>
					<i class='fa fa-print'></i>
					Tisk
					</a>
					</div>";
				
				strankovani_homilie_pub($_GET['idh'],$spojeni);
				
				
				
			}
			else
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b>".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo cut_text(stripslashes($row_t->$obsah),__DELKA__);
				echo "...</p>
				<div class='private-content'>
					<h3>".__CELY_TEXT_HOMILIE__."</h3>
					<a class='btn btn-small' href='/".__LANG__."/prihlaseni.html'>
					".__PRIHLASIT_SE__."
					</a>
					<a class='btn btn-ghost btn-small' href='/".__LANG__."/registrace.html'>
					".__REGISTROVAT_SE__."
					</a>
					</div>
					</div>
					
					
					
					<div class='article-bottom'>
					<a class='btn btn-small' href='/".__LANG__."/homileticke-publikace.html'>
					<i class='fa fa-reply'></i>
					".__ZPET_NA_VYPIS__."
					</a>
					<a class='btn btn-ghost btn-small float-right' href='' onclick='window.print();'>
					<i class='fa fa-print'></i>
					Tisk
					</a>
					</div>";
			}
	}
	else
	{
		$query_tx = MySQLi_Query($spojeni,"SELECT id FROM homileticke_publikace WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=1 ") or die(err(1));
	    if(mysqli_num_rows($query_tx))
	    {
			$query_t = MySQLi_Query($spojeni,"SELECT * FROM homileticke_publikace WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' AND typ=1 ") or die(err(1));
			$row_t = MySQLi_fetch_object($query_t);
			
			if($_SESSION['prihlaseni'])
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo stripslashes($row_t->$obsah);
				echo "</p></div>";
				
				strankovani_homilie_pub($_GET['idh'],$spojeni);
				
				
			}
			else
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo cut_text(stripslashes($row_t->$obsah),__DELKA__);
				echo "...</p><div class='private-content'>
					<h3>".__CELY_TEXT_HOMILIE__."</h3>
					<a class='btn btn-small' href='/".__LANG__."/prihlaseni.html'>
					".__PRIHLASIT_SE__."
					</a>
					<a class='btn btn-ghost btn-small' href='/".__LANG__."/registrace.html'>
					".__REGISTROVAT_SE__."
					</a>
					</div>
					</div>
					
					
					
					<div class='article-bottom'>
					<a class='btn btn-small' href='/".__LANG__."/homileticke-publikace.html'>
					<i class='fa fa-reply'></i>
					".__ZPET_NA_VYPIS__."
					</a>
					<a class='btn btn-ghost btn-small float-right' href='' onclick='window.print();'>
					<i class='fa fa-print'></i>
					Tisk
					</a>
					</div>";
			}
			
		}
		else
		{
			$query_t = MySQLi_Query($spojeni,"SELECT * FROM homileticke_publikace WHERE $aktivni=1 AND id_nadrazeneho='".intval($idk)."' ORDER BY nazev_cz, id ") or die(err(1));
			$row_t = MySQLi_fetch_object($query_t);
			
			if($_SESSION['prihlaseni'])
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
				echo "<div class='print-area'><p>";
				echo stripslashes($row_t->$obsah);
				echo "</p></div>";
				
				strankovani_homilie_pub($_GET['idh'],$spojeni);
				
				
			}
			else
			{
				echo "<div class='article-credit'>
				<span>";
					
					if($row_t->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_t->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_t->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_t->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_t->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_t->typ!=1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".stripslashes($row_t->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
					
					
				echo "<div class='print-area'><p>";
				echo cut_text(stripslashes($row_t->$obsah),__DELKA__);
				echo "...</p><div class='private-content'>
					<h3>".__CELY_TEXT_HOMILIE__."</h3>
					<a class='btn btn-small' href='/".__LANG__."/prihlaseni.html'>
					".__PRIHLASIT_SE__."
					</a>
					<a class='btn btn-ghost btn-small' href='/".__LANG__."/registrace.html'>
					".__REGISTROVAT_SE__."
					</a>
					</div>
					</div>
					
					
					
					<div class='article-bottom'>
					<a class='btn btn-small' href='/".__LANG__."/homileticke-publikace.html'>
					<i class='fa fa-reply'></i>
					".__ZPET_NA_VYPIS__."
					</a>
					<a class='btn btn-ghost btn-small float-right' href='' onclick='window.print();'>
					<i class='fa fa-print'></i>
					Tisk
					</a>
					</div>";
			}
		}
		

		
	 
    }
}



function asc2bin ($ascii)
{
  while ( strlen($ascii) > 0 )
  {
   $byte = ""; $i = 0;
   $byte = substr($ascii, 0, 1);
   while ( $byte != chr($i) ) { $i++; }
   $byte = base_convert($i, 10, 2);
   $byte = str_repeat("0", (8 - strlen($byte)) ) . $byte; 
   $ascii = substr($ascii, 1);
   $binary = "$binary$byte";
  }
  return $binary;
} 

function bin2asc ($binary)
{
  $i = 0;
  while ( strlen($binary) > 3 )
  {
   $byte[$i] = substr($binary, 0, 8);
   $byte[$i] = base_convert($byte[$i], 2, 10);
   $byte[$i] = chr($byte[$i]);
   $binary = substr($binary, 8);
   $ascii = "$ascii$byte[$i]";
  }
  return $ascii;
} 

function sanitize($s)
{
	if(is_numeric($s))
	{ 
	 $san = intval($s);	// cele cislo
	}
	elseif(is_float($s))
	{
	 $san = floatval($s);	// des. cislo
	}
	elseif(is_array($s))
	{   $s2 = array();
	      foreach ($s as $key => $value)
               {
                $s2[$key] = sanitize($value);
               }

	 $san = $s2;	// pole
        }
	else
	{
	 $san = addslashes(strip_tags($s));	// retezec	
	}

return $san;
}


$kapky_verses = array(
  'Gn' => array('cs' => 'Gn', 'sk' => 'Gn', 'pl' => 'Rdz', 'en' => 'Gn', 'hu' => 'Ter', 'ru' => 'Быт', 'uk' => 'Бут', 'id' => '01'), // 1. Mojžíšova
  'Ex' => array('cs' => 'Ex', 'sk' => 'Ex', 'pl' => 'Wj', 'en' => 'Ex', 'hu' => 'Kiv', 'ru' => 'Исх', 'uk' => 'Вих', 'id' => '02'), // 2. Mojžíšova
  'Lv' => array('cs' => 'Lv', 'sk' => 'Lv', 'pl' => 'Kpł', 'en' => 'Lv', 'hu' => 'Lev', 'ru' => 'Лев', 'uk' => 'Лев', 'id' => '03'), // 3. Mojžíšova
  'Nm' => array('cs' => 'Nm', 'sk' => 'Nm', 'pl' => 'Lb', 'en' => 'Nm', 'hu' => 'Szám', 'ru' => 'Числ', 'uk' => 'Чис', 'id' => '04'), // 4. Mojžíšova
  'Dt' => array('cs' => 'Dt', 'sk' => 'Dt', 'pl' => 'Pwt', 'en' => 'Dn', 'hu' => 'MTörv', 'ru' => 'Втор', 'uk' => 'Втор', 'id' => '05'), // 5. Mojžíšova
  'Joz' => array('cs' => 'Joz', 'sk' => 'Joz', 'pl' => 'Joz', 'en' => 'Jos', 'hu' => 'Józs', 'ru' => 'Ис Нав', 'uk' => 'ІсНав', 'id' => '06'), // Jozue
  'Sd' => array('cs' => 'Sd', 'sk' => 'Sdc', 'pl' => 'Sdz', 'en' => 'Jgs', 'hu' => 'Bír', 'ru' => 'Суд', 'uk' => 'Суд', 'id' => '07'), // Soudcu
  'Rt' => array('cs' => 'Rt', 'sk' => 'Rút', 'pl' => 'Rt', 'en' => 'Ru', 'hu' => 'Rut', 'ru' => 'Руф', 'uk' => 'Рут', 'id' => '08'), // Rút
  '1Sam' => array('cs' => '1 Sam', 'sk' => '1 Sam', 'pl' => '1 Sam', 'en' => '1 Sm', 'hu' => '1Sám', 'ru' => '1 Цар', 'uk' => '1 Сам', 'id' => '09'), // 1. Samuelova
  '2Sam' => array('cs' => '2 Sam', 'sk' => '2 Sam', 'pl' => '2 Sam', 'en' => '2 Sm', 'hu' => '2Sám', 'ru' => '2 Цар', 'uk' => '2 Сам', 'id' => '10'), // 2. Samuelova
  '1Kral' => array('cs' => '1 Král', 'sk' => '1 Kr', 'pl' => '1 Krl', 'en' => '1 Kgs', 'hu' => '1Kir', 'ru' => '3 Цар', 'uk' => '1 Цар', 'id' => '11'), // 1. Královská
  '2Kral' => array('cs' => '2 Král', 'sk' => '2 Kr', 'pl' => '2 Krl', 'en' => '2 Kgs', 'hu' => '2Kir', 'ru' => '4 Цар', 'uk' => '2 Цар', 'id' => '12'), // 2. Královská
  '1Kron' => array('cs' => '1 Kron', 'sk' => '1 Krn', 'pl' => '1 Krn', 'en' => '1 Chr', 'hu' => '1Krón', 'ru' => '1 Пар', 'uk' => '1 Хр', 'id' => '13'), // 1. Paralipomenon
  '2Kron' => array('cs' => '2 Kron', 'sk' => '1 Krn', 'pl' => '2 Krn', 'en' => '2 Chr', 'hu' => '2Krón', 'ru' => '2 Пар', 'uk' => '2 Хр', 'id' => '14'), // 2. Paralipomenon
  'Ez' => array('cs' => 'Ezd', 'sk' => 'Ezd', 'pl' => 'Ezd', 'en' => 'Ezr', 'hu' => 'Ezd', 'ru' => 'Езд', 'uk' => 'Езр', 'id' => '15'), // Ezdráš
  'Neh' => array('cs' => 'Neh', 'sk' => 'Neh', 'pl' => 'Ne', 'en' => 'Neh', 'hu' => 'Neh', 'ru' => 'Неем', 'uk' => 'Неєм', 'id' => '16'), // Nehemiáš
  'Tob' => array('cs' => 'Tob', 'sk' => 'Tob', 'pl' => 'Tb', 'en' => 'Tb', 'hu' => 'Tób', 'ru' => 'Тов', 'uk' => 'Тов', 'id' => '90'), //
  'Jdt' => array('cs' => 'Jdt', 'sk' => 'Jdt', 'pl' => 'Jdt', 'en' => 'Jdt', 'hu' => 'Jud', 'ru' => 'Иф', 'uk' => 'Юдіт', 'id' => '91'), // Júdit, ???????????
  'Est' => array('cs' => 'Est', 'sk' => 'Est', 'pl' => 'Est', 'en' => 'Est', 'hu' => 'Eszt', 'ru' => 'Есф', 'uk' => 'Ест', 'id' => '17'), // Ester, ???????????
  '1Mak' => array('cs' => '1 Mak', 'sk' => '1 Mach', 'pl' => '1 Mch', 'en' => '1 Mc', 'hu' => '1Mak', 'ru' => '1 Макк', 'uk' => '1 Мак', 'id' => '92'), //
  '2Mak' => array('cs' => '2 Mak', 'sk' => '2 Mach', 'pl' => '2 Mch', 'en' => '2 Mc', 'hu' => '2Mak', 'ru' => '2 Макк', 'uk' => '2 Мак', 'id' => '93'), //
  'Job' => array('cs' => 'Job', 'sk' => 'Jób', 'pl' => 'Hi', 'en' => 'Jb', 'hu' => 'Jób', 'ru' => 'Иов', 'uk' => 'Іов', 'id' => '18'), // Jób
  'Zl' => array('cs' => 'Žl', 'sk' => 'Ž', 'pl' => 'Ps', 'en' => 'Ps', 'hu' => 'Zs', 'ru' => 'Пс', 'uk' => 'Пс', 'id' => '19'), // Žalmy
  'Pr' => array('cs' => 'Př', 'sk' => 'Prís', 'pl' => 'Prz', 'en' => 'Prv', 'hu' => 'Péld', 'ru' => 'Притч', 'uk' => 'Пр', 'id' => '20'), // Přísloví
  'Kaz' => array('cs' => 'Kaz', 'sk' => 'Kaz', 'pl' => 'Koh', 'en' => 'Eccl', 'hu' => 'Préd', 'ru' => 'Екк', 'uk' => 'Пtit', 'id' => '21'), // Kazatel,  ?????????
  'Pís' => array('cs' => 'Pís', 'sk' => 'Pies', 'pl' => 'Pnp', 'en' => 'Sg', 'hu' => 'Én', 'ru' => 'Песн', 'uk' => 'Пп', 'id' => '22'), // Písen Šalomounova
  'Pis' => array('cs' => 'Pís', 'sk' => 'Pies', 'pl' => 'Pnp', 'en' => 'Sg', 'hu' => 'Én', 'ru' => 'Песн', 'uk' => 'Пп', 'id' => '22'), // Písen Šalomounova
  'Mdr' => array('cs' => 'Mdr', 'sk' => 'Múd', 'pl' => 'Mdr', 'en' => 'Wis', 'hu' => 'Bölcs', 'ru' => 'Прем', 'uk' => 'Мудр', 'id' => '94'), //
  'Sir' => array('cs' => 'Sir', 'sk' => 'Sir', 'pl' => 'Syr', 'en' => 'Sir', 'hu' => 'Sir', 'ru' => 'Сир', 'uk' => 'Сир', 'id' => '95'), //
  'Iz' => array('cs' => 'Iz', 'sk' => 'Iz', 'pl' => 'Iz', 'en' => 'Is', 'hu' => 'Iz', 'ru' => 'Ис', 'uk' => 'Іс', 'id' => '23'), // Izajáš
  'Jer' => array('cs' => 'Jer', 'sk' => 'Jer', 'pl' => 'Jr', 'en' => 'Jer', 'hu' => 'Jer', 'ru' => 'Иер', 'uk' => 'Єр', 'id' => '24'), // Jeremjáš
  'PL' => array('cs' => 'Pláč', 'sk' => 'Nar', 'pl' => 'Lm', 'en' => 'Lam', 'hu' => 'Siral', 'ru' => 'Плач', 'uk' => 'Плач', 'id' => '25'), // Plác Jeremjášuv ?????????
  'Bar' => array('cs' => 'Bar', 'sk' => 'Bar', 'pl' => 'Ba', 'en' => 'Bar', 'hu' => 'Bár', 'ru' => 'Вар', 'uk' => 'Вар', 'id' => '96'), //
  'Ez' => array('cs' => 'Ez', 'sk' => 'Ez', 'pl' => 'Ez', 'en' => 'Ez', 'hu' => 'Ez', 'ru' => 'Иез', 'uk' => 'Єз', 'id' => '26'), // Ezechiel
  'Dan' => array('cs' => 'Dan', 'sk' => 'Dan', 'pl' => 'Dn', 'en' => 'Dn', 'hu' => 'Dán', 'ru' => 'Дан', 'uk' => 'Дан', 'ru' => 'Дан', 'uk' => 'Дан', 'id' => '27'), // Daniel
  'Oz' => array('cs' => 'Oz', 'sk' => 'Oz', 'pl' => 'Oz', 'en' => 'Hos', 'hu' => 'Oz', 'ru' => 'Ос', 'uk' => 'Ос', 'id' => '28'), // Ozeáš
  'Jl' => array('cs' => 'Jl', 'sk' => 'Joel', 'pl' => 'Jl', 'en' => 'Jl', 'hu' => 'Jo', 'ru' => 'Иоил', 'uk' => 'Йоіл', 'id' => '29'), // Jóel
  'Am' => array('cs' => 'Am', 'sk' => 'Am', 'pl' => 'Am', 'en' => 'Am', 'hu' => 'Ám', 'ru' => 'Ам', 'uk' => 'Ам', 'id' => '30'), // Ámos
  'Abd' => array('cs' => 'Abd', 'sk' => 'Abd', 'pl' => 'Ab', 'en' => 'Ob', 'hu' => 'Abd', 'ru' => 'Авд', 'uk' => 'Овд', 'id' => '31'), // Abdijáš, ???????????
  'Jon' => array('cs' => 'Jon', 'sk' => 'Jon', 'pl' => 'Jon', 'en' => 'Jon', 'hu' => 'Jón', 'ru' => 'Ион', 'uk' => 'Йоіл', 'id' => '32'), // Jonáš
  'Mich' => array('cs' => 'Mich', 'sk' => 'Mich', 'pl' => 'Mi', 'en' => 'Mi', 'hu' => 'Mik', 'ru' => 'Мих', 'uk' => 'Міх', 'id' => '33'), // Micheáš
  'Na' => array('cs' => 'Nah', 'sk' => 'Nah', 'pl' => 'Na', 'en' => 'Na', 'hu' => 'Náh', 'ru' => 'Наум', 'uk' => 'Наум', 'id' => '34'), // Nahum,  ?????????????
  'Hab' => array('cs' => 'Hab', 'sk' => 'Hab', 'pl' => 'Ha', 'en' => 'Hb', 'hu' => 'Hab', 'ru' => 'Авв', 'uk' => 'Авв', 'id' => '35'), // Habakuk
  'Abk' => array('cs' => 'Hab', 'sk' => 'Hab', 'pl' => 'Ha', 'en' => 'Hb', 'hu' => 'Hab', 'ru' => 'Авв', 'uk' => 'Авв', 'id' => '35'), // Abakuk, ????????
  'Sof' => array('cs' => 'Sof', 'sk' => 'Sof', 'pl' => 'So', 'en' => 'Zep', 'hu' => 'Szof', 'ru' => 'Соф', 'uk' => 'Соф', 'id' => '36'), // Sofonjáš
  'Ag' => array('cs' => 'Ag', 'sk' => 'Ag', 'pl' => 'Ag', 'en' => 'Hg', 'hu' => 'Ag', 'ru' => 'Агг', 'uk' => 'Аг', 'id' => '37'), // Ageus, ??????????
  'Zach' => array('cs' => 'Zach', 'sk' => 'Zach', 'pl' => 'Za', 'en' => 'Zec', 'hu' => 'Zak', 'ru' => 'Зах', 'uk' => 'Зах', 'id' => '38'), // Zacharjáš
  'Mal' => array('cs' => 'Mal', 'sk' => 'Mal', 'pl' => 'Ml', 'en' => 'Mal', 'hu' => 'Mal', 'ru' => 'Мал', 'uk' => 'Мал', 'id' => '39'), // Malachiáš
  'Mt' => array('cs' => 'Mt', 'sk' => 'Mt', 'pl' => 'Mt', 'en' => 'Mt', 'hu' => 'Mt', 'ru' => 'Мф', 'uk' => 'Мт', 'id' => '40'), // Matouš
  'Mk' => array('cs' => 'Mk', 'sk' => 'Mk', 'pl' => 'Mk', 'en' => 'Mk', 'hu' => 'Mk', 'ru' => 'Мк', 'uk' => 'Мк', 'id' => '41'), // Marek
  'Lk' => array('cs' => 'Lk', 'sk' => 'Lk', 'pl' => 'Łk', 'en' => 'Lk', 'hu' => 'Lk', 'ru' => 'Лк', 'uk' => 'Лк', 'id' => '42'), // Lukáš
  'Jan' => array('cs' => 'Jan', 'sk' => 'Jn', 'pl' => 'J', 'en' => 'Jn', 'hu' => 'Jn', 'ru' => 'Ин', 'uk' => 'Йн', 'id' => '43'), // Jan
  'Sk' => array('cs' => 'Sk', 'sk' => 'Sk', 'pl' => 'Dz', 'en' => 'Acts', 'hu' => 'ApCsel', 'ru' => 'Деян', 'uk' => 'Діян', 'id' => '44'), // Skutky apoštolů
  'Rim' => array('cs' => 'Řím', 'sk' => 'Rim', 'pl' => 'Rz', 'en' => 'Rom', 'hu' => 'Róm', 'ru' => 'Рим', 'uk' => 'Рим', 'id' => '45'), // Římanům
  '1Kor' => array('cs' => '1 Kor', 'sk' => '1 Kor', 'pl' => '1 Kor', 'en' => '1 Cor', 'hu' => '1Kor', 'ru' => '1 Кор', 'uk' => '1 Кор', 'id' => '46'), // 1. Korintským
  '2Kor' => array('cs' => '2 Kor', 'sk' => '2 Kor', 'pl' => '2 Kor', 'en' => '2 Cor', 'hu' => '2Kor', 'ru' => '2 Кор', 'uk' => '2 Кор', 'id' => '47'), // 2. Korintským
  'Gal' => array('cs' => 'Gal', 'sk' => 'Gal', 'pl' => 'Ga', 'en' => 'Gal', 'hu' => 'Gal', 'ru' => 'Гал', 'uk' => 'Гал', 'id' => '48'), // Galatským
  'Ef' => array('cs' => 'Ef', 'sk' => 'Ef', 'pl' => 'Ef', 'en' => 'Eph', 'hu' => 'Ef', 'ru' => 'Еф', 'uk' => 'Єр', 'id' => '49'), // Efezským
  'Flp' => array('cs' => 'Flp', 'sk' => 'Flp', 'pl' => 'Flp', 'en' => 'Phil', 'hu' => 'Fil', 'ru' => 'Флп', 'uk' => 'Флп', 'id' => '50'), // Filipským
  'Kol' => array('cs' => 'Kol', 'sk' => 'Kol', 'pl' => 'Kol', 'en' => 'Col', 'hu' => 'Kol', 'ru' => 'Кол', 'uk' => 'Кол', 'id' => '51'), // Koloským
  '1Sol' => array('cs' => '1 Sol', 'sk' => '1 Sol', 'pl' => '1 Tes', 'en' => '1 Thes', 'hu' => '1Tessz', 'ru' => '1 Фес', 'uk' => '1 Сол', 'id' => '52'), // 1. Tesalonickým
  '2Sol' => array('cs' => '2 Sol', 'sk' => '2 Sol', 'pl' => '2 Tes', 'en' => '2 Thes', 'hu' => '1Tessz', 'ru' => '2 Фес', 'uk' => '2 Сол', 'id' => '53'), // 2. Tesalonickým
  '1Tim' => array('cs' => '1 Tim', 'sk' => '1 Tim', 'pl' => '1 Tm', 'en' => '1 Tm', 'hu' => '1Tim', 'ru' => '1 Тим', 'uk' => '1 Тим', 'id' => '54'), // 1. Timoteovi
  '2Tim' => array('cs' => '2 Tim', 'sk' => '2 Tim', 'pl' => '2 Tm', 'en' => '2 Tm', 'hu' => '2Tim', 'ru' => '2 Тим', 'uk' => '2 Тим', 'id' => '55'), // 2. Timoteovi
  'Tit' => array('cs' => 'Tit', 'sk' => 'Tit', 'pl' => 'Tt', 'en' => 'Ti', 'hu' => 'Tit', 'ru' => 'Тит', 'uk' => 'Тит', 'id' => '56'), // Titovi
  'Fil' => array('cs' => 'Flm', 'sk' => 'Flm', 'pl' => 'Flm', 'en' => 'Phlm', 'hu' => 'Filem', 'ru' => 'Флм', 'uk' => 'Флм', 'id' => '57'), // Filemonovi, ??????????
  'Fm' => array('cs' => 'Flm', 'sk' => 'Flm', 'pl' => 'Flm', 'en' => 'Phlm', 'hu' => 'Filem', 'ru' => 'Флм', 'uk' => 'Флм', 'id' => '57'), // Filemonovi, ??????????
  'Flm' => array('cs' => 'Flm', 'sk' => 'Flm', 'pl' => 'Flm', 'en' => 'Phlm', 'hu' => 'Filem', 'ru' => 'Флм', 'uk' => 'Флм', 'id' => '57'), // Filemonovi, ??????????
  'Zid' => array('cs' => 'Žid', 'sk' => 'Hebr', 'pl' => 'Hbr', 'en' => 'Heb', 'hu' => 'Zsid', 'ru' => 'Евр', 'uk' => 'Євр', 'id' => '58'), // Židům
  'Zd' => array('cs' => 'Žid', 'sk' => 'Hebr', 'pl' => 'Hbr', 'en' => 'Heb', 'hu' => 'Zsid', 'ru' => 'Евр', 'uk' => 'Євр', 'id' => '58'), // Židům
  'Jak' => array('cs' => 'Jak', 'sk' => 'Jak', 'pl' => 'Jk', 'en' => 'Jas', 'hu' => 'Jak', 'ru' => 'Откр', 'uk' => 'Як', 'id' => '59'), // Jakub
  '1Pt' => array('cs' => '1 Petr', 'sk' => '1 Pt', 'pl' => '1 P', 'en' => '1 Peter', 'hu' => '1Pét', 'ru' => '1 Петр', 'uk' => '1 Пт', 'id' => '60'), // 1. Petr
  '1Petr' => array('cs' => '1 Petr', 'sk' => '1 Pt', 'pl' => '1 P', 'en' => '1 Peter', 'hu' => '1Pét', 'ru' => '1 Петр', 'uk' => '1 Пт', 'id' => '60'), // 1. Petr
  '2Pt' => array('cs' => '2 Petr', 'sk' => '2 Pt', 'pl' => '1 P', 'en' => '2 Peter', 'hu' => '2Pét', 'ru' => '2 Петр', 'uk' => '2 Пт', 'id' => '61'), // 2. Petr
  '2Petr' => array('cs' => '2 Petr', 'sk' => '2 Pt', 'pl' => '1 P', 'en' => '2 Peter', 'hu' => '2Pét', 'ru' => '2 Петр', 'uk' => '2 Пт', 'id' => '61'), // 2. Petr
  '1Jan' => array('cs' => '1 Jan', 'sk' => '1 Jn', 'pl' => '1 J', 'en' => '1 John', 'hu' => '1Jn', 'ru' => '1 Ин', 'uk' => '1 Йн', 'id' => '62'), // 1. Jan
  '2Jan' => array('cs' => '2 Jan', 'sk' => '2 Jn', 'pl' => '2 J', 'en' => '2 John', 'hu' => '2Jn', 'ru' => '2 Ин', 'uk' => '2 Йн', 'id' => '63'), // 2. Jan
  '3Jan' => array('cs' => '3 Jan', 'sk' => '3 Jn', 'pl' => '2 J', 'en' => '3 John', 'hu' => '3Jn', 'ru' => '3 Ин', 'uk' => '3 Йн', 'id' => '64'), // 3. Jan
  'Jud' => array('cs' => 'Jud', 'sk' => 'Júd', 'pl' => 'Jud', 'en' => 'Jude', 'hu' => 'Júdás', 'ru' => 'Иуд', 'uk' => 'Юда', 'id' => '65'), // Juda, ?????????
  'Jud' => array('cs' => 'Jud', 'sk' => 'Júd', 'pl' => 'Jud', 'en' => 'Jude', 'hu' => 'Júdás', 'ru' => 'Иуд', 'uk' => 'Юда', 'id' => '65'), // Juda, ?????????
  'Zj' => array('cs' => 'Zj', 'sk' => 'Zjv', 'pl' => 'Ap', 'en' => 'Rv', 'hu' => 'Jel', 'ru' => 'Откр', 'uk' => 'Од', 'id' => '66') // Zjevení
);

function _trans($textid) {
  global $langVar;
  return ($langVar[$textid]);
}

function translateVerse($verse) {
  global $kapky_verses, $lang;
  //$verse = html_entity_decode(iconv('CP1250', 'UTF-8', $verse), null, 'utf-8');
  $verse = html_entity_decode($verse, null, 'utf-8');
  $verse = preg_replace('/([a-z0-9])-([a-z0-9])/i', '$1 - $2', $verse);

  $pattern = '~^(?P<xx>[0-9]*[a-zA-Zěščřžýáíéúůť]+)(?P<yyy>.*)~';

  $matches = array();
  preg_match($pattern, $verse, $matches);
  if (array_key_exists("xx", $matches) && array_key_exists($matches["xx"], $kapky_verses)) {
    $xx = $kapky_verses[$matches["xx"]][$lang];
    return $xx.$matches["yyy"];
  }
  return $verse;
}

function transformVerse($verse) {
  global $kapky_verses;
  $bezmezer = preg_replace('/\s/','',$verse);
  $pattern = '~^(?P<xx>[0-9]*[a-zA-Z]+)(?P<yyy>[0-9]*),?(?P<zzz>[0-9]*-?[0-9]*)~';

  $matches = array();
  preg_match($pattern, $bezmezer, $matches);
  if (array_key_exists($matches["xx"], $kapky_verses)) {
    $xx = $kapky_verses[$matches["xx"]]['id'];
    $yyy = $matches["yyy"];
    if (strlen($yyy)<4) {
      for($i=0; $i<(3-strlen($matches["yyy"])); $i++) {
        $yyy = '0'.$yyy;
      }
      $zzz = '000';
      return $xx.$yyy.$zzz;
    }
  }
  return $verse;
}

function bible_href($verse) {
  return "http://www.bibleserver.com/go.php?lang=cz&amp;bible=CEP&amp;ref=".transformVerse($verse);
}


function cesky_den($den) {
	
	if(__LANG__=='cz')
	{
         $nazvy = array('neděli', 'pondělí', 'úterý', 'středu', 'čtvrtek', 'pátek', 'sobotu');
    }
    elseif(__LANG__=='sk')
    {
		 $nazvy = array('nedeľu', 'pondelok', 'utorok', 'stredu', 'štvrtok', 'piatok', 'sobotu');
	}
    return $nazvy[$den];
}

?>
