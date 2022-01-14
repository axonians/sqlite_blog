<?php
	if(!isset($_GET['action']) AND !isset($_GET['id'])){
		echo "<div class='header'>Categories</div><ul class='categories'>";
		$cat = $db->query("SELECT * FROM category ORDER BY category_title ASC");
			while($row = $cat->fetchArray()){
				echo "<li><a href='?do=category&id={$row['id']}'>{$row['category_title']}</a> <em>(".getCatPages($row['id'])." articles)</em></li>";
			}
		echo "</ul>
		<div class='actions'>
			<ul>
				<li><a href='?do=category&action=new'>Add Category</a></li>
				<li><a href='?do=category&action=view'>Manage Categories</a></li>
			</ul>	
		</div>";
	}
	elseif(isset($_GET['id']) AND !isset($_GET['action'])){
		$cat = $db->query("SELECT * FROM category WHERE id = {$_GET['id']}")->fetchArray();
		echo "<div class='header' style='color: var(--link-color)'><a href='?do=category'>Category</a> :: {$cat['category_title']}</div><ul class='categories'>";
		$articles = $db->query("SELECT * FROM articles WHERE cat_id = {$cat['id']} ORDER BY article_timestamp DESC");
			while($row = $articles->fetchArray()){
				echo "<li><a href='?do=article&id={$row['id']}'>{$row['article_title']}</a> <em>(posted: ".date('m/d/Y h:i:s A',$row['article_timestamp']).")</em></li>";
			}
		echo "</ul>
		<div class='actions'>
			<ul>
				<li><a href='?do=article&action=new'>Add Article</a></li>
				<li><a href='?do=category&action=new'>Add Category</a></li>
			</ul>	
		</div>";
	}
	elseif($_GET['action']=='view'){
		echo "<div class='header'><a style='color:var(--link-color);' href='?do=category'>Categories</a></div>
		<ul class='categories' style='width: 400px;'>";
		$cat = $db->query("SELECT * FROM category ORDER BY category_title ASC");
			while($row = $cat->fetchArray()){
				echo "
					<li><a href='?do=category&id={$row['id']}'>{$row['category_title']}</a> <em>(created: ".date('m/d/Y',$row['category_timestamp']).", ".getCatPages($row['id'])." articles)</em>
					<span style='float:right;'>
						<span onclick=\"deleteCategory('{$row['id']}','{$row['category_title']}')\" title='Remove Category {$row['category_title']}' class='link'><i class='fas fa-trash-alt' style='color:crimson;'></i></span>
						<a href='?do=category&action=edit&id={$row['id']}' title='Rename Category {$row['category_title']}'><i class='fas fa-edit' style='color:green;'></i></a>
					</span></li>
				";
			}
		echo "</ul>
		<div class='actions'>
			<ul>
				<li><a href='?do=category&action=new'>Add Category</a></li>
			</ul>	
		</div>";
	}
	elseif($_GET['action']=='new' OR $_GET['action']=='edit'){ ?>

<form action="?do=category&action=add" method="POST" id="add_new">
	<div class="header">
		<?php echo ucwords($_GET['action'])?> Category
	</div>
	<?php
		if($_GET['action']=='edit'){
			$cat = $db->query("SELECT * FROM category WHERE id = {$_GET['id']}")->fetchArray();
			echo "<input type=\"hidden\" name=\"cat_id\" value=\"{$cat['id']}\">";
		}
	?>
	<div class="title">
		<label for="">Category</label>
		<input type="text" name="category" id="category" value="<?php echo isset($cat['id'])?$cat['category_title']:''; ?>">
	</div>
	<div class="submit">
		<input type="submit" value="Submit">
	</div>
	<div class='actions'>
		<ul>
			<li><a href='?do=category&action=view'>Manage Categories</a></li>
		</ul>	
	</div>
</form>

<?php }
	elseif($_GET['action']=='add'){
		if(isset($_POST['category'])){
			$category = $_POST['category'];
			$curr_timestamp = time();
			if(isset($_POST['cat_id'])){
				$query = $db->prepare("UPDATE category set category_title =:category WHERE id = '{$_POST['cat_id']}'");
			}else{
				$query = $db->prepare("INSERT INTO category (category_title, category_timestamp) VALUES (:category, '{$curr_timestamp}')");
			}
			$query->bindparam(':category', $category);
			$query->execute();
			header('location: ./?do=category');
		}
	}
	elseif($_GET['action']=='delete'){
		if(isset($_GET['id'])){
			$catId = $_GET['id'];
			$query = $db->prepare('DELETE FROM category where id = :catId');
			$query->bindparam(':catId', $catId);
			$query->execute();
			header('location: ./?do=category');
		}
	}
?>

<script>
	function deleteCategory(id, title){
		var confirmDelete = confirm('Are you sure you want to delete this category?\n' + title);
		if(confirmDelete === false){ return false; }
		else { location.href = location.pathname + "?do=category&action=delete&id=" + id; }
	}
</script>