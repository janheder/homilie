<?
// homilie děti

$nazev = "nazev_".__LANG__;
$obsah = "obsah_".__LANG__;
$str = "str_".__LANG__;
$aktivni = "aktivni_".__LANG__;
$autor = "autor_".__LANG__;	
$prelozil = "prelozil_".__LANG__;	
$zdroj = "zdroj_".__LANG__;
	

	
if($_GET['idh'])
{
	
	
	$query_tx = MySQLi_Query($spojeni,"SELECT *  FROM homileticke_publikace WHERE $aktivni=1 AND id='".intval($_GET['idh'])."' ") or die(err(1));
	$row_tx = MySQLi_fetch_object($query_tx);
	

	   if($row_tx->pozadi)
		{
			$pozadi = "/prilohy/".$row_tx->pozadi;
		}
		else
		{
			$pozadi = "/img/homilie-header.jpg";
		}
		
		// vypis podkategorii
		
			
			//  vypis
			
			$h = 0;
			
			$query_t = MySQLi_Query($spojeni,"SELECT id, $nazev, $str, vnor, id_nadrazeneho, typ, typ2, pozadi, ico, $autor FROM homileticke_publikace WHERE $aktivni=1 AND id_nadrazeneho='".intval($_GET['idh'])."'  ORDER BY nazev_cz, id") or die(err(1));
		    while($row_t = MySQLi_fetch_object($query_t))
		    {
	              

				if($row_t->vnor==4)
				{
					
					if($row_t->typ2==1 && $h==0)
					{
						   if($row_t->pozadi)
							{
								$pozadi = "/prilohy/".$row_t->pozadi;
							}
							else
							{
								$pozadi = "/img/homilie-header.jpg";
							}
		
						// text vypis
						echo "<section class='page-heading page-heading-post post__image-container'>
							<div class='container'>
							<span>";
							/*if($row_t->$autor)
							{
								echo __NAPSAL__.": ".stripslashes($row_t->$autor);
							}*/
							
							echo "</span>
							<h1>
							".stripslashes($row_tx->$nazev)."
							</h1>
							</div>
							<img alt='".stripslashes($row_t->$nazev)."' class='post__featured-image' data-src='".$pozadi."' src='/img/lazy/lazy-texty-header.jpg'>
							<section class='breadcrumb-wrap'>
							<div class='container'>";
							
							drobinka($p,$menu_all,$spojeni);
							
							
							echo "</div>
							</section>
							</section>
							<main>
							<div class='container'>";
						
						echo "<article class='post'>";
						strankovani_homilie_pub($_GET['idh'],$spojeni);
						texty_homilie_pub($_GET['idh'],$spojeni);
						echo "</article>";
						
					}
					elseif($row_t->typ2==0)
					{
						// kategorie
						
						if($h==0)
						{
						echo "<section class='page-heading post__image-container'>
							<div class='container'>
							<h1>
							".__HOMILETICKE_PUBLIKACE__."
							</h1>
							</div>
							<img alt='homilie' class='post__featured-image' data-src='".$pozadi."' src='/img/lazy/lazy-homilie-header.jpg'>
							<section class='breadcrumb-wrap'>
							<div class='container'>";
							
								drobinka($p,$menu_all,$spojeni);
							
							echo "</div>
							</section>
							</section>
							<main>";
							
							
							echo "<section class='homilie --column'>
							<div class='container'>
							<div class='row'>";
						}
						
						$n2 = MySQLi_Query($spojeni,"SELECT id, $str, id_nadrazeneho FROM homileticke_publikace WHERE id='".$row_t->id_nadrazeneho."' ");
						$row_n2 = MySQLi_fetch_object($n2);
						
						$n3 = MySQLi_Query($spojeni,"SELECT id, $str, id_nadrazeneho FROM homileticke_publikace WHERE id='".$row_n2->id_nadrazeneho."' ");
						$row_n3 = MySQLi_fetch_object($n3);
						
						echo "<div class='col-6 col-xs-6 col-sm-6 col-md-4 col-lg-3'>
							<a class='homilie-single' href='/".__LANG__."/homileticke-publikace/".$row_n3->$str."/".$row_n2->$str."/".$row_t->$str."/".$row_t->id.".html'>
							<img alt='".stripslashes($row_t->$nazev)."' src='/img/".$row_t->ico."' title='".stripslashes($row_t->$nazev)."'>
							<p>
							".stripslashes($row_t->$nazev)."
							</p>
							</a>
						  </div>";
					  
					}
					
				}
				elseif($row_t->vnor==3)
				{
					
					
						if($row_t->typ2==1 && $h==0)
						{
							   if($row_t->pozadi)
								{
									$pozadi = "/prilohy/".$row_t->pozadi;
								}
								else
								{
									$pozadi = "/img/homilie-header.jpg";
								}
			
							// text vypis
							echo "<section class='page-heading page-heading-post post__image-container'>
								<div class='container'>
								<span>";
								/*if($row_t->$autor)
								{
									echo __NAPSAL__.": ".stripslashes($row_t->$autor);
								}*/
								
								echo "</span>
								<h1>
								".stripslashes($row_tx->$nazev)."
								</h1>
								</div>
								<img alt='".stripslashes($row_t->$nazev)."' class='post__featured-image' data-src='".$pozadi."' src='/img/lazy/lazy-texty-header.jpg'>
								<section class='breadcrumb-wrap'>
								<div class='container'>";
								
								drobinka($p,$menu_all,$spojeni);
								
								
								echo "</div>
								</section>
								</section>
								<main>
								<div class='container'>";
							
							echo "<article class='post'>";
							strankovani_homilie_pub($_GET['idh'],$spojeni);
							texty_homilie_pub($_GET['idh'],$spojeni);
							echo "</article>";
							
						}
						elseif($row_t->typ2==0)
						{
							// kategorie
							
							
							if($h==0)
							{
							echo "<section class='page-heading post__image-container'>
								<div class='container'>
								<h1>
								".__HOMILETICKE_PUBLIKACE__."
								</h1>
								</div>
								<img alt='homilie' class='post__featured-image' data-src='".$pozadi."' src='/img/lazy/lazy-homilie-header.jpg'>
								<section class='breadcrumb-wrap'>
								<div class='container'>";
								
									drobinka($p,$menu_all,$spojeni);
								
								echo "</div>
								</section>
								</section>
								<main>";
								
								
								echo "<section class='homilie --column'>
								<div class='container'>
								<div class='row'>";
							}
							
							$n2 = MySQLi_Query($spojeni,"SELECT id, $str, id_nadrazeneho FROM homileticke_publikace WHERE id='".$row_t->id_nadrazeneho."' ");
							$row_n2 = MySQLi_fetch_object($n2);
							
							$n3 = MySQLi_Query($spojeni,"SELECT id, $str, id_nadrazeneho FROM homileticke_publikace WHERE id='".$row_n2->id_nadrazeneho."' ");
							$row_n3 = MySQLi_fetch_object($n3);
							
							echo "<div class='col-6 col-xs-6 col-sm-6 col-md-4 col-lg-3'>
								<a class='homilie-single' href='/".__LANG__."/homileticke-publikace/".$row_n3->$str."/".$row_n2->$str."/".$row_t->$str."/".$row_t->id.".html'>
								<img alt='".stripslashes($row_t->$nazev)."' src='/img/".$row_t->ico."'  title='".stripslashes($row_t->$nazev)."'>
								<p>
								".stripslashes($row_t->$nazev)."
								</p>
								</a>
							  </div>";
						  
						}
					
					
			 
						
				}
				elseif($row_t->vnor==2)
				{
					
					if($h==0)
						{
							echo "<section class='page-heading post__image-container'>
						<div class='container'>
						<h1>
						".__HOMILETICKE_PUBLIKACE__."
						</h1>
						</div>
						<img alt='homilie' class='post__featured-image' data-src='".$pozadi."' src='/img/lazy/lazy-homilie-header.jpg'>
						<section class='breadcrumb-wrap'>
						<div class='container'>";
						
							drobinka($p,$menu_all,$spojeni);
						
						echo "</div>
						</section>
						</section>
						<main>";
						
						
						echo "<section class='homilie --column'>
						<div class='container'>
						<div class='row'>";
					     }
				   
				   echo "<div class='col-6 col-xs-6 col-sm-6 col-md-4 col-lg-3'>
						<a class='homilie-single' href='/".__LANG__."/homileticke-publikace/".$row_tx->$str."/".$row_t->$str."/".$row_t->id.".html'>
						<img alt='".stripslashes($row_t->$nazev)."' src='/img/".$row_t->ico."'  title='".stripslashes($row_t->$nazev)."'>
						<p>
						".stripslashes($row_t->$nazev)."
						</p>
						</a>
					  </div>";
				   	
				}
				
				$h++;
				
			}
			
			echo "</div>
		</div>
		</section>
		</main>";
		
	
	
	
}
else
{
	
	echo "<section class='page-heading post__image-container'>
<div class='container'>
<h1>
".__HOMILETICKE_PUBLIKACE__."
</h1>
</div>
<img alt='homilie' class='post__featured-image' data-src='/img/homilie-header.jpg' src='/img/lazy/lazy-homilie-header.jpg'>
<section class='breadcrumb-wrap'>
<div class='container'>";

	drobinka($p,$menu_all,$spojeni);

echo "</div>
</section>
</section>
<main>";


echo "<section class='homilie --column'>
<div class='container'>
<div class='row'>";
	
	// zakladni vypis

	
	$query_t = MySQLi_Query($spojeni,"SELECT id, $nazev, $str, ico FROM homileticke_publikace WHERE $aktivni=1 AND vnor=1 AND typ2=0  ORDER BY $nazev, id") or die(err(1));
    while($row_t = MySQLi_fetch_object($query_t))
    {
		
		echo "<div class='col-12 col-xs-6 col-sm-6 col-md-4 col-lg-4'>
		      <div class='homilie-single'>
				<a  href='/".__LANG__."/homileticke-publikace/".$row_t->$str."/".$row_t->id.".html'>
				<img alt='".stripslashes($row_t->$nazev)."' src='/img/".$row_t->ico."' title='".stripslashes($row_t->$nazev)."'>
				<p>
				".stripslashes($row_t->$nazev)."
				</p>
				</a>
			  <div class='homilie-single-sub'>";
			  
 
				
				
				echo "
					</div>
				</div>
				</div>";
				
	}
	
	
	
	echo "</div>
</div>
</section>
</main>";
	
}

?>


