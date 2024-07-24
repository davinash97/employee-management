const signUP = document.getElementById("signUp");
const logIn = document.getElementById("logIn");
const position = document.getElementById("position");

const form = document.getElementsByTagName("form")[0];

const fname = document.getElementById("fname");
const lname = document.getElementById("lname");

signUP.addEventListener("click", () => {
	if (signUP.checked) {
		console.log("signUp");
		fname.disabled = false;
		lname.disabled = false;
		position.disabled = false;
		form.method = "POST";
	}
});

logIn.addEventListener("click", () => {
	if (logIn.checked) {
		fname.disabled = true;
		lname.disabled = true;
		position.disabled = true;
		form.method = "GET";
		console.log("logIn");
	}
});
