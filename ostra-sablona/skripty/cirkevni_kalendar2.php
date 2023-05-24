<?php
/*
    @author Ondrej Laga
    @date 7.5.2011
*/
class cirkevniTyden {
    public $tyden = null;
    public $time = null;
    public $cacheFileName;
    public $mapSlavnosti;
    public $mapSvatky;
    public $mapEvangelia;
    public $mapCteni;
    public $katolikFail = false, $katolikFormatFail = false;

    function __construct($time=null, $cache=true, $cacheFileName = "./cache.txt") {
        if (empty($time)) $time = time();
        $this->time = $time;
        $changed = false;
        $nactenyTyden = null;
        $this->cacheFileName = $cacheFileName;
        if ($cache && file_exists($this->cacheFileName)) {
            $bytestring = implode("", @file($this->cacheFileName));
            $nactenyTyden = unserialize($bytestring);
        }
        for ($i=0; $i<7; $i++) {
            $denTime = $this->time + ($i*24*60*60);
            $klic = Date("j_n_Y", $denTime); //4_1_2011
            $tDen = Date("j", $denTime);
            $tMesic = Date("n", $denTime);
            $tRok = Date("Y", $denTime);
            if ($cache && is_array($nactenyTyden) && array_key_exists($klic, $nactenyTyden)) {
                $tyden[$klic] = $nactenyTyden[$klic]; //nactu z cache
                //
            } else {
                $changed = true;
                if (!$this->katolikFail && !$this->katolikFormatFail) {
                  $tyden[$klic] = new cirkevniDen($denTime); //nactu z webu
                  $this->katolikFail = $tyden[$klic]->katolikFail;
                  $this->katolikFormatFail = $tyden[$klic]->katolikFormatFail;
                  if ($this->katolikFail) {
                    throw new Exception('Nezdařilo se připojení na katolik.cz');
                  }
                  else  if ($this->katolikFormatFail) {
                    throw new Exception('Kalendář z katolik.cz má neočekávaný formát');
                  }
                }
            }
        }
        $this->tyden = $tyden;
        if ($cache && $changed) { //ulozim do souboru
            $bytestring=serialize($tyden);
            $f = fopen($this->cacheFileName, "w"); //vytvoření a otevření souboru pro zápis
            fputs($f, $bytestring); //uložení bytestringu do souboru
            fclose($f);
        }
        $this->mapEvangelia = array();
        $this->mapSvatky = array();
        $this->mapSlavnosti = array();
        $this->mapCteni = array();
    }

    //ziskani dne v tydnu podle indexu
    function getCirkevniDenByIndex($index) {
        $time = $this->time + (intval($index)*24*60*60);
        return $this->getCirkevniDenByTime($time);
    }

    //ziskani dne v tydnu podle casu
    function getCirkevniDenByTime($time) {
        $klic = Date("j_n_Y", $time);
        if (!(is_array($this->tyden) && array_key_exists($klic, $this->tyden))) { //pokud by nahodou nenasel tak nactu z webu
            $this->tyden[$klic] = new cirkevniDen($time);
        }
        return $this->tyden[$klic];
    }
    
    function loadVazby($DB) {
      $rs = $DB->query("SELECT type, code, url FROM export_vazby");
      if ($rs) {
        while ($fields = $rs->fetch_array()) {
          switch ($fields['type']) {
            case 1: {
              if (!array_key_exists($fields['code'], $this->mapEvangelia)) {
                $this->mapEvangelia[$fields['code']] = array();
              }
              $this->mapEvangelia[$fields['code']][] = $fields['url'];
            } break;
            case 2: {
              if (!array_key_exists($fields['code'], $this->mapSvatky)) {
                $this->mapSvatky[$fields['code']] = array();
              }
              $this->mapSvatky[$fields['code']][] = $fields['url'];
            } break;
            case 3: {
              if (!array_key_exists($fields['code'], $this->mapSlavnosti)) {
                $this->mapSlavnosti[$fields['code']] = array();
              }
              $this->mapSlavnosti[$fields['code']][] = $fields['url'];
            } break;
            case 4: {
              if (!array_key_exists($fields['code'], $this->mapCteni)) {
                $this->mapCteni[$fields['code']] = array();
              }
              $this->mapCteni[$fields['code']][] = $fields['url'];
            } break;
          }
        }
      }
      else {
        echo 'query fail: '.$DB->error.'<br>';
      }
    }
}

