<?
// uvod

$query_nadpis = MySQLi_Query($spojeni,"SELECT * FROM stranky WHERE str='uvod' AND lang='".__LANG__."'") or die(err(1));
$row_nadpis = MySQLi_fetch_object($query_nadpis);

echo "<section class='hero post__image-container'>

<div class='container'>

<h1>

<b>";

if(__LANG__=='sk')
{
	echo __TEXT_1_UVOD_SK__;
}
else
{
	echo __TEXT_1_UVOD_CZ__;
}

echo "</b>";

if(__LANG__=='sk')
{
	echo __TEXT_2_UVOD_SK__;
}
else
{
	echo __TEXT_2_UVOD_CZ__;
}

echo "</h1>

<p>";

if(__LANG__=='sk')
{
	echo __TEXT_3_UVOD_SK__;
}
else
{
	echo __TEXT_3_UVOD_CZ__;
}

echo "</p>

<a class='btn btn-white' href='/".__LANG__."/registrace.html'>

".__VYTVORIT_UCET__."

</a>

<a class='btn btn-ghost-white' href='/".__LANG__."/texty.html'>

".__CIST_TEXTY__."

</a>

</div>";	


if($row_nadpis->pozadi)
{
	$pozadi = "/prilohy/".$row_nadpis->pozadi;
}
else
{
	$pozadi = "/img/hero-bg.jpg";
}

echo "<img alt='Homiletický web pro kněze' class='post__featured-image' data-src='".$pozadi."' src='/img/lazy/lazy-hero-bg.jpg'>

</section>";




echo "<a class='hero-scroll' href='#intro-text'>

<img alt='Posunout dolů' src='/images/hero-scroll.svg'>

</a>

<section class='intro-text' id='intro-text'>

<div class='container'>

<div class='row'>

<div class='col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6'>

<figure class='intro-img'>

<img alt='Homiletický web pro kněze' data-src='/img/intro-img.jpg' src='/img/lazy/lazy-intro-img.jpg' title='Homiletický web pro kněze'>

</figure>

</div>

<div class='col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6'>

<div class='intro-text'>

<h3>

".stripslashes($row_nadpis->podnadpis)."

</h3>

<p>

".stripslashes($row_nadpis->obsah)."

</p>

<a class='btn' href='/".__LANG__."/registrace.html'>

".__VYTVORIT_UCET__."

</a>

<a class='btn btn-ghost' href='/".__LANG__."/texty.html'>

".__CIST_TEXTY__."

</a>

</div>

</div>

</div>

</div>

</section>";




include('./skripty/simple_html_dom.php');
include('./skripty/mapovani.php');
include('./skripty/cirkevni_kalendar2.php');

$lang = strip_tags($_GET['lang']);

if(!$lang){$lang = 'cs';}

/*
$langVar["2day_1"]="neděli";
$langVar["2day_2"]="pondělí";
$langVar["2day_3"]="úterý";
$langVar["2day_4"]="středu";
$langVar["2day_5"]="čtvrtek";
$langVar["2day_6"]="pátek";
$langVar["2day_7"]="sobotu";
*/


$tyden = new cirkevniTyden();
 
$day = date('w');
$homtyden = array();
$time = time();

echo '<section class="dailyWord">
	<div class="container">
		<h1 class="dailyWord__title">'.__SLOVO_NA__DEN__.'</h1>
		<div class="swiffy-slider slider-item-show3 slider-nav-arrow slider-nav-outside-expand slider-item-reveal slider-item-nosnap">
    <div id="heroControl"><button type="button" class="slider-nav" aria-label="Previous"></button><button type="button" class="slider-nav slider-nav-next" aria-label="Next"></button></div>
    <div class="slider-container">
    
    ';
		
		
