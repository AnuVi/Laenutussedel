<?php

$conn = mysql_connect ('localhost', '','');   //ühenduse loomine andmebaasiga
mysql_select_db(''); 

//PATRONAPI

/**
* This is a set of functions that allow access to the III Patron API
* This file is released under the GNU Public License
* @package PatronAPI
* @Author John Blyberg
*/


/**
* Change this to your PatronAPI server
*/
//define("APISERVER", "https://tartu.ester.ee:54620");


// Nothing below here needs to be configured

/**
* Returns patron data from the API in an easy-to-use array
* @param string $id Can be either a barcode number or a pnum, the function can tell which
* @return array 
*/
function get_patronapi_data($id) {

	$apiurl = APISERVER."/PATRONAPI/$id/dump";
	//echo $apiurl;
	$api_contents = get_api_contents($apiurl);
	$api_array_lines = explode("\n", $api_contents);
	//echo $api_array_lines;
		foreach ($api_array_lines as $api_line) {
			$api_line = str_replace("p=", "peq", $api_line);
			$api_line_arr = explode("=", $api_line);
			$regex_match = array("/\[(.*?)\]/","/\s/","/#/");
			$regex_replace = array('','','NUM');
			$key = trim(preg_replace($regex_match, $regex_replace, $api_line_arr[0]));
			//echo $key;
			$api_data[$key] = trim($api_line_arr[1]);
			//echo $api_data[$key]; 
		
	}
	return $api_data;
}

/**
* Checks tha validity of an id/pin combo
* @param string $id Can be either a barcode number or a pnum, the function can tell which
* @param string $pin The password/pin to use with $id
* @return array
*/
function check_validation($id, $pin) {

	$pin = urlencode($pin);
	$apiurl = APISERVER . "/PATRONAPI/$id/$pin/pintest";
	//echo $apiurl;
	$api_contents = get_api_contents($apiurl);

	$api_array_lines = explode("\n", $api_contents);
	foreach ($api_array_lines as $api_line) {
		$api_line_arr = explode("=", $api_line);
		$api_data[$api_line_arr[0]] = $api_line_arr[1];
	}

	return $api_data;
}

/**
* An internal function to grab the API XML
* @param string $apiurl The formulated url to the patron API record
* @return string
*/
function get_api_contents($apiurl) {
	$api_contents = file_get_contents($apiurl);
	$api_contents = trim(strip_tags($api_contents));
	return $api_contents;
}

// nextid - id suureneks
  function nextid($field=0, $table=0) 
  { global $conn;
    $query='SELECT MAX('.$field.') as nextid from '.$table.'';
	$result=mysql_query($query, $conn);
		if ($result)
		{ 
			$row = mysql_fetch_array($result);
			return $row['nextid']+1;
		}
  }

 // cleanup -jama ei tuleks sisse
 function cleanup($text = null)
 {	if (isset ($text))
	{ 
		return addslashes(strip_tags($text));
	}
 }
// kirjete lisamine andmebaasi Sedeltellimus
 if ( isset ($_POST['telli']) ) 
{ 

  $kviit=trim(cleanup($_POST['kviit']));
  $pnimi = trim(cleanup($_POST['pnimi']));
  $pkiri = trim(cleanup($_POST['pkiri']));
  $autor = trim(cleanup($_POST['autor']));
  $vihik = trim(cleanup($_POST['vihik']));
  $seeria = trim(cleanup($_POST['seeria']));
  $koide = trim(cleanup($_POST['koide']));
  $ilmaasta=$_POST['ilmaasta'];
  $lkaart=strtolower($_POST['lkaart']); //pole vahet, kas suured või väiksed tähed
  $kontr=get_patronapi_data($lkaart);
  $pinn=($_POST['pin']);
  $kontrp=check_validation($_POST['lkaart'],$pinn);
  //kohaviida kontroll
  if(!$kviit || strlen($kviit)<2 ){
  $error1.="<p class='warning'>Rohkem kui kaks sümbolit - More than 2 symbols</p>";
  }
  //pealkirja kontroll
  if (!$pkiri || strlen($pkiri)<2 )
  {
   $error2 .="<p class='warning'>Rohkem kui kaks sümbolit - More than 2 symbols</p>";
  }
  // ilmumisaasta oleks vastavas vahemikus
   if (!$ilmaasta=" " || !preg_match("/^[1]{1}[0-9]{3}$/",$ilmaasta))
  {
   $error3 .="<p class='warning'>Aastaarv-Year:1525-1999</p>";
  }
  // nimi ei oleks number ja rohkem kui kaks tähte
  if (strlen($pnimi)<2 || is_numeric($pnimi) )
  {
   $error4 .="<p class='warning'>Rohkem kui kaks tähte - More than 2 letters</p>";
  }
 
  // numbri kontroll
  if (!$kontr["UNIVID"]==$lkaart)
 {
   $error5.="<p class='warning'>Pole TÜ raamatukogu lugeja - Card number is not valid</p>";
   
	}
  //paroolo kontroll
   if(!$kontrp["RETCOD"]==0 )
	{
  $error6.="<p class='warning'>Salasõna vale - Password is not valid</p>";
	}
  // vigu pole, siis sisesta andmebaasi
  if(!$error1 && !$error2 && !$error3 && !$error4 && !$error5 && !$error6) {
	$query1 ='INSERT INTO Sedeltellimus (ID, Kviit, Perenimi, Pkiri, Autor, Ilmaasta,Seeria, Koide, Vihik, Lkaart) VALUES ('.nextid('ID','Sedeltellimus').',"'.$_POST['kviit'].'","'.$pnimi.'","'.$pkiri.'","'.$autor.'","'.$_POST['ilmaasta'].'","'.$seeria.'","'.$koide.'","'.$vihik.'","'.$_POST['lkaart'].'")';
	$result=mysql_query($query1, $conn);
	header('Location: ok.html'); //kui korras siis suunatakse ümber
	}
 } 