class cirkevniDen {
    public $svatek = null;
    public $cteni1 = null;
    public $cteni1link = 1;
    public $cteni2 = null;
    public $zalm = null;
    public $evangelium = null;
    public $obcanskyKal = null;
    public $cirkevniKal = null;
    public $time = null;
    public $cyklus = null;
    public $katolikFail = false, $katolikFormatFail = false;

    function __construct($time) {
      $this->time = $time;
      $tDen = Date("j", $time);
      $tMesic = Date("n", $time);
      $tRok = Date("Y", $time);
      $ctx = stream_context_create(array(
          'http' => array(
              'timeout' => 2
              )
          )
      );
      $tmp = @file_get_contents('http://www.katolik.cz/rss/kapky.asp?d='.$tDen.'&m='.$tMesic.'&r='.$tRok, 0, $ctx);
      if ($tmp !== false) {
        $tmp = iconv('cp1250', 'utf-8', $tmp);
        $data = json_decode($tmp, true);
        if ($data !== false) {
          $this->svatek = trim($data['liturgickyKalendar']); 
          $this->obcanskyKal = trim($data['obcanskyKalendar']);
          $this->cirkevniKal = trim($data['cirkevniKalendar']);
          $this->cteni1 = trim($data['cteni1']);
          if (empty($this->cteni1)) {
            $this->cteni1link = 0;
          }
          $this->zalm = trim($data['zalm']);
          $this->cteni2 = trim($data['cteni2']);
          $this->evangelium = trim($data['evangelium']); 
          $this->cyklus = trim($data['cyklus']); 
        }
        else {
          $this->katolikFormatFail = true;
        }
      }
      else {
        $this->katolikFail = true;
      }
    }

    //zjisteni kategorie na kapky.eu - na vetsinu pripadu funguje ale je potreba jeste doplnit mapovani o ruzne svatky a slavnosti
    function getCategory($tyden, &$typ=null) {
        $svatek = html_entity_decode($this->svatek, null, 'utf-8');
        $slavnost = html_entity_decode($this->cirkevniKal, null, 'utf-8');
        $evangelium = html_entity_decode($this->evangelium, null, 'utf-8');
        $category = null;
        if (!empty($svatek)) { //nedele a slavnosti
          if (array_key_exists($svatek, $tyden->mapSlavnosti)) {
            $category = $tyden->mapSlavnosti[$svatek];
          }
          else {
            $svatek2 = $svatek.' '.$this->getCyklus();
            if (array_key_exists($svatek2, $tyden->mapSlavnosti)) {
              $category = $tyden->mapSlavnosti[$svatek2];
            }
          }
          if (!is_null($typ)) $typ = 1;
        }

        if (is_null($category) && !empty($slavnost)) { //svatky
          if (array_key_exists($slavnost, $tyden->mapSvatky)) {
            $category = $tyden->mapSvatky[$slavnost];
          }
          else {
            $slavnost2 = $slavnost.' '.$this->getCyklus();
            if (array_key_exists($slavnost2, $tyden->mapSvatky)) {
              $category = $tyden->mapSvatky[$slavnost2];
            }
          }
          if (!is_null($typ)) $typ = 2;
        }

        if (is_null($category)) {
            //hledam podle evangelia
            if (array_key_exists($evangelium, $tyden->mapEvangelia)) {
               $category = $tyden->mapEvangelia[$evangelium];
            }
            else {
              if (preg_match('/.*[a-z]$/i', $evangelium)) {
                $evangelium = substr($evangelium, 0, strlen($evangelium)-1);
                if (array_key_exists($evangelium, $tyden->mapEvangelia)) {
                   $category = $tyden->mapEvangelia[$evangelium];
                }
              }
            }
            if (!is_null($typ)) $typ = 0;
        }
        return $category;
    }

    function getCategoryCteni($tyden) {
        $category = null;
        $cteni1 = html_entity_decode($this->cteni1, null, 'utf-8');

        if (array_key_exists($cteni1, $tyden->mapCteni)) {
           $category = $tyden->mapCteni[$cteni1];
        }
        else {
          if (preg_match('/.*[a-z]$/i', $cteni1)) {
            $cteni1 = substr($cteni1, 0, strlen($cteni1)-1);
            if (array_key_exists($cteni1, $tyden->mapCteni)) {
               $category = $tyden->mapCteni[$cteni1];
            }
          }
        }
        return $category;
    }

