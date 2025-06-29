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
function testGet() {
	
	var fName = document.getElementById('fName').value;
	var sName = document.getElementById('sName').value;
	var email = document.getElementById('email').value;
	var password = document.getElementById('password').value;
	//change
	var cPassword = document.getElementById('cPassword').value; 
	
	xhr.open("GET", "testRegister.php?fName=" + fName + "&sName=" + sName + "&email=" + encodeURIComponent(email) + "&password=" +password + "&cPassword=" + cPassword + "&id=" + Number(new Date), true);
	//add "&cPassword" + cPassword
	xhr.onreadystatechange = testInput;
	xhr.send(null);
	
}

function testInput() {
	
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		document.getElementById('msg').innerHTML = xhr.responseText;
	}
	
}

function getXML() {

xhr.open ("GET", "getData.php", true);

xhr.onreadystatechange = testXML;

xhr.send(null);
}

function testXML() {
	
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		//var xmlDoc = xhr.responseXML;
		var xmlDoc = xhr.responseText;
		alert(xmlDoc);
	}
	
}

function deleteXML() {

xhr.open ("GET", "deleteFile.php", true);

xhr.onreadystatechange = function () {
if ((xhr.readyState == 4) && (xhr.status == 200)) {
		document.getElementById('msg').innerHTML = xhr.responseText;
	}
}
xhr.send(null);
}
function clearForm() {

document.getElementById("fName").value ="";
document.getElementById("sName").value ="";
document.getElementById('email').value="";
document.getElementById('password').value="";
document.getElementById('cPassword').value="";
//add cPassword
document.getElementById('msg').innerHTML ="";
}
