<?
// novinky

if($_GET['idn'])
{
	// detail
	$query_novinky = MySQLi_Query($spojeni,"SELECT * FROM aktuality WHERE aktivni=1 AND id='".intval($_GET['idn'])."' ") or die(err(1));
    $row_novinky = MySQLi_fetch_object($query_novinky);
	
	echo "<div class='page-heading-small'></div>
<main>
<div class='container'>
<article class='post'>
<div class='novinky-header'>
<h1>
".stripslashes($row_novinky->nadpis)."
</h1>
<span class='text-muted'>
<i class='fa fa-calendar'></i>
".date("d. m. Y",$row_novinky->datum)."
</span>
</div>
<p>
".stripslashes($row_novinky->perex)."
</p>
<p>
".stripslashes($row_novinky->text)."
</p>
<div class='article-bottom'>
<a class='btn btn-small' href='/".__LANG__."/novinky.html'>
<i class='fa fa-reply'></i>
".__ZPET_NA_VYPIS_AKTUALIT__."
</a>
</div>
</article>
</div>
</main>";
}
else
{
echo "<section class='page-heading post__image-container'>
<div class='container'>
<h1>
Novinky
</h1>
</div>
<img alt='Novinky' class='post__featured-image' data-src='/img/novinky-header.jpg' src='/img/lazy/lazy-novinky-header.jpg'>
<section class='breadcrumb-wrap'>
<div class='container'>";

	drobinka($p,$menu_all,$spojeni);

echo "</div>
</section>
</section>
<main>
<section class='news'>
<div class='container'>
<div class='row'>";
	
	novinky_zbytek($spojeni);
	
	
//echo "</div>";


echo "</div>
</section>
</main>";

	
}

?>
