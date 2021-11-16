<?php
$uus_fail=(isSet($_POST["uus_fail"])) && $_POST["uus_fail"];
// XML andmete salvestamine uusBaas.xml
if(isset($_POST['submit']) && $uus_fail && !empty($_POST['nimi'])){
    $xmlDoc = new DOMDocument("1.0","UTF-8");
    $xmlDoc->preserveWhiteSpace = false;
    $xmlDoc->formatOutput = true;

    $xml_root = $xmlDoc->createElement("tooted");
    $xmlDoc->appendChild($xml_root);

    $xml_toode = $xmlDoc->createElement("toode");
    $xmlDoc->appendChild($xml_toode);

    $xml_root->appendChild($xml_toode);

    $xml_toode->appendChild($xmlDoc->createElement('nimi', $_POST['nimi']));
    $xml_toode->appendChild($xmlDoc->createElement('hind',$_POST['hind']));
    $xml_toode->appendChild($xmlDoc->createElement('varv',$_POST['varv']));

    $lisad=$xml_toode->appendChild($xmlDoc->createElement('lisad'));
    $lisad->appendChild($xmlDoc->createElement('nimi', $_POST['linimi']));
    $lisad->appendChild($xmlDoc->createElement('suurus', $_POST['lisuurus']));

    $xmlDoc->save('tooted.xml');
}

// XML andmete täiendamine
if(isset($_POST['submit']) && !$uus_fail && !empty($_POST['nimi'])){
    $xmlDoc = new DOMDocument("1.0","UTF-8");
    $xmlDoc->preserveWhiteSpace = false;
    $xmlDoc->load('andmeteBaas.xml');
    $xmlDoc->formatOutput = true;

    $xml_root = $xmlDoc->documentElement;
    $xmlDoc->appendChild($xml_root);

    $xml_toode = $xmlDoc->createElement("toode");
    $xmlDoc->appendChild($xml_toode);

    $xml_root->appendChild($xml_toode);

    $xml_toode->appendChild($xmlDoc->createElement('nimi', $_POST['nimi']));
    $xml_toode->appendChild($xmlDoc->createElement('hind',$_POST['hind']));
    $xml_toode->appendChild($xmlDoc->createElement('varv',$_POST['varv']));
    $lisad=$xml_toode->appendChild($xmlDoc->createElement('lisad'));
    $lisad->appendChild($xmlDoc->createElement('nimi', $_POST['linimi']));
    $lisad->appendChild($xmlDoc->createElement('suurus', $_POST['lisuurus']));

    $xmlDoc->save('andmeteBaas.xml');

}
// Otsing toode nimi järgi
function searchByName($query){
    global $andmed;
    $result=array();
    foreach ($andmed->toode as $toode){
        if(substr(strtolower($toode->nimi),0,strlen($query)) == strtolower($query))
            array_push($result, $toode);
    }
    return $result;
}

$andmed=simplexml_load_file("andmeteBaas.xml");
?>

<!DOCTYPE HTML>
<html lang="et">
<head>
    <title>XML andmeteBaas.xml lugemine PHP abil</title>
    <link rel="stylesheet" href="style.css">
</head>
<div class="category-wrap">
    <ul>
        <li>
            <a href="lisamisvorm.html">Lisa uued andmed</a>
        </li>
        <li>
            <a href="toode_salvestaminevalikuga.php">Vaata andmed</a>
        </li>
    </ul>
</div>
<!--<h2>Otsing toodenimi järgi</h2>-->
<br>
<form action="?" method="post">
    <table>
        <tr>
            <th>
                <div class="aks-input-row">
                    <input type="text" class="aks-input" id="otsing" name="otsing" placeholder="Toode nimi">
                </div>
            </th>
            <th>
                <div class="aks-input-row">
                    <input type="submit" class="aks-input" value="Otsi">
                </div>
            </th>
        </tr>
    </table>
</form>
<ul>
    <?php
    if(!empty($_POST["otsing"])){
        $result=searchByName($_POST["otsing"]);
        foreach ($result as $toode){
            echo "<li class='otsing'>";
            echo $toode->nimi. ", ". $toode->hind;
            echo "</li>";
        }
    }
    ?>
</ul>
<body>
<?php
//lisamisvorm html failist
//include('lisamisvorm.html');
?>
<h2>Andmed andmeteBaas.xml failist</h2>
<table class="andmete_tabel">
    <tr>
        <th>Toodenimi</th>
        <th>Hind</th>
        <th>Värv</th>
        <th>Lisade nimi</th>
        <th>Lisade suurus</th>
    </tr>
    <?php
    foreach ($andmed->toode as $toode){
        echo "<tr>";
        echo "<td>".($toode->nimi)."</td>";
        echo "<td>".($toode->hind)."</td>";
        echo "<td>".($toode->varv)."</td>";
        echo "<td>".($toode->lisad->nimi)."</td>";
        echo "<td>".($toode->lisad->suurus)."</td>";
        echo "</tr>";
    }
    ?>
</table>
<!--<h2>RSS uudised</h2>-->
<ul>
<?php
/*$feed=simplexml_load_file('https://www.postimees.ee/rss');
$linkide_arv=10;
$loendur=1;
foreach ($feed->channel->item as $item){
    if($loendur<=$linkide_arv){
        echo "<li>";
        echo "<a href='$item->link' target='_blank'>$item->title</a>";
        echo "</li>";
        $loendur++;
    }
}*/
?>
</ul>
</body>
</html>