<?php
$andmed=simplexml_load_file("andmeteBaas.xml");

//данные добавляются в уже имеющийся xml файл
if(isSet($_POST['submit'])) {

    $toodenimi = $_POST['nimi'];
    $toodehind = $_POST['hind'];
    $toodevarv = $_POST['varv'];
    $lisadenimi = $_POST['linimi'];
    $lisadesuurus = $_POST['lisuurus'];

    $xml_tooded = $andmed->addChild('toode');
    $xml_tooded->addChild('nimi', $toodenimi);
    $xml_tooded->addChild('hind', $toodehind);
    $xml_tooded->addChild('varv', $toodevarv);

    $lisad = $xml_tooded->addChild('lisad');
    $lisad->addChild('nimi', $lisadenimi);
    $lisad->addChild('suurus', $lisadesuurus);

    $xmlDoc = new DOMDocument("1.0", "UTF-8");
    $xmlDoc->loadXML($andmed->asXML(), LIBXML_NOBLANKS);
    $xmlDoc->formatOutput = true;
    $xmlDoc->preserveWhiteSpace = false;
    $xmlDoc->save('andmeteBaas.xml');
    header("refresh: 0;");
}
//при нажатии на конпку создается новый xml файл tooted.xml с данными, которые прописаны в коде
if(isSet($_POST['submit'])) {
    /*создаем основную структуру xml документа
    <tooded>
        <toode>
            <lisad>
            </lisad>
        </toode>
    </tooded>
    */

    $xmlDoc = new DOMDocument("1.0","UTF-8");
    $xmlDoc->formatOutput = true;
    $xmlDoc->preserveWhiteSpace = false;

    $xml_root = $xmlDoc->createElement("tooded");
    $xmlDoc->appendChild($xml_root);

    $xml_toode = $xmlDoc->createElement("toode");
    $xmlDoc->appendChild($xml_toode);
    $xml_lisad=$xmlDoc->createElement("lisad");

    $xml_root->appendChild($xml_toode);
    $xml_root->appendChild($xml_lisad);
    /*
     * Добавляем элементы к основный структуре и заполняем их данными
     * <nimi></nimi>
     * <hind></hind>
     * <varv></varv>
     * <lisad>
     *      <nimi></nimi>
     *      <suurus></suurus>
     * </lisad> */
    $xml_toode->appendChild($xmlDoc->createElement('nimi','Lamp'));
    $xml_toode->appendChild($xmlDoc->createElement('hind','55'));
    $xml_toode->appendChild($xmlDoc->createElement('varv','punane'));
    $xml_lisad->appendChild($xmlDoc->createElement('nimi','puudub'));
    $xml_lisad->appendChild($xmlDoc->createElement('suurus','puudub'));
    $xmlDoc->save('tooted.xml');
}
//данные из формы хаписываются в xml файл tooted.xml
if(isset($_POST['submit'])){
    $xmlDoc = new DOMDocument("1.0","UTF-8");
    $xmlDoc->preserveWhiteSpace = false;
    $xmlDoc->load('tooted.xml');
    $xmlDoc->formatOutput = true;

    $xml_root = $xmlDoc->documentElement;
    $xmlDoc->appendChild($xml_root);

    $xml_toode = $xmlDoc->createElement("toode");
    $xmlDoc->appendChild($xml_toode);

    $xml_root->appendChild($xml_toode);

    unset($_POST['submit']);
    foreach($_POST as $voti=>$vaartus){
        $kirje = $xmlDoc->createElement($voti,$vaartus);
        $xml_toode->appendChild($kirje);
    }
    $xmlDoc->save('tooted.xml');
}
?>
<!DOCTYPE HTML>
<html lang="et">
    <head>
        <title>XML andmete lugemine PHP abil</title>
    </head>
<body>
<h1>XML andmete lugemine PHP abil</h1>
<h3>Esimese toode nimi:</h3>
<?php
echo $andmed->toode[0]->nimi;
?>
<br><br>
<table>
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
<h1>Vormist saadud andmete lisamine XML faili</h1>
<form method="post" action="">
    <input type="text" id="nimi" name="nimi" placeholder="Toode nimi">
    <br><br>
    <input type="text" id="hind" name="hind" placeholder="Toode hind">
    <br><br>
    <input type="text" id="varv" name="varv" placeholder="Toode värv">
    <br><br>
    <input type="text" id="linimi" name="linimi" placeholder="Lisade nimi"
    <br><br>
    <input type="text" id="lisuurus" name="lisuurus" placeholder="Lisade suurus">
    <br><br>
    <input type="submit" id="submit" name="submit" value="Sisesta">
</form>
</body>
</html>
