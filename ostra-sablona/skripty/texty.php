<?
// texty

$nazev = "nazev_".__LANG__;
$obsah = "obsah_".__LANG__;
$str = "str_".__LANG__;
$aktivni = "aktivni_".__LANG__;	
	
$autor = "autor_".__LANG__;	
$prelozil = "prelozil_".__LANG__;	
$zdroj = "zdroj_".__LANG__;

if(__LANG__=='sk')
{
    $prelozil_nazev = "preklad";
}
else
{
	$prelozil_nazev = "Český překlad";
}	
	
	
if($_GET['idt'])
{
	
	
	$query_tx = MySQLi_Query($spojeni,"SELECT *  FROM texty WHERE $aktivni=1 AND id='".intval($_GET['idt'])."' ") or die(err(1));
	$row_tx = MySQLi_fetch_object($query_tx);

	if($row_tx->$obsah)
	{
		// textova konecna kategorie
		
		if($row_tx->pozadi)
		{
			$pozadi = "/prilohy/".$row_tx->pozadi;
		}
		else
		{
			$pozadi = "/img/texty-header.jpg";
		}
		
		echo "<section class='page-heading page-heading-post post__image-container'>
				<div class='container'>
				<span>";
				//echo __NAPSAL__.": ".stripslashes($row_tx->autor);
				echo "</span>
				<h1>
				".stripslashes($row_tx->$nazev)."
				</h1>
				</div>
				<img alt='".stripslashes($row_tx->$nazev)."' class='post__featured-image' data-src='".$pozadi."' src='/img/lazy/lazy-texty-header.jpg'>
				<section class='breadcrumb-wrap'>
				<div class='container'>";
				
				drobinka($p,$menu_all,$spojeni);
				
				
				echo "</div>
				</section>
				</section>
				<main>
				<div class='container'>
				<article class='post'>";
				
				  echo "<div class='article-credit'>
				<span>";
					
					if($row_tx->$autor)
					{
					  echo "Autor: <b> ".stripslashes($row_tx->$autor)."</b>";	
					}
					
					
					echo "</span>
					<span>";
					
					if($row_tx->$zdroj)
					{
						echo "Zdroj: <b>".stripslashes($row_tx->$zdroj)."</b>";
						
					}
					
					
					echo "</span>
					<span>";
					
					if($row_tx->$prelozil)
					{
					  echo $prelozil_nazev.": <b> ".stripslashes($row_tx->$prelozil)."</b>";	
					}
					
					
					echo "</span>";
					
					if($row_tx->typ2==1)
					{
					 
					 echo "<span>
					Datum napsání:
					<b>
					".date("d.m.Y",$row_tx->datum)."
					</b>
					</span>";	
						
					}
					
					
					
					echo "</div>";
				
				echo "<div class='print-area'>";
				
				echo stripslashes($row_tx->$obsah);
				
				echo "</div>
<div class='article-bottom'>
<a class='btn btn-small' href='/".__LANG__."/texty.html'>
<i class='fa fa-reply'></i>
".__ZPET_NA_VYPIS__."
</a>
<a class='btn btn-ghost btn-small float-right' href='' onclick='window.print();'>
<i class='fa fa-print'></i>
Tisk
</a>
</div>
</article>
</div>
</main>";
		
	}
	else
	{
		if($row_tx->pozadi)
		{
			$pozadi = "/prilohy/".$row_tx->pozadi;
		}
		else
		{
			$pozadi = "/img/texty-header.jpg";
		}
		
		// vypis podkategorii
		echo "<section class='page-heading post__image-container'>
		<div class='container'>
		<h1>
		Texty
		</h1>
		</div>
		<img alt='Texty' class='post__featured-image' data-src='".$pozadi."' src='/img/lazy/lazy-texty-header.jpg'>
		<section class='breadcrumb-wrap'>
		<div class='container'>";
		
			drobinka($p,$menu_all,$spojeni);
		
		echo "</div>
		</section>
		</section>
		<main>";
		
		
		echo "<section class='homilie'>
		<div class='container'>
		<div class='row'>";
			
			//  vypis

			
			$query_t = MySQLi_Query($spojeni,"SELECT id, $nazev, $str, vnor, id_nadrazeneho FROM texty WHERE $aktivni=1 AND id_nadrazeneho='".intval($_GET['idt'])."'  ORDER BY nazev_cz, id") or die(err(1));
		    while($row_t = MySQLi_fetch_object($query_t))
		    {
				
				if($row_t->vnor==4)
				{
					$n2 = MySQLi_Query($spojeni,"SELECT id, $str, id_nadrazeneho FROM texty WHERE id='".$row_t->id_nadrazeneho."' ");
					$row_n2 = MySQLi_fetch_object($n2);
					
					$n3 = MySQLi_Query($spojeni,"SELECT id, $str, id_nadrazeneho FROM texty WHERE id='".$row_n2->id_nadrazeneho."' ");
					$row_n3 = MySQLi_fetch_object($n3);
					
					$n4 = MySQLi_Query($spojeni,"SELECT id, $str, id_nadrazeneho FROM texty WHERE id='".$row_n3->id_nadrazeneho."' ");
					$row_n4 = MySQLi_fetch_object($n4);
					
					echo "<div class='col-6 col-xs-6 col-sm-6 col-md-4 col-lg-3'>
						<a class='homilie-single' href='/".__LANG__."/texty/".$row_n4->$str."/".$row_n3->$str."/".$row_n2->$str."/".$row_t->$str."/".$row_t->id.".html'>
						<img alt='".stripslashes($row_t->$nazev)."' data-src='/img/texty-single.jpg' src='/img/lazy/lazy-texty-single.jpg' title='".stripslashes($row_t->$nazev)."'>
						<p>
						".stripslashes($row_t->$nazev)."
						</p>
						</a>
					  </div>";
						
				}
				if($row_t->vnor==3)
				{
					$n2 = MySQLi_Query($spojeni,"SELECT id, $str, id_nadrazeneho FROM texty WHERE id='".$row_t->id_nadrazeneho."' ");
					$row_n2 = MySQLi_fetch_object($n2);
					
					$n3 = MySQLi_Query($spojeni,"SELECT id, $str, id_nadrazeneho FROM texty WHERE id='".$row_n2->id_nadrazeneho."' ");
					$row_n3 = MySQLi_fetch_object($n3);
					
					echo "<div class='col-6 col-xs-6 col-sm-6 col-md-4 col-lg-3'>
						<a class='homilie-single' href='/".__LANG__."/texty/".$row_n3->$str."/".$row_n2->$str."/".$row_t->$str."/".$row_t->id.".html'>
						<img alt='".stripslashes($row_t->$nazev)."' data-src='/img/texty-single.jpg' src='/img/lazy/lazy-texty-single.jpg' title='".stripslashes($row_t->$nazev)."'>
						<p>
						".stripslashes($row_t->$nazev)."
						</p>
						</a>
					  </div>";
						
				}
				elseif($row_t->vnor==2)
				{
				   
				   echo "<div class='col-6 col-xs-6 col-sm-6 col-md-4 col-lg-3'>
						<a class='homilie-single' href='/".__LANG__."/texty/".$row_tx->$str."/".$row_t->$str."/".$row_t->id.".html'>
						<img alt='".stripslashes($row_t->$nazev)."' data-src='/img/texty-single.jpg' src='/img/lazy/lazy-texty-single.jpg' title='".stripslashes($row_t->$nazev)."'>
						<p>
						".stripslashes($row_t->$nazev)."
						</p>
						</a>
					  </div>";
				   	
				}
				
				
				
			}
			
			echo "</div>
		</div>
		</section>
		</main>";
		
	}
	
	
}
else
{
	
	echo "<section class='page-heading post__image-container'>
<div class='container'>
<h1>
Texty
</h1>
</div>
<img alt='Texty' class='post__featured-image' data-src='/img/texty-header.jpg' src='/img/lazy/lazy-texty-header.jpg'>
<section class='breadcrumb-wrap'>
<div class='container'>";

	drobinka($p,$menu_all,$spojeni);

echo "</div>
</section>
</section>
<main>";


echo "<section class='homilie'>
<div class='container'>
<div class='row'>";
	
	// zakladni vypis

	
	$query_t = MySQLi_Query($spojeni,"SELECT id, $nazev, $str FROM texty WHERE $aktivni=1 AND vnor=1 AND typ2=0  ORDER BY nazev_cz, id") or die(err(1));
    while($row_t = MySQLi_fetch_object($query_t))
    {
		
		echo "<div class='col-6 col-xs-6 col-sm-6 col-md-4 col-lg-3'>
				<a class='homilie-single' href='/".__LANG__."/texty/".$row_t->$str."/".$row_t->id.".html'>
				<img alt='".stripslashes($row_t->$nazev)."' data-src='/img/texty-single.jpg' src='/img/lazy/lazy-texty-single.jpg' title='".stripslashes($row_t->$nazev)."'>
				<p>
				".stripslashes($row_t->$nazev)."
				</p>
				</a>
			  </div>";
		
	}
	
	echo "</div>
</div>
</section>
</main>";
	
}

?>
