<?php
$conn = mysql_connect ('localhost', '','');   //ühenduse loomine andmebaasiga
mysql_select_db('test'); 
  //
  $query = 'SELECT Kviit,Perenimi, Autor, Pkiri, Ilmaasta, Seeria, Koide, Vihik, Toodeldud AS Toodeldud, CASE Toodeldud WHEN 0 THEN "Ei" ELSE "Jah" END AS Toodeldud,Lkaart, DATE_FORMAT(Aeg, "%d.%m.%Y.%T") as date FROM Sedeltellimus WHERE Aeg >= CURDATE() - INTERVAL 31 DAY AND left(curdate(),4)=left(Aeg,4) ORDER BY date DESC';
  $result= mysql_query($query, $conn);
  if( mysql_num_rows($result) > 0)
  { 
	$output = '<table>';
	$output .= '<caption><h1>Siin lehel kajastuvad viimase 30 päeva tellimused sedelkataloogist</h1></caption>';
	$output .= '<tr>';
	$output .= '<th>Kuupäev</th>';
	$output .= '<th>Nimi</th>';
	$output .= '<th>Lugejakaart</th>';
	$output .= '<th>Kohaviit</th>';
	$output .= '<th>Autor</th>';
	$output .= '<th>Pealkiri</th>';
	$output .= '<th>Ilmumisaasta</th>';
	$output .= '<th>Seeria/Köide/Vihik</th>';
	$output .= '<th>Prinditud</th>';
	$output .= '</tr>';
	while($row = mysql_fetch_array($result)) 
	{
	
	$output .= '<tr>';
	$output .= '<td>'.$row['date'].'</td>';
	$output .= '<td>'.$row['Perenimi'].'</td>';
	$output .= '<td>'.$row['Lkaart'].'</td>';
	$output .= '<td>'.$row['Kviit'].'</td>';
	$output .= '<td>'.$row['Autor'].'</td>';
	$output .= '<td class="laiem">'.$row['Pkiri'].'</td>';
	$output .= '<td>'.$row['Ilmaasta'].'</td>';
	$output .= '<td>'.$row['Seeria'].$row['Koide'].$row['Vihik'].'</td>';
	$output .= '<td>'.$row['Toodeldud'].'</td>';
	$output .= '</tr>';
	
	}
	
  }
  else{
  $output = '<p>Tellimusi pole</p>';
  }
//kustutab vanemad kui 50-päevased tellimused, mis printitud
$query2= 'DELETE FROM Sedeltellimus WHERE  Aeg <= CURDATE() - INTERVAL 50 DAY AND Toodeldud=1';
$result2= mysql_query($query2, $conn);

   
?>





<!DOCTYPE html>
<html lang="et" dir="ltr">
<head>
<meta charset="utf-8">
<meta name="keywords" content="Sedelkatalloogist viimase 30 päeva telllimused" />
<meta name="description" content="sedelkataloogi laenutussedel, room call slip," />
<title>Sedelkataloogist viimase kuu tellimused</title>
<link rel="stylesheet" type="text/css" href="v_tellimused.css">
</head>
<body> 
	 <?=$output?>
</body>
</html>