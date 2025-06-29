<?php

session_start();

if(!isset($_SESSION["customerId"])) {
	echo "You must be logged in";
} elseif(isset($_GET["item"]) && isset($_GET["cate"]) && isset($_GET["desc"]) && isset($_GET["sPrice"]) && isset($_GET["rPrice"])
			&& isset($_GET["bPrice"]) && isset($_GET["day"]) && isset($_GET["hour"]) && isset($_GET["minute"]))
{
	$ownerId = $_SESSION["customerId"];
	$item = $_GET["item"];
	$cate = $_GET["cate"];
	$desc = $_GET["desc"];
	$sPrice = $_GET["sPrice"];
	$rPrice = $_GET["rPrice"];
	$bPrice = $_GET["bPrice"];
	$day = $_GET["day"];
	$hour = $_GET["hour"];
	$minute = $_GET["minute"]; 
	$startDate = explode("T", date("c"))[0];
	$startTime = explode("T", date("c"))[1];
	$errMsg = "";
	if (empty($item)) {
			$errMsg .= "You must enter an item. <br />";
	}
	
	if (empty($cate)) {
			$errMsg .= "You must enter a category. <br />";
	}
	
	if (empty($desc)) {
			$errMsg .= "You must enter a description. <br />";
	}

	if (empty($sPrice)) {
			$errMsg .= "You must enter a Start Price. <br />";
	}else if (!is_numeric($sPrice)) {
			$errMsg .= "Start Price must be a number. <br />";
	}
	
	if (empty($rPrice)) {
			$errMsg .= "You must enter a Reserve Price. <br />";
	}else if (!is_numeric($rPrice)) {
			$errMsg .= "Reserve Price must be a number. <br />";
	}
	
	if (empty($bPrice)) {
			$errMsg .= "You must enter a Buy-it-now Price. <br />";
	}else if (!is_numeric($bPrice)) {
			$errMsg .= "Buy it now Price must be a number. <br />";
	}
	
	if (!is_numeric($day)) {
			$errMsg .= "You must enter a number for day. <br />";
	}
	if (!is_numeric($hour)) {
			$errMsg .= "You must enter a number for hour. <br />";
	}
	if (!is_numeric($minute)) {
			$errMsg .= "You must enter a number for minute. <br />";
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
		}
		else { // load the xml file
			$doc->preserveWhiteSpace = FALSE; 
			$doc->load($xmlfile);  
		}
		
		//create a listing node under listings node
		$listings = $doc->getElementsByTagName('listings')->item(0);
		$listing = $doc->createElement('listing');
		$statusAttribute = $doc->createAttribute('status');
		$statusAttribute->value = 'in_progress';
		$listing->appendChild($statusAttribute);
		$listings->appendChild($listing);
		
		$itemNum = getBiggestItemNum($listings) + 1;
	
		appendNode($doc, $listing, 'item_num', $itemNum);
		appendNode($doc, $listing, 'ownerId', $ownerId);
		appendNode($doc, $listing, 'item', $item);
		appendNode($doc, $listing, 'category', $cate);
		appendNode($doc, $listing, 'description', $desc);
		appendNode($doc, $listing, 'sPrice', $sPrice);
		appendNode($doc, $listing, 'rPrice', $rPrice);
		appendNode($doc, $listing, 'bPrice', $bPrice);
		appendNode($doc, $listing, 'day', $day);
		appendNode($doc, $listing, 'hour', $hour);
		appendNode($doc, $listing, 'minute', $minute);
		appendNode($doc, $listing, 'startDate', $startDate);
		appendNode($doc, $listing, 'startTime', $startTime);
		appendNode($doc, $listing, 'bid', $sPrice);
		appendNode($doc, $listing, 'bidderId', "");
		
		//save the xml file
		$doc->formatOutput = true;
		$doc->save($xmlfile);  
		echo "Thank you! Your item has been listed in ShopOnline. The item number is "
			. $itemNum . ", and the bidding starts now: " . explode("+", $startTime)[0] . " on " . $startDate . ".";
	} 
}

function appendNode($doc, $parentNode, $nodeName, $nodeValue) {
	$nodeElement = $doc->createElement($nodeName);
	$textNode = $doc->createTextNode($nodeValue);
	$nodeElement->appendChild($textNode);
	$parentNode->appendChild($nodeElement);
}

function getBiggestItemNum($listingsNode) {
	$listingNodes = $listingsNode->getElementsByTagName('listing');
	$biggestNum = 0;
	foreach ($listingNodes as $listingNode) {
		$itemNumNodes = $listingNode->getElementsByTagName("item_num");
		if($itemNumNodes->length > 0) {
			$itemNumText = $itemNumNodes->item(0)->nodeValue;
			$itemNum = intval($itemNumText);
			if($itemNum > $biggestNum) {
				$biggestNum = $itemNum;
			}
		}
	}
	return $biggestNum;
}
?>