    //informativni vypis
    function info() {
        echo '<table border=0>';
        echo '<tr><td>Datum:</td><td>'.Date("j.n.Y", $this->time).'</td></tr>';
        echo '<tr><td>1. čtení:</td><td><a href="'.$this->biblenet($this->cteni1).'" target="_blank">'.$this->cteni1.'</a></td></tr>';
        echo '<tr><td>Žalm:</td><td><a href="'.$this->biblenet($this->zalm).'" target="_blank">'.$this->zalm.'</a></td></tr>';
        echo '<tr><td>2. čtení:</td><td><a href="'.$this->biblenet($this->cteni2).'" target="_blank">'.$this->cteni2.'</a></td></tr>';
        echo '<tr><td>Evangelium:</td><td><a href="'.$this->biblenet($this->evangelium).'" target="_blank">'.$this->evangelium.'</a></td></tr>';
        echo '<tr><td>Svátek:</td><td>'.$this->svatek.'</td></tr>';
        echo '<tr><td>Církevní:</td><td>'.$this->cirkevniKal.'</td></tr>';
        echo '<tr><td>Občanský:</td><td>'.$this->obcanskyKal.'</td></tr>';
        echo '<tr><td>Cyklus:</td><td>'.$this->getCyklus().'</td></tr>';
        echo '</table><br>';
    }

    //informativni vypis
    function info2() {
        echo '<tr>';
        echo '<td>'.Date("j.n.Y", $this->time).'</td>';
        echo '<td><a href="'.$this->biblenet($this->cteni1).'" target="_blank">'.$this->cteni1.'</a></td>';
        echo '<td><a href="'.$this->biblenet($this->zalm).'" target="_blank">'.$this->zalm.'</a></td>';
        echo '<td><a href="'.$this->biblenet($this->cteni2).'" target="_blank">'.$this->cteni2.'</a></td>';
        echo '<td><a href="'.$this->biblenet($this->evangelium).'" target="_blank">'.$this->evangelium.'</a></td>';
        $cat = $this->getCategory();
        if ($cat==0) {
            echo '<td><div style="color: red"><b>CHYBA</b></div></td>';
            echo '<td></td>';
        } else {
            echo '<td><div style="color: green"><b>OK '.$cat.'</b></div></td>';
            echo '<td><a href="http://kapky.eu?cat='.$cat.'" target="_blank">http://kapky.eu?cat='.$cat.'</a></td>';
        }
        echo '</tr>';
    }
    
    
    //informativni vypis
    function info3() {
        echo '<tr>';
        echo '<td>'.Date("j.n.Y", $this->time).'</td>';
        echo '<td>'.$this->svatek.'</a></td>';
        echo '<td>'.$this->cirkevniKal.'</a></td>';
        echo '<td><a href="'.$this->biblenet($this->cteni1).'" target="_blank">'.$this->cteni1.'</a></td>';
        echo '<td><a href="'.$this->biblenet($this->evangelium).'" target="_blank">'.$this->evangelium.'</a></td>';
        
        
        /*$cat = $this->getCategory();
        if ($cat==0) {
            echo '<td><div style="color: red"><b>CHYBA</b></div></td>';
            echo '<td></td>';
        } else {
            echo '<td><div style="color: green"><b>OK '.$cat.'</b></div></td>';
            echo '<td><a href="http://kapky.eu?cat='.$cat.'" target="_blank">http://kapky.eu?cat='.$cat.'</a></td>';
        }*/
        echo '</tr>';
    }
    
    
    
