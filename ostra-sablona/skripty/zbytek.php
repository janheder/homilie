<?
// zbytek

    if(file_exists("./skripty/".$p.".php"))
     {
      include_once("./skripty/$p.php");
      MySQLi_Query ($spojeni,"UPDATE stranky SET precteno=precteno+1 WHERE str='$p' AND lang='".__LANG__."'") or die(err(3));
     }
    elseif (array_key_exists($p, $menu_all))
     {
		 $query_nadpis = MySQLi_Query($spojeni,"SELECT id, nadpis, obsah, fotogalerie, pozadi FROM stranky WHERE str='".$p."' AND lang='".__LANG__."'") or die(err(1));
         $row_nadpis = MySQLi_fetch_object($query_nadpis);
         
         MySQLi_Query ($spojeni,"UPDATE stranky SET precteno=precteno+1 WHERE str='$p' AND lang='".__LANG__."'") or die(err(3));
      
        
        
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
			<article class='page'>
			<div class='print-area'>
			<p>";
		 
		 echo stripslashes($row_nadpis->obsah);
		  
		  
		  echo "</div>
				</article>
				</div>
				</main>";
		 
		 
	 }
	else
     {
      echo "<br />Stránka <strong>".$p."</strong> se na serveru nenachází.<br />";
     }
?>
