var xhr = false;
if (window.XMLHttpRequest) {
	xhr = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
	xhr = new ActiveXObject("Microsoft.XMLHTTP");
}

function checkLoggedIn() {
	console.log("Checking if logged in");
	xhr.open("GET", "session.php");
	xhr.onreadystatechange = handleLoggedInResponse;
	xhr.send(null);
}

function handleLoggedInResponse() {
	
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		let email = xhr.responseText;
		if(email != null && email != undefined && email != "") {
			let logoutLink = document.createElement('a');
			logoutLink.setAttribute('href', 'logout.php');
			logoutLink.innerHTML = 'Logout: ' + email;
			document.getElementsByTagName('nav')[0].appendChild(logoutLink);
		}
	}
	
}

window.onload = function() {
	checkLoggedIn();
}