    function info4() {
        
        $datum = Date("j.n.Y", $this->time);
         
        if($datum == date("j.n.Y"))
        {
			$datum_den = __DNESNI_DEN__;
			 echo '<div >';
		}
		else
		{   
			$datum_den = cesky_den(date("w",$this->time));
			echo '<div>';
		}
        
       

				echo '<div class="dailyWord__single';
				
				if($datum == date("j.n.Y"))
				{ 
					echo ' w-6';
				}
				
				echo '">
					<h3>Slovo na '.$datum_den.'</h3>

					<div class="dailyWord__wrap">';
					
					
					if($this->cteni1)
					{
						
						echo '<div class="dailyWord__wrapItem">
							<span class="dailyWord__label">1. '.__CTENI__.':</span>
							<a href="'.$this->biblenet($this->cteni1).'" target="_blank">'.translateVerse($this->cteni1).'</a>
						</div>';
					}
					
					if($this->zalm)
					{
						echo '<div class="dailyWord__wrapItem">
							<span class="dailyWord__label">Žalm:</span>
							<a href="'.$this->biblenet($this->zalm).'" target="_blank">'.translateVerse($this->zalm).'</a>
						</div>';
					}
					
					if($this->cteni2)
					{
					
					    echo '<div class="dailyWord__wrapItem">
							<span class="dailyWord__label">2. '.__CTENI__.':</span>
							<a href="'.$this->biblenet($this->cteni2).'" target="_blank">'.translateVerse($this->cteni2).'</a>
						</div>';
					}
					
					if($this->evangelium)
					{
						echo '<div class="dailyWord__wrapItem">
							<span class="dailyWord__label">'.__EVANGELIUM__.':</span>
							<a href="'.$this->biblenet($this->evangelium).'" target="_blank">'.translateVerse($this->evangelium).'</a>
						</div>';
				    
				    }
				    
				    
				echo '</div>';    
					
 
    }

    //ziskani cyklu cirkevniho roku (A,B,C)
    function getCyklus() {
      return $this->cyklus;
    }

    //rozdeleni citatu z bible na knihu, kapitolu a verse
    function parseCitat($citat) {
        if (empty($citat)) return;
        $bezmezer = preg_replace('/\s/','',$citat);
        $pattern = '~^(?P<kniha>[0-9]*[a-žA-Ž]+)(?P<kapitola>[0-9]*)?,?(?P<vers1>[0-9]+)?(?P<zbytek>.*)?$~';
        preg_match($pattern, $bezmezer, $matches);
        $ret["kniha"] = $matches["kniha"];
        $ret["kapitola"] = $matches["kapitola"];
        if (array_key_exists("vers1", $matches)) {
            $ret["vers1"] = $matches["vers1"];
        }
        if (array_key_exists("zbytek", $matches)) {
          $pattern = '~(?P<vers2>[0-9]+)[a-z]*$~';
          preg_match($pattern, $matches["zbytek"], $matches1);
          if (array_key_exists("vers2", $matches1)) {
              $ret["vers2"] = $matches1["vers2"];
          }
        }
        return $ret;
    }

    //odkaz na citat z bible na http://www.bibleserver.com
    function bibleserver($citat){
        if (empty($citat)) return;
        //http://www.bibleserver.com/go.php?lang=cz&bible=CEP&ref=16001001
        global $mapBible;
        $ret = $this->parseCitat($citat);
        if (array_key_exists($ret["kniha"], $mapBible)) {
            $kniha = $mapBible[$ret["kniha"]][0];
            $kapitola = $ret["kapitola"];
            if (strlen($kapitola)<4) {
                for($i=0; $i<(3-strlen($ret["kapitola"])); $i++) {
                    $kapitola = '0'.$kapitola;
                }
                return "http://www.bibleserver.com/go.php?lang=cz&bible=CEP&ref=".$kniha.$kapitola.'000';
            }
		    }
        return "http://www.bibleserver.com";
    }

    //odkaz na citat z bible na http://www.biblenet.cz
    function biblenet($citat) {
        global $mapBible;
        if (empty($citat)) return;
        //http://www.biblenet.cz/app/bible/passage?passagePath.path=lukas%2024,35-48
        $ret = $this->parseCitat($citat);
        if (array_key_exists($ret["kniha"], $mapBible) && $ret["kniha"]!="") {
            $str = $mapBible[$ret["kniha"]][1];
            if (!array_key_exists("kapitola", $ret) || $ret["kapitola"]=="") {
                $ret["kapitola"] = 1;
            }
            $str .= " ".$ret["kapitola"];
            if (array_key_exists("vers1", $ret) && $ret["vers1"]!="") {
                $str .= ",".$ret["vers1"];
                if (array_key_exists("vers2", $ret) && $ret["vers2"]!="") {
                    $str .= "-".$ret["vers2"];
                }
            }
            return "http://www.biblenet.cz/app/bible/passage?passagePath.path=".$str;
        }
        return "http://www.biblenet.cz";
    }
}
?>
