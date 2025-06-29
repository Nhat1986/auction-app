var xhr = false;
if (window.XMLHttpRequest) {
	xhr = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
	xhr = new ActiveXObject("Microsoft.XMLHTTP");
}
//test if browser support new standard that ok, if not, check wherethe it support old version and make a request

// access user inputs from customer page and pass them
// to custRegister.php
function testLogin() {
	
	var email = document.getElementById('email').value;
	var password = document.getElementById('password').value; 	
	
	xhr.open("GET", "login.php?email=" + encodeURIComponent(email) 
			+ "&password=" + password, true);
	xhr.onreadystatechange = testInput;
	xhr.send(null);
	
}

function testInput() {
	
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		if(xhr.responseText === "Success") {
			window.location = "./bidding.htm";
		} else {
			document.getElementById('msg').innerHTML = xhr.responseText;
		}
	}
	
}

function clearForm() {

document.getElementById('email').value="";
document.getElementById('password').value="";
document.getElementById('msg').innerHTML ="";
}
