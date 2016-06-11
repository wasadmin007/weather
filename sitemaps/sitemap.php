<?php

require_once('../config.php');
$di = DIR_APPLICATION;
$rootPath = str_replace("catalog/", "", $di);

function getUrlFriendlyString($str) {
    // convert spaces to '-', remove characters that are not alphanumeric
    // or a '-', combine multiple dashes (i.e., '---') into one dash '-'.
    $str = preg_replace("/[-]+/", "-", preg_replace("/[^a-z0-9-]/", "", strtolower(str_replace(" ", "-", $str))));
    return $str;
}

//put this into your robots.txt: 
// Sitemap: http://www.YOURSITE.COM/sitemaps/sitemapindex.xml

$dbhandle = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
if (!$dbhandle) {
    die('Could not connect: ' . mysql_error());
}

$selected = mysql_select_db(DB_DATABASE, $dbhandle)
        or die("Could not select examples");

$result = mysql_query("SELECT
    " . DB_PREFIX . "product.product_id as id,
    model,
    " . DB_PREFIX . "product_description.name as title,
    category_id as category,
    " . DB_PREFIX . "manufacturer.name as brand
        FROM 
    " . DB_PREFIX . "product
        inner join
    " . DB_PREFIX . "product_description ON " . DB_PREFIX . "product.product_id = " . DB_PREFIX . "product_description.product_id
        inner join
    " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product.product_id = " . DB_PREFIX . "product_to_category.product_id
        inner join
    " . DB_PREFIX . "manufacturer ON " . DB_PREFIX . "product.manufacturer_id = " . DB_PREFIX . "manufacturer.manufacturer_id ;");

//create the xml document
$xmlDoc = new DOMDocument();

$sitemaps = array(); //array of sitemaps
//create the root element
$root = $xmlDoc->appendChild(
        $xmlDoc->createElement("urlset"));

$tutTag = $root->appendChild(
                $xmlDoc->createAttribute("xmlns"))->appendChild(
        $xmlDoc->createTextNode("http://www.google.com/schemas/sitemap/0.9"));

$counter = 1;
while ($row = mysql_fetch_array($result)) {
    //on each iteration we create new sitemap
    if ($counter % 30000 == 0) {

        $sitemaps[] = $xmlDoc;
        $xmlDoc = null;

        $xmlDoc = new DOMDocument();
        $root = $xmlDoc->appendChild(
                $xmlDoc->createElement("urlset"));

        $tutTag = $root->appendChild(
                        $xmlDoc->createAttribute("xmlns"))->appendChild(
                $xmlDoc->createTextNode("http://www.google.com/schemas/sitemap/0.9"));
    }

    $final_url = HTTP_SERVER . "index.php?route=product/product&path=" . $row{'category'} . "&product_id=" . $row{'id'} .
            "&pdescription=" . getUrlFriendlyString($row{'brand'}) . "_" . getUrlFriendlyString($row{'model'}) . "_" . getUrlFriendlyString($row{'title'});

    $tutTag = $root->appendChild(
            $xmlDoc->createElement("url"));

    $tutTag->appendChild(
            $xmlDoc->createElement("loc", htmlentities($final_url)));

    $tutTag->appendChild(
            $xmlDoc->createElement("priority", "0.5"));
    $counter++;
}
//we add our last sitemap into array
$sitemaps[] = $xmlDoc;

//Now we create a sitemapindex.xml file
$xmlDocIndex = new DOMDocument();
$rootIndex = $xmlDocIndex->appendChild(
        $xmlDocIndex->createElement("sitemapindex"));

$tutTag = $rootIndex->appendChild(
                $xmlDocIndex->createAttribute("xmlns"))->appendChild(
        $xmlDocIndex->createTextNode("http://www.google.com/schemas/sitemap/0.84"));

$sitemapsCount = 1;
foreach ($sitemaps as $value) {
    $fname = "sitemaps/sitemap_" . $sitemapsCount . ".xml.gz";

    $tutTag = $rootIndex->appendChild(
            $xmlDocIndex->createElement("sitemap"));
    $tutTag->appendChild(
            $xmlDocIndex->createElement("loc", HTTP_SERVER . $fname));
    $tutTag->appendChild(
            $xmlDocIndex->createElement("lastmod", date('Y-m-d')));
    $value->formatOutput = true;
    $theOutput = gzencode($value->saveXML(), 9);

    //create archive with the sitemap
    file_put_contents($rootPath . $fname, $theOutput);

    $sitemapsCount++;
}

//now we save the sitemapindex.xml
$xmlDocIndex->formatOutput = true;
$xmlDocIndex->save($rootPath . "sitemaps/sitemapindex.xml");
echo "<a href=sitemapindex.xml>View Sitemap Index</a>";

mysql_close($dbhandle);
?>
