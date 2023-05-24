<?
// vyhledavani


echo "<section class='page-heading-small'></section>
<main>
<div class='container'>
<article class='form'>
<h2>
".__VYHLEDAVANI__."
</h2>
<div class='row'>
<div class='col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12'>";

if(strlen(strip_tags(addslashes($_POST['h_text'])))<2)
	{
	echo "<br />Vyhledávané slovo musí mít alespoň dva znaky!";
	}
	else
	{
	// staticke stranky
	$query_sp = MySQLi_Query($spojeni,"SELECT str, nadpis FROM stranky WHERE (obsah LIKE '%".strip_tags(addslashes($_POST['h_text']))."%' OR nadpis LIKE '%".strip_tags(addslashes($_POST['h_text']))."%') AND aktivni=1  AND systemova!=1  AND lang='".__LANG__."' LIMIT 100") or die(err(1));
		
		$pocet = mysqli_num_rows($query_sp);		
		 if($pocet<1)
		 {
		echo "<br />žádný výsledek pro statické stránky";
		  }
		  else
		  {
		  echo "<br />výsledky pro statické stránky: <br />";
		   while($row_sp = mysqli_fetch_object($query_sp))
		    {
			echo "<a href=\"/".__LANG__."/".$row_sp->str.".html\">
			<strong>".$row_sp->nadpis."</strong></a><br />";	
		
	            }
		  }
		  
	// aktualne  
		$query_sp2 = MySQLi_Query($spojeni,"SELECT nadpis, id FROM aktuality WHERE (nadpis LIKE '%".strip_tags(addslashes($_POST['h_text']))."%'  OR perex LIKE '%".strip_tags(addslashes($_POST['h_text']))."%' OR text LIKE '%".strip_tags(addslashes($_POST['h_text']))."%') AND aktivni=1  AND lang='".__LANG__."' LIMIT 100") or die(err(1));
		

		$pocet2 = mysqli_num_rows($query_sp2);
		
		 if($pocet2<1)
		 {
		echo "<br />žádný výsledek pro novinky";
		  }
		  else
		  {
		   echo "<br />výsledky pro novinky: <br />";
		   while($row_sp2 = mysqli_fetch_object($query_sp2))
		   {
			echo "<a href=\"/".__LANG__."/novinky/".bez_diakritiky($row_sp2->nadpis)."/".$row_sp2->id.".html\">
			<strong>".$row_sp2->nadpis."</strong></a><br />";	
		
		   }
		  }
		  	  
	
		// homilie 
		
		$nazev = "nazev_".__LANG__;
		$obsah = "obsah_".__LANG__;
	    $nadpis = "nadpis_".__LANG__;
	    $str = "str_".__LANG__;
	    $aktivni = "aktivni_".__LANG__;
	    $autor = "autor_".__LANG__;

	
		$query_sp3 = MySQLi_Query($spojeni,"SELECT id, $str, $nazev, vnor, id_nadrazeneho FROM homilie WHERE ($nazev LIKE '%".strip_tags(addslashes($_POST['h_text']))."%'  OR $obsah LIKE '%".strip_tags(addslashes($_POST['h_text']))."%' OR $autor LIKE '%".strip_tags(addslashes($_POST['h_text']))."%') AND $aktivni=1  AND typ2=1 LIMIT 300") or die(err(1));
		

		$pocet3 = mysqli_num_rows($query_sp3);
		
		 if($pocet3<1)
		 {
		     echo "<br />žádný výsledek pro homílie";
		 }
		 else
		 {
		   echo "<br />výsledky pro homílie: <br />";
		   while($row_sp3 = mysqli_fetch_object($query_sp3))
		   {
			   if($row_sp3->vnor==4)
			   {
				   
				   $query_sp4 = MySQLi_Query($spojeni,"SELECT id, $str, vnor, id_nadrazeneho FROM homilie WHERE id=".$row_sp3->id_nadrazeneho." ") or die(err(1));
				   $row_sp4 = mysqli_fetch_object($query_sp4);
				   
				   $query_sp5 = MySQLi_Query($spojeni,"SELECT id, $str, vnor, id_nadrazeneho FROM homilie WHERE id=".$row_sp4->id_nadrazeneho." ") or die(err(1));
				   $row_sp5 = mysqli_fetch_object($query_sp5);
				   
				   $query_sp6 = MySQLi_Query($spojeni,"SELECT id, $str, vnor FROM homilie WHERE id=".$row_sp5->id_nadrazeneho." ") or die(err(1));
				   $row_sp6 = mysqli_fetch_object($query_sp6);
				   
				   echo "<a href=\"/".__LANG__."/homilie/".$row_sp6->$str."/".$row_sp5->$str."/".$row_sp4->$str."/".$row_sp4->id.".html?t=".$row_sp3->id."\"><strong>".$row_sp3->$nazev."</strong></a><br />";	
				   
			   }
			   elseif($row_sp3->vnor==3)
			   {
				   
				   $query_sp4 = MySQLi_Query($spojeni,"SELECT id, $str, vnor, id_nadrazeneho FROM homilie WHERE id=".$row_sp3->id_nadrazeneho." ") or die(err(1));
				   $row_sp4 = mysqli_fetch_object($query_sp4);
				   
				   $query_sp5 = MySQLi_Query($spojeni,"SELECT id, $str, vnor FROM homilie WHERE id=".$row_sp4->id_nadrazeneho." ") or die(err(1));
				   $row_sp5 = mysqli_fetch_object($query_sp5);
				  
				   echo "<a href=\"/".__LANG__."/homilie/".$row_sp5->$str."/".$row_sp4->$str."/".$row_sp4->id.".html?t=".$row_sp3->id."\"><strong>".$row_sp3->$nazev."</strong></a><br />";	
				   
				   
			   }
			   elseif($row_sp3->vnor==2)
			   {
				   $query_sp4 = MySQLi_Query($spojeni,"SELECT id, $str, vnor FROM homilie WHERE id=".$row_sp3->id_nadrazeneho." ") or die(err(1));
				   $row_sp4 = mysqli_fetch_object($query_sp4);
				   
				   echo "<a href=\"/".__LANG__."/homilie/".$row_sp4->$str."/".$row_sp3->$str."/".$row_sp3->id.".html\"><strong>".$row_sp3->$nazev."</strong></a><br />";	
				
			   }
			   elseif($row_sp3->vnor==1)
			   {
				   echo "<a href=\"/".__LANG__."/homilie/".$row_sp3->$str."/".$row_sp3->id.".html\"><strong>".$row_sp3->$nazev."</strong></a><br />";	
			   }
			   
			
		
		   }
		   
		  }
	
	
	// texty 

	
		$query_sp3 = MySQLi_Query($spojeni,"SELECT id, $str, $nazev FROM texty 
		WHERE ($nazev LIKE '%".strip_tags(addslashes($_POST['h_text']))."%'  OR $obsah LIKE '%".strip_tags(addslashes($_POST['h_text']))."%' OR $autor LIKE '%".strip_tags(addslashes($_POST['h_text']))."%') AND $aktivni=1  AND typ2=1 LIMIT 100") or die(err(1));
		

		$pocet3 = mysqli_num_rows($query_sp3);
		
		 if($pocet3<1)
		 {
		echo "<br />žádný výsledek pro texty";
		  }
		  else
		  {
		   echo "<br />výsledky pro texty: <br />";
		   while($row_sp3 = mysqli_fetch_object($query_sp3))
		   {
			echo "<a href=\"/".__LANG__."/texty/".$row_sp3->$str."/".$row_sp3->id.".html\">
			<strong>".$row_sp3->$nazev."</strong></a><br />";	
		
		   }
		  }
	
	
	
           }
		



echo "</div>
</div>
</article>
</div>
</main>
";

?>
