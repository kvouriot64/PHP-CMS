<?php
include 'includes/header.php';
?>

<h1>Add A New Restaurant</h1>
<form method="post">
	<fieldset>
		<label>Name:</label>
		<input name="name" id="name">
		<label>Phone Number:</label>
		<input type="number" name="phonenumber" id="phonenumber">
		<label>Address:</label>
		<input name="address" id="address">
		<label>Postal Code:</label>
		<input name="pcode" id="pcode">
	</fieldset>

	<label>Enter Your Company's About or Bio Message Here:</label>
	<textarea></textarea>
</form>
</body>
</html>