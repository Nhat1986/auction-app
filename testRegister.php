<?php
header('Content-Type: text/xml');
if(isset($_GET["fName"]) && isset($_GET["sName"]) && isset($_GET["email"]) && isset($_GET["password"]) && isset($_GET["cPassword"]) )
{

	$fName = $_GET["fName"];
	$sName = $_GET["sName"];
	$email = $_GET["email"];
	$password = $_GET["password"];
	$cPassword = $_GET["cPassword"];
	
	$errMsg = "";
	if (empty($fName)) {
			$errMsg .= "You must enter a first Name. <br />";
	} else if (!preg_match('/^[A-Za-z\s\-]+$/', $fName)) {
			$errMsg .= "first Name must be text. <br />";
	}
	
	if (empty($sName)) {
			$errMsg .= "You must enter a sur Name. <br />";
	} else if (!preg_match('/^[A-Za-z\s\-]+$/', $sName)) {
			$errMsg .= "Sur Name must be text. <br />";
	}
	
	if (empty($email)) {
			$errMsg .= "You must enter an email id. <br />";
	} else if (!preg_match('/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/', $email)) {
			$errMsg .= "Email is not in a valid format. <br />";
	}

	if (empty($password)) {
			$errMsg .= "You must enter a password. <br />";
	}
	if (empty($cPassword)) {
			$errMsg .= "You must enter a confirm password. <br />";
	}
	if($password != $cPassword) {
		$errMsg .= "Passwords don't match <br />";
	}
	
	$xmlfile = '../../data/customers.xml';
	
	$doc = new DomDocument();
	
	if (!file_exists($xmlfile)){ // if the xml file does not exist, create a root node $customers
		$customers = $doc->createElement('customers');
		$doc->appendChild($customers);
	}
	else { // load the xml file
		$doc->preserveWhiteSpace = FALSE; 
		$doc->load($xmlfile);  
	}
	
	$customers = $doc->getElementsByTagName('customers')->item(0);
	
	// check if customer with email already exists
	if(isEmailAlreadyRegistered($customers, $email)) {
		$errMsg .= "This email is already in use. <br />";
	}
	
	if ($errMsg != "") {
		echo $errMsg;
	}
	else {
		$biggestCustomerId = getBiggestCustomerId($customers);
		$customerId = $biggestCustomerId + 1;
		
		//create a customer node under customers node
		$customer = $doc->createElement('customer');
		$customers->appendChild($customer);
		
		// create id node
		$idNode = $doc->createElement('id');
		$customer->appendChild($idNode);
		$idNodeValue = $doc->createTextNode($customerId);
		$idNode->appendChild($idNodeValue);
		
		// create a fName node ....
		$FName = $doc->createElement('fName');
		$customer->appendChild($FName);
		$fNameValue = $doc->createTextNode($fName);
		$FName->appendChild($fNameValue);
		
		// create a sName node ....
		$SName = $doc->createElement('sName');
		$customer->appendChild($SName);
		$sNameValue = $doc->createTextNode($sName);
		$SName->appendChild($sNameValue);
		
		
		
		//create a Email node ....
		$Email = $doc->createElement('email');
		$customer->appendChild($Email);
		$emailValue = $doc->createTextNode($email);
		$Email->appendChild($emailValue);
		
		//create a pwd node ....
		$pwd = $doc->createElement('password');
		$customer->appendChild($pwd);
		$pwdValue = $doc->createTextNode($password);
		$pwd->appendChild($pwdValue);
		
		//save the xml file
		$doc->formatOutput = true;
		$doc->save($xmlfile);  
		
		// send email
		$message = "Dear " . $fName . ", welcome to use ShopOnline! Your customer id is " . $customerId .
					" and the password is " . $password;
		mail($email, "Welcome to ShopOnline!", $message, "From: registration@shoponline.com.au");
		
		echo "Dear $fName  $sName you have successfully registered, a confirm email sent to $email!";
	} 
}

function getBiggestCustomerId($customersNode) {
	$customerNodes = $customersNode->getElementsByTagName('customer');
	$biggestId = 0;
	foreach ($customerNodes as $customerNode) {
		$idNodes = $customerNode->getElementsByTagName("id");
		if($idNodes->length > 0) {
			$customerIdText = $idNodes->item(0)->nodeValue;
			$customerId = intval($customerIdText);
			if($customerId > $biggestId) {
				$biggestId = $customerId;
			}
		}
	}
	return $biggestId;
}

function isEmailAlreadyRegistered($customersNode, $email) {
	$customerNodes = $customersNode->getElementsByTagName('customer');
	foreach ($customerNodes as $customerNode) {
		$emailNodes = $customerNode->getElementsByTagName("email");
		if($emailNodes->length > 0) {
			$emailText = $emailNodes->item(0)->nodeValue;
			if($emailText == $email) {
				return true;
			}
		}
	}
	return false;
}
?>