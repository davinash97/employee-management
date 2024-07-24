const form = document.getElementsByTagName("form")[0];

const submit = document.getElementsByTagName("button")[0];
const reset = document.getElementsByTagName("button")[1];
const deleteBtn = document.getElementsByTagName("button")[2];
const logOutBtn = document.getElementsByTagName("button")[3];

reset.addEventListener("click", () => {
	form.reset();
});

submit.addEventListener("click", () => {
	form.submit();
});

deleteBtn.addEventListener("click", () => {
	form.method = "delete";
	alert("Are you sure you want to delete your account?");
	form.submit();
});

logOutBtn.addEventListener("click", () => {
	window.location.href = "profile.php";
});
