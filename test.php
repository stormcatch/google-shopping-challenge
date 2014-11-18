<?php
/**
 * Created by PhpStorm.
 * User: Zoran
 * Date: 11/13/14
 * Time: 1:06 PM
 */


require_once 'GoogleShopping.php';

$ean = '8806085553941';


// get prices array by ean
$crawler = new GoogleShopping;
$prices = $crawler->getPrices($ean);

echo "<PRE>", json_encode($prices, JSON_PRETTY_PRINT), "</PRE>";

