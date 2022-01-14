<?php
	include_once('conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Einia Blogs</title>
	<link rel="stylesheet" href="./dist/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
</head>
<body>
	<div class="container">
		<div class="logo">
			<a href="./">Einia Blogs</a>
		</div>
	<?php if(!isset($_GET['do']) OR (isset($_GET['do']) AND $_GET['do']=='search')){ ?>
		<form action='?do=search' method='POST' id='search'>
			<input type='text' name='q' id='q' value="<?php echo isset($_POST['q'])?$_POST['q']:'' ?>">
			<input type='submit' value='Search'>				
		</form>
		<div class="content_holder">
	<?php	}
	if(!isset($_GET['do'])){
		if(isset($_GET['show']) AND $_GET['show']=='all'){
			$articles = $db->query("SELECT * FROM articles ORDER BY article_timestamp DESC");
			echo '<h3>All articles</h3>';
		}elseif(isset($_GET['show']) AND $_GET['show']=='trending'){
			$articles = $db->query("SELECT * FROM articles ORDER BY views DESC LIMIT 25");
			echo '<h3>Most viewed articles</h3>';
		}else{
			$articles = $db->query("SELECT * FROM articles ORDER BY article_timestamp DESC LIMIT 25");
			echo '<h3>Latest articles</h3>';
		}
		echo "<ul class='categories'>";
		while($row = $articles->fetchArray()){
			$views = (isset($_GET['show']) AND $_GET['show']=='trending')?$row['views']." views":"on ".date('m/d/Y',$row['article_timestamp']);
			echo "<li><a href='?do=article&id={$row['id']}'>{$row['article_title']}</a> <em>(posted in <b><a href='?do=category&id={$row['cat_id']}' class='border link'>".getCatName($row['cat_id'])."</a></b> {$views})</em></li>";
		}
		echo "</ul>";
		}
		elseif(file_exists('pages/'.$_GET['do'].'.php')){
			include_once('pages/'.$_GET['do'].'.php');
		}
		if(!isset($_GET['do'])){ ?>
		<div class="actions">
			<ul>
				<?php
					if(isset($_GET['show']) AND $_GET['show']=='trending'){
						echo '<li><a href="?show=latest">Latest Articles</a></li>';
					}else{
						echo '<li><a href="?show=trending">Trending Articles</a></li>';
					}
				?>
				<li><a href="?show=all">All Articles</a></li>
				<li><a href="?do=article&action=new">Add Article</a></li>
				<li><a href="?do=category">Categories</a></li>
			</ul>
		</div>
	<?php } ?>
	</div>
	</div>
</body>
</html>