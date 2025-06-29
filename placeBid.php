<?php

session_start();

if(!isset($_SESSION["customerId"])) {
	echo "You must be logged in";
} elseif(isset($_GET["bidPrice"]) && isset($_GET["itemNum"]) )
{
	$ownerId = $_SESSION["customerId"];
	$itemNum = $_GET["itemNum"];
	$bidPrice = $_GET["bidPrice"];
	$errMsg = "";
	if (empty($itemNum) || empty($bidPrice) || !is_numeric($itemNum) || !is_numeric($bidPrice)) {
		$errMsg = "Sorry, your bid is not valid.";
	}
	
	if ($errMsg != "") {
		echo $errMsg;
	}
	else {
	
		$xmlfile = '../../data/auction.xml';
		
		$doc = new DomDocument();
		
		if (!file_exists($xmlfile)){ // if the xml file does not exist, create a root node $customers
			$listings = $doc->createElement('listings');
			$doc->appendChild($listings);
		} else { // load the xml file
			$doc->preserveWhiteSpace = FALSE; 
			$doc->load($xmlfile);  
		}
		
		$listings = $doc->getElementsByTagName('listings')->item(0);
		$listing = findListing($listings, $itemNum);
		
		if(is_null($listing)) {
			echo "Could not find listing.";
			return;
		}
		
		if($listing->getAttribute('status') != "in_progress") {
			echo "Listing is not in progress.";
			return;
		}
		
		if($bidPrice <= getBidPrice($listing)) {
			echo "Bid price is not greater than current bid price.";
			return;
		}
		
		setBid($listing, $ownerId, $bidPrice);
		
		//save the xml file
		$doc->formatOutput = true;
		$doc->save($xmlfile);  
		echo "Thank you! Your bid is recorded in ShopOnline.";
	} 
}

function findListing($listingsNode, $targetItemNum) {
	$listingNodes = $listingsNode->getElementsByTagName('listing');
	foreach ($listingNodes as $listingNode) {
		$itemNumNodes = $listingNode->getElementsByTagName("item_num");
		if($itemNumNodes->length > 0) {
			$itemNumText = $itemNumNodes->item(0)->nodeValue;
			$itemNum = intval($itemNumText);
			if($itemNum == $targetItemNum) {
				return $listingNode;
			}
		}
	}
	return null;
}

function getBidPrice($listingNode) {
	$bidNodes = $listingNode->getElementsByTagName("bid");
	if($bidNodes->length > 0) {
		return intval($bidNodes->item(0)->nodeValue);
	}
	return 0;
}

function setBid($listingNode, $bidderId, $bidPrice) {
	$bidNodes = $listingNode->getElementsByTagName("bid");
	if($bidNodes->length > 0) {
		$bidNodes->item(0)->nodeValue = $bidPrice;
	}
	$bidderIdNodes = $listingNode->getElementsByTagName("bidderId");
	if($bidderIdNodes->length > 0) {
		$bidderIdNodes->item(0)->nodeValue = $bidderId;
	}
}
?>