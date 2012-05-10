<html>
<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
 $(document).ready(function() {
 	 $("#responsecontainer").load("response.php");
   var refreshId = setInterval(function() {
      $("#responsecontainer").load('response.php?randval='+ Math.random());
   }, 9000);
   $.ajaxSetup({ cache: false });
});
</script>
</head>
<body>

<div id="responsecontainer">
</div>
</body>