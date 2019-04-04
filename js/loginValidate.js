$(document).ready(load);


function load() {
	//$(".text-danger").hide();

	$("#submit").click(validate);
}

/*
* Validates all errors in the HTML form
* e - the click event, will be used to prevent the default action from happening
*/
function validate(e) {
	if(formHasErrors())
	{
		e.preventDefault();
	}
}

function formHasErrors() {
	errorFlag = false;

	if($("#username").val(""))
	{
		errorFlag = true;
		$(".username-error").show();
	}
}