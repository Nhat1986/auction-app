<?php

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
	
	//create a listing node under listings node
	$listings = $doc->getElementsByTagName('listings')->item(0);
	$listingNodes = $listings->getElementsByTagName('listing');
	foreach ($listingNodes as $listingNode) {
		processListing($listingNode);
	}

	//save the xml file
	$doc->formatOutput = true;
	$doc->save($xmlfile);  
	echo "Processing complete.";
	
}

function processListing($listingNode) {
	if($listingNode->getAttribute('status') == "in_progress") {
		$currentTime = time();
		$endTime = getEndTime($listingNode);
		if($endTime < $currentTime) {
			$bidNodes = $listingNode->getElementsByTagName("bid");
			$reservePriceNodes = $listingNode->getElementsByTagName("rPrice");
			if($reservePriceNodes->length > 0 && $reservePriceNodes->length > 0) {
				$bidPrice = intval($bidNodes->item(0)->nodeValue);
				$reservePrice = intval($reservePriceNodes->item(0)->nodeValue);
				
				if ($bidPrice >= $reservePrice) {
					$listingNode->setAttribute('status', "sold");
				} else {
					$listingNode->setAttribute('status', "failed");
				}
			}
		}
	}
}

function getEndTime($listingNode) {
	$startDate = $listingNode->getElementsByTagName("startDate")->item(0)->nodeValue;
	$startTime = $listingNode->getElementsByTagName("startTime")->item(0)->nodeValue;
	$day = intval($listingNode->getElementsByTagName("day")->item(0)->nodeValue);
	$hour = intval($listingNode->getElementsByTagName("hour")->item(0)->nodeValue);
	$minute = intval($listingNode->getElementsByTagName("minute")->item(0)->nodeValue);
	
	$endTime = strtotime($startDate."T".$startTime);
	$endTime += $minute * 60;
	$endTime += $hour * 60 * 60;
	$endTime += $day * 60 * 60 * 24;
	return $endTime;
}


?>