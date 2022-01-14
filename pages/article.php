<?php 
if(!isset($_GET['action']) AND isset($_GET['id'])){
	$article = $db->query("SELECT * FROM articles WHERE id = {$_GET['id']}")->fetchArray();
	$newViewCount = $article['views'] + 1;
	$updateViews = $db->query("UPDATE articles SET views = {$newViewCount} WHERE id = {$_GET['id']}");
?>
	<div class="article_content">
		<div class="title">
			<span id="title"><?php echo $article['article_title'] ?></span>
			<span id="timestamp">Posted on <?php echo date("m/d/Y h:i:s A", $article['article_timestamp']) ?></span>
		</div>
		<div class="text" style="margin-block:1.25rem;"><?php echo parseMarkUp(nl2br($article['article_content'])) ?></div>
		<div class="category"><span style='color:var(--head-color);'>Category:</span> <a class="border" style="color:var(--link-color);" href="?do=category&id=<?php echo $article['cat_id']?>"><?php echo getCatName($article['cat_id']) ?></a></div>
		<div class="actions">
			<center><small><i>This article was viewed <?php echo $newViewCount.($newViewCount==1?' time':'times') ?></i></small></center>
			<ul>
				<li><a title="Go back" href="./" class="border">Go Back</a></li>
				<li><span title="Delete this article" onclick="<?php echo "deleteArticle('{$article['id']}','{$article['article_title']}')" ?>" class="link border" style="color: crimson">Delete</a></li>
				<li><a title="Edit this article" href="?do=article&action=edit&id=<?php echo $article['id'] ?>" class="border">Edit</a></li>
			</ul>
		</div>
	</div>
<?php	}
elseif($_GET['action']=='delete'){
	if(isset($_GET['id'])){
		$catId = $_GET['id'];
		$query = $db->prepare('DELETE FROM articles where id = :catId');
		$query->bindparam(':catId', $catId);
		$query->execute();
		header('location: ./');
	}
}
elseif($_GET['action']=='add'){
		$title = $_POST['title'];
		$content = $_POST['content'];
		$category = $_POST['category'];
		$curr_timestamp = time();
	if(!isset($_POST['article_id'])){
		$query = $db->prepare("INSERT INTO articles (article_title, article_content, article_timestamp, cat_id, views) VALUES (:title, :content, '{$curr_timestamp}', :category, 0)");
	}else{
		$query = $db->prepare("UPDATE articles SET article_title = :title, article_content = :content, cat_id =:category WHERE id = '{$_POST['article_id']}'");		
	}
		$query->bindparam(':title', $title);
		$query->bindparam(':content', $content);
		$query->bindparam(':category', $category);
		$query->execute();
		header('location: '.(isset($_POST['article_id'])?"?do=article&id={$_POST['article_id']}":'./'));
}
elseif($_GET['action']=='new' OR $_GET['action']=='edit'){
	$query = $db->query('SELECT COUNT(*) as count FROM category')->fetchArray();
	if($query['count']==0){
?>
	<div class="warning_text">
		<p>No categories found</p>
		<p><a href="?do=category&action=new">Create a category</a></p>
	</div>

<?php }else{ ?>
	<form id="add_new" class="content" action="?do=article&action=add" method="POST">
	<div class="header">
		Add New Article
	</div>
<?php	if($_GET['action']=='edit'){
		$article = $db->query("SELECT * FROM articles WHERE id = {$_GET['id']}")->fetchArray();
		echo "<input type=\"hidden\" name=\"article_id\" value=\"{$article['id']}\">";
	}
?>
	<div class="title">
		<label for="">Title</label>
		<input type="text" name="title" id="title" required value="<?php echo isset($article['article_title'])?$article['article_title']:''; ?>">
	</div>
	<div class="body">
		<label for="">Content</label>
		<textarea name="content" id="content" cols="30" rows="10" required><?php echo isset($article['article_content'])?$article['article_content']:''; ?></textarea>
	</div>
	<div class="category">
		<label for="">Category <a href="?do=category&action=new"><i class="fas fa-plus-circle"></i></a></label>
		<select name="category" id="category" required>
			<?php
				$cat = $db->query("SELECT * FROM category ORDER BY category_title ASC");
					while($row = $cat->fetchArray()){
						$selected = ($row['id']==(isset($article['cat_id'])?$article['cat_id']:2)?"selected":"");
						echo "
							<option value='{$row['id']}' {$selected}>{$row['category_title']}</option>
						";
					}
			?>
		</select>
	</div>
	<div style="margin-bottom:-1rem;">
		<label for=""><a href="?do=upload<?php echo isset($article['id'])?'&id='.$article['id']:'' ?>">Upload Image <i class="fas fa-upload"></i></a></label>
	</div>
	<div class="submit">
		<input type="submit" value="Submit">
	</div>
<?php if(isset($_GET['action']) AND $_GET['action'] == 'edit'){ ?>
	<div class="footer">
		<a title="Go back to article" href="?do=article&id=<?php echo $article['id'] ?>" class="border">Go back to article</a>
	</div>
<?php	}else{ ?>
	<div class="footer">
		<a title="Go back to main" href="./" class="border">Go back to main</a>
	</div>
</form>
<?php
	}
 }
} ?>

<script>
	function deleteArticle(id, title){
		var confirmDelete = confirm('Are you sure you want to delete this article?\n' + title);
		if(confirmDelete === false){ return false; }
		else { location.href = location.pathname + "?do=article&action=delete&id=" + id; }
	}
</script>
