"use strict";

function toggleContent(id, fullText) {
	var content = document.getElementById(id);
	content.style.display == "block" ? content.style.display = "none" : content.style.display = "block";
}

//From w3schools for email validation
function validateRegister() {
	var x=document.forms["registerForm"]["username"].value;
  	var emailpattern = /([\w\-]+\@[\w\-]+\.[\w\-]+)/;
  	var errors = "";
  	if (!x.match(emailpattern)) {
  		 errors += "**Not a valid e-mail address**\n";
  	}
  	var y=document.forms["registerForm"]["first"].value;
  	var z=document.forms["registerForm"]["last"].value;
  	var namepatt = /^[a-zA-Z ]*$/;
  	if(!y.match(namepatt)) {
  		errors += "**Only letters and white space allowed in first name**\n";
  	}		
  	if(!z.match(namepatt)) {
  		errors += "**Only letters and white space allowed in last name**\n";
  	}
  	if(document.forms["registerForm"]["pwd"].value != document.forms["registerForm"]["pwd_confirm"].value) {
  		errors += "**Your passwords don't match**\n";
  	}
  	if(errors) {
  		alert("Please fix these error(s):\n" + errors);
  		return false;
  	}

}

//due to time constraints, I won't be implementing the calendar script. The idea of trying to use this over specifying a specific period time for auctions is unnecessary and overcomplicated. Also, I've already implemented an SQL query that uses hardcoded values for auction duration. 

