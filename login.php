<?php
	session_start();
	
	if(isset($_GET["email"]) && isset($_GET["password"])) {
		$xmlfile = '../../data/customers.xml';
		if (!file_exists($xmlfile)){
			echo "Error: cannot find customer data";
		} else { 
			$doc = new DomDocument();
			$doc->preserveWhiteSpace = FALSE; 
			$doc->load($xmlfile);
			$customers = $doc->getElementsByTagName('customers')->item(0);
			$customerId = getCustomerIdByEmailAndPwd($customers, $_GET["email"], $_GET["password"]);
			if(is_null($customerId)) {
				echo "Error: invalid email or password";
			} else {
				$_SESSION["customerId"] = $customerId;
				$_SESSION["customerEmail"] = $_GET["email"];
				echo "Success";
			}
		}
	}
	
	
	function getCustomerIdByEmailAndPwd($customersNode, $email, $password) {
		$customerNodes = $customersNode->getElementsByTagName('customer');
		foreach ($customerNodes as $customerNode) {
			$idNodes = $customerNode->getElementsByTagName("id");
			$emailNodes = $customerNode->getElementsByTagName("email");
			$pwdNodes = $customerNode->getElementsByTagName("password");
			if($idNodes->length > 0 && $emailNodes->length > 0 && $pwdNodes->length > 0) {
				$idText = $idNodes->item(0)->nodeValue;
				$emailText = $emailNodes->item(0)->nodeValue;
				$pwdText = $pwdNodes->item(0)->nodeValue;
				if($emailText == $email && $pwdText == $password) {
					return intval($idText);
				}
			}
		}
		return null;
	}
?>