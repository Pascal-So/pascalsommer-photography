<?php 
	include_once('app/photo.php');
	include_once('app/comment.php');

	$id = -1;
	if(isset($_GET["id"])){
		$id = $_GET["id"];
	}else{
		$id = get_newest_photo_id();
	}

	$prev_id = get_previous_photo_id($id);
	$next_id = get_next_photo_id($id);

	$pic = get_photo($id);
	// check if photo with this id exists
	if($pic == -1){
		die("Photo not available");
		// maybe add a redirect later on to a nicer error page.
	}

	$pic["nr_comments"] = get_nr_comments_by_photo($id);

	$comments = get_comments_array_by_photo($id);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Photo</title>
	<link rel="stylesheet" type="text/css" href="base.css">
	<link rel="stylesheet" type="text/css" href="photodetail.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

	<script type="text/javascript" src="main.js"></script>

	<script type="text/javascript">
		$(function(){
			scrollToLink($("#bt_comments"), $("#comments"));
		});
	</script>
</head>
<body class="alignCenter">

<?php generate_pic_html($pic, $prev_id, $next_id); ?>

<div id="comments" class="comments ma0 mt4">
	<div id="comments_content">
	<?php 
	foreach ($comments as $comment) {
		generate_comment_html($comment);
	}
	?>
	</div>
	<div class="card ma1">
		<h3 class="f5 ma0">New Comment</h3>
		<form action="comments.php" method="post" onsubmit="return checkcomment()" class="ma0 mt2">
			<label for="tx-name" class="f5">Name: </label><br>
			<input type="text" id="tx-name" name="name" class="ma0 mt1 mb1 textinput"><br>
			<br>
			<label for="tx-comment" class="f5">Comment: </label><br>
			<textarea id="tx-comment" name="comment" class="ma0 mt1 mb1 textinput"></textarea><br>
			<br>
			<input type="submit" class="f5" value="Send"><br>
		</form>
	</div><br>
	<br>
</div>

</body>
</html>