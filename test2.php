<?php if(strpos($_SERVER["HTTP_USER_AGENT"],'redditbot')||true){
	  echo "<img src='http://www.exifeed.com/images/post/399/1.jpg'>";
	  exit;
	}else{
		header('Location:http://www.exifeed.com/post/id399');
		exit;
	}?>
<html>
	<body>
		<script>
			
		</script>
		<?=Yii::app()->user->id;?>
	</body>
</html>
