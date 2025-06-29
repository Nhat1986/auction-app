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
function createListing() {
	
	var item = document.getElementById('item').value;
	var cate = document.getElementById('cate').value;
	var desc = document.getElementById('desc').value;
	var sPrice = document.getElementById('sPrice').value;
	var rPrice = document.getElementById('rPrice').value;
	var bPrice = document.getElementById('bPrice').value;
	var day = document.getElementById('day').value;
	var hour = document.getElementById('hour').value; 
	var minute = document.getElementById('minute').value; 	
	
	xhr.open("GET", "listing.php?item=" + item + "&cate=" + cate + "&desc=" + desc + "&sPrice=" + sPrice 
				+ "&rPrice=" + rPrice + "&bPrice=" + bPrice + "&day=" + day + "&hour=" + hour + "&minute=" + minute, true);
	xhr.onreadystatechange = handleResponse;
	xhr.send(null);
	
}

function handleResponse() {
	
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		if(xhr.responseText === 'Success') {
			window.location = './bidding.htm';
		} else {
			document.getElementById('msg').innerHTML = xhr.responseText;
		}
	}
	
}

function clearForm() {

document.getElementById("item").value ="";
document.getElementById("cate").value ="camera";
document.getElementById('desc').value="";
document.getElementById('sPrice').value="";
document.getElementById('rPrice').value="";
document.getElementById('bPrice').value="";
document.getElementById('day').value="day";
document.getElementById('hour').value="hour";
document.getElementById('minute').value="minute";
document.getElementById('msg').innerHTML ="";
}
