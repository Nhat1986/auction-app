<?php

header("Content-Type:text/xml");
session_start();

if(!isset($_SESSION["customerId"])) {
	echo "You must be logged in";
} else {
	$ownerId = $_SESSION["customerId"];
	$xmlfile = '../../data/auction.xml';
	
	$doc = new DomDocument();
	
	if (!file_exists($xmlfile)){ // if the xml file does not exist, create a root node $customers
		echo "Cannot find auction data";
		return;
	} else { // load the xml file
		$doc->preserveWhiteSpace = FALSE; 
		$doc->load($xmlfile);  
	}
	
	$result = new DomDocument();
	$resultListings = $result->createElement('listings');
	$result->appendChild($resultListings);
	
	//create a listing node under listings node
	$listings = $doc->getElementsByTagName('listings')->item(0);
	$listingNodes = $listings->getElementsByTagName('listing');
	$listingsToRemove = array();
	foreach ($listingNodes as $listingNode) {
		$status = $listingNode->getAttribute('status');
		if($status == 'sold' || $status == 'failed') {
			$resultListingNode = $result->importNode($listingNode, true);
			$revenue = calculateRevenue($resultListingNode, $status);
			addRevenueToListing($result, $resultListingNode, $revenue);
			$resultListings->appendChild($resultListingNode);
			array_push($listingsToRemove, $listingNode);
		} 
	}
	
	// Remove from auction.xml
	foreach ($listingsToRemove as $listingNode) {
		$listings->removeChild($listingNode);
	}

	//save the xml file
	$doc->formatOutput = true;
	$doc->save($xmlfile);  
	echo $result->saveXML();
	
}

function calculateRevenue($listingNode, $status) {
	if($status == 'sold') {
		$bid = intval($listingNode->getElementsByTagName("bid")->item(0)->nodeValue);
		return $bid * 0.03;
	} else if ($status == 'failed') {
		$reservePrice = intval($listingNode->getElementsByTagName("rPrice")->item(0)->nodeValue);
		return $reservePrice * 0.01;
	}
	return 0;
}

function addRevenueToListing($doc, $listingNode, $revenue) {
	$revenueNode = $doc->createElement('revenue');
	$revenueNode->appendChild($doc->createTextNode($revenue));
	$listingNode->appendChild($revenueNode);
}

?>