?>
<!-- html algus-->
<!DOCTYPE html>
<html lang="et" dir="ltr">
<head>
<meta charset="utf-8">
<meta name="keywords" content="Tellimine sedelkataloogist">
<meta name="description" content="sedeltellimus, autor">
<title>Saali laenutussedel</title>
<!-- style-->
<link href="stellimus.css" rel="stylesheet" type="text/css">
<!--javascript-->
<script   src="http://google.com/jsapi"></script>
<script type="text/javascript" src="vormikontroll.js"></script>
</head>
<body>      
<!-- form  start-->
	<div id="page_wrap">
    <form method="post" id="stellimus" action="lugeja.php">
		<h3>SEDELTELLIMUS/Call Slip</h3>
		<p>Tellimiseks peate olema TÜ Raamatukogu lugeja</p>
		<p><a href="http://www.utlib.ee/index.php?mod=reg&e_id=18&e=1" target="_blank" title="Lugejaks registreerumine">Registreeru lugejaks</a></p>
		<h6>&#042;kohustuslik väli/mandatory field</h6>
	<!-- kohaviit-->
		<label for="kviit">&#042;KOHAVIIT/Call No:</label>
		<p class="phpw"><?=$error1?></p>
		<input type="text" name="kviit" id="kviit" value="<?php echo $_POST["kviit"];?>">
	<!-- autor-->
		<label for="autor">AUTOR/Author:</label>
		<input type="text" name="autor" id="autor" value="<?php echo $_POST["autor"];?>">
	<!-- pealkiri-->
		<label for="pkiri">&#042;PEALKIRI/Title:</label>
		<p class="phpw"><?=$error2?></p>
		<input type="text" name="pkiri" id="pkiri" maxlength="200" value="<?php echo $_POST["pkiri"];?>">
	<!-- ilmumisaasta-->
		<label for="ilmaasta">ILMUMISAASTA/Year:</label>
		<p class="phpw"><?=$error3?></p>
		<input type="text" name="ilmaasta" id="ilmaasta" maxlength="4" value="<?php echo $_POST["ilmaasta"];?>">
	<!-- seeria-->
		<label for="seeria">SEERIA/Series:</label>
		<input type="text" name="seeria" id="seeria"value="<?php echo $_POST["seeria"];?>">
	<!--köide-->
		<label for="koide">KIDE/Vol.:</label>
		<input type="text" name="koide" id="koide" value="<?php echo $_POST["koide"];?>">
	<!--vihik-->
		<label for="vihik">VIHIK/Part:</label>
		<input type="text" name="vihik" id="vihik"value="<?php echo $_POST["vihik"];?>">
	<label for="pnimi">&#042;NIMI/Name:</label>
	    <p class="phpw"><?=$error4?></p>
		<input type="text" name="pnimi" id="pnimi" value="<?php echo $_POST["pnimi"];?>" >
	<!--lugejakaart-->
		<label for="lkaart">&#042;Lugejakaardi number (nt y13983) või<br> y+ID-kaardi number (nt yA1163386)/<br>Library Card No.:</label>
		<p class="phpw"><?=$error5?></p>
		<input type="text" name="lkaart" id="lkaart" maxlength="11" value="<?php echo $_POST["lkaart"];?>">
	<!--pin-->
		<label for="pin">&#042;SALASÕNA/Password:</label>
		<p class="phpw"><?=$error6?></p>
		<input type="text" name="pin" id="pin">
		<p><button type="submit" name="telli" id="telli">Telli</button>
		<button type="submit" name="cancel" id="cancel" onClick="window.close()">Loobu</button></p>
		</form><!-- form  end-->
		
	
	</div>
</body>
</html>
