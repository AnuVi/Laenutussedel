<?php
$conn = mysql_connect ('localhost', '','');   //ühenduse loomine andmebaasiga
mysql_select_db(''); //andmebaasi valimine
//välja printimata sedelite päring
  $query = 'SELECT Kviit, Autor, Pkiri, Ilmaasta, Seeria, Koide, Vihik, Perenimi, Lkaart, DATE_FORMAT(Aeg, "%T.%d.%m.%Y") as date  FROM Sedeltellimus WHERE Toodeldud=0';
  $result= mysql_query($query, $conn);
  if( mysql_num_rows($result) > 0)
  {
  $output="";
  //kui vajutatakse print nuppu - avab printimisakna
  $output .='<form method="post" action="tootaja.php" id="print" >';
  $output .= '<button type="submit" name="prindi" id="prindi" onClick="window.print();">Prindi</button>';
  
  $output .='</form>';
  $output  .= '<ul>';
	
	while($row = mysql_fetch_array($result)) 
	{
	$count=0;
	//vasak paan
    $output .= '<li>';
	$output .= '<div id="print_margin">';
	$output .= '<p>';
	$output .= 'Kohaviit: <span class="bold">'.$row['Kviit'].'</span>';
	$output .= '</p>';
	$output .= '<p>';
	$output .= 'Autor: <span class="bold">'.$row['Autor'].'</span>';
	$output .= '</p>';
	$output .= '<p>';
	$output .= 'Pealkiri: <span class="bold" style="height: 30px;">'.$row['Pkiri'].'</span>';
	$output .= '</p>';
	$output .= '<p>';
	$output .= 'Ilmumisaasta: <span class="bold">'.$row['Ilmaasta'].'</span>';
	$output .= '</p>';
	$output .= '<p>';
	$output .= 'Seeria: <span class="bold">'.$row['Seeria'].'</span> Köide: <span>'.$row['Koide'].'</span> Vihik: <span>'.$row['Vihik'].'</span>';
	$output .= '</p>';
	$output .= '<p>';
	$output .= 'Tellitud: <span class="bold">'.$row['date'].'</span>';
	$output .= '</p>';
	$output .= '<p>';
	$output .= 'Nimi: <span class="bold">'.$row['Perenimi'].'</span>';
	$output .= '</p>';
	$output .= '<p>';
	$output .= 'Lugejakaart: <span class="bold">'.$row['Lkaart'].'</span>';
	$output .= '</p>';
	$output .= '</div>';
	$output .= '</li>';
	//parem paan
	$output .= '<li class="hidden">';
	$output .= '<div id="print_margin">';
	$output .= '<p>';
	$output .= 'Kohaviit: <span class="bold">'.$row['Kviit'].'</span>';
	$output .= '</p>';
	$output .= '<p>';
	$output .= 'Autor: <span class="bold">'.$row['Autor'].'</span>';
	$output .= '</p>';
	$output .= '<p>';
	$output .= 'Pealkiri: <span class="bold">'.$row['Pkiri'].'</span>';
	$output .= '</p>';
	$output .= '<p>';
	$output .= 'Ilmumisaasta: <span class="bold">'.$row['Ilmaasta'].'</span>';
	$output .= '</p>';
	$output .= '<p>';
	$output .= 'Seeria: <span class="bold">'.$row['Seeria'].'</span> Köide: <span class="bold">'.$row['Koide'].'</span> Vihik: <span class="bold">'.$row['Vihik'].'</span>';
	$output .= '</p>';
	$output .= '<p>';
	$output .= 'Tellitud: <span class="bold">'.$row['date'].'</span>';
	$output .= '<p>';
	$output .= 'Perenimi: <span class="bold">'.$row['Perenimi'].'</span>';
	$output .= '</p>';
	$output .= '<p>';
	$output .= 'Lugejakaart: <span class="bold">'.$row['Lkaart'].'</span>';
	$output .= '</p>';
	$output .= '</p>';
	$output .= '</div>';
	$output .= '</li>';
			// varjatud vaher
			$output .='<li class="hidden_small">';
			$output .='<div style="page-break-after:always"></div>';
			$output .='</li>';
			$output .='<li class="hidden_small">';
			$output .='<div style="page-break-after:always"></div>';
			$output .='</li>';
		
	
    }
    $output .='</ul>';

	
	//kui vajutatakse prindi-nuppu, siis enam ei kuvata
	if ( isset($_POST['prindi']) ){
	$querypr= 'UPDATE Sedeltellimus SET Toodeldud=1 WHERE Toodeldud=0';
	$resultpr= mysql_query($querypr, $conn);
	} 
	if ( mysql_query($querypr, $conn))  
	       		 {    //kuvatakse leht prinditud
			header('Location: prinditud.php');
		      }
 }
 else{
  $output .= '<p>Uusi tellimusi pole</p>';
  } 
?> 
<!DOCTYPE html>
<html lang="et" dir="ltr">
<head>
<meta charset="utf-8">
<meta name="keywords" content="Tellimised sedelkataloogist">
<meta name="description" content="saali laenutussedel, room call slip,">
<title>Sedelkataloogist tellimused saali</title>
<link rel="stylesheet" type="text/css"  href="tootaja.css">
<link rel="stylesheet" type="text/css" media="print" href="print.css">
</head>
<body>
     
	 <?=$output?>
	 <a href="v_tellimused.php" title="Viimase 30 päeva tellimuste koondtabel"> Vaata viimase kuu aja tellimusi</a>
</body>
</html>