for ($i=0;$i<7;$i++) {
  $rec = array();
  $den = $tyden->getCirkevniDenByIndex($i);
  $rec['cteni1'] = translateVerse($den->cteni1);
  if ($den->cteni1link==1) {
    if ($lang=='cs') $rec['cteni1_link'] = $den->biblenet($den->cteni1);
    else $rec['cteni1_link'] = bible_href($den->cteni1);
  }
  $rec['cteni2'] = translateVerse($den->cteni2);
  if (!empty($rec['cteni2'])) {
    if ($lang=='cs') $rec['cteni2_link'] = $den->biblenet($den->cteni2);
    else $rec['cteni2_link'] = bible_href($den->cteni2);
  }
  $rec['zalm'] = translateVerse($den->zalm);
  if ($lang=='cs') $rec['zalm_link'] = $den->biblenet($den->zalm);
  else $rec['zalm_link'] = bible_href($den->zalm);
  $rec['evangelium'] = translateVerse($den->evangelium);
  if ($lang=='cs') $rec['evangelium_link'] = $den->biblenet($den->evangelium);
  else $rec['evangelium_link'] = bible_href($den->evangelium);
  $rec['nazevdne'] = _trans('2day_'.((($i+$day) % 7)+1));
  
  
  $denTime = $time + ($i*24*60*60);
  $klic = Date("j_n_Y", $denTime);
  $catalogflt = '';
  $slavnost = html_entity_decode($den->cirkevniKal, null, 'utf-8');
  if (substr($klic, 0, 6)=='26_11_' && $slavnost=='sv. Silvestr') {
    $den->cirkevniKal='sv. Silvestr Guzzolini';
  }
  else if (substr($klic, 0, 5)=='16_8_' && $slavnost=='sv. Štěpán') {
    $den->cirkevniKal=html_entity_decode('sv. Štěpán 2', null, 'utf-8');
  }
  
  $den->info4();	
  
  echo '<div class="dailyWord__wrapHom">';
  
 if ($tyden->mapEvangelia == null) $tyden->loadVazby($spojeni);  //načtení překladů z názvů evangelií či svátků na kategorie homílií
  
  $typ = 0; 	
  $ca_ids = $den->getCategory($tyden, $typ);
  $cteni_ca_ids = $den->getCategoryCteni($tyden);
  
  
   
  if($cteni_ca_ids[0] && !$typ)
  {
	  echo '<a class="dailyWord__linkHom" href="/'.__LANG__.''.$cteni_ca_ids[0].'">
							<img src="/img/icon.jpg" alt="Homilie k 1. čtení">
							<span>'.__HOMILIE__.' k 1. '.__CTENI2__.' na '.cesky_den(date("w",$denTime)).'</span>
						</a>'; 
  }
  
  
  if($ca_ids[0])
  {
	  
	 echo '<a class="dailyWord__linkHom" href="/'.__LANG__.'/'.$ca_ids[0].'">
							<img src="/img/icon.jpg" alt="Zobrazit homilii k 1. čtení">
							<span>'.__HOMILIE__.' '.($typ ? ' ' : 'k '.__EVANGELIU__.' ').' na '.cesky_den(date("w",$denTime)).' </span>
						</a>'; 
  }
  
 
						
						 
			echo '</div>

				</div>
   
			</div>';
  
    

}
		

			



		echo '</div></div>
	</div>
</section>';




// homilie uvod
echo "<section class='homilie'>

<div class='container'>

<div class='section-heading'>

<h2>

Homílie

</h2>

<img alt='Homílie' src='/images/separator-tome.svg'>

</div>

<div class='row'>";

$str = "str_".__LANG__;
$nazev = "nazev_".__LANG__;
$aktivni = "aktivni_".__LANG__;

$query_hom_uvod = MySQLi_Query($spojeni,"SELECT id, $str, $nazev, ico FROM homilie WHERE $aktivni=1 AND vnor=1 ORDER BY nazev_cz, id") or die(err(1));
while($row_hom_uvod = MySQLi_fetch_object($query_hom_uvod))
{
  
  echo "<div class='col-6 col-xs-6 col-sm-6 col-md-4 col-lg-3'>

	<a class='homilie-single' href='/".__LANG__."/homilie/".$row_hom_uvod->$str."/".$row_hom_uvod->id.".html'>
	
	<img alt='".stripslashes($row_hom_uvod->$nazev)."' src='/img/".$row_hom_uvod->ico."'  title='".stripslashes($row_hom_uvod->$nazev)."'>
	
	<p>
	
	".stripslashes($row_hom_uvod->$nazev)."
	
	</p>
	
	</a>
	
	</div>";
  	
}







echo "</div>

<div class='read-more-wrap'>

<a class='btn' id='homilie-toggler'>

".__ZOBRAZIT_DALSI_HOMILIE__."

</a>

</div>

</div>

</section>";



?>
