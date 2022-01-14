<?php
if(isset($_POST['q'])){
	$search = $_POST['q'];
	$stmt = "SELECT * FROM vw_blog WHERE search LIKE '%{$search}%' ORDER BY views DESC";
	$query = $db->query($stmt);
	$i = 0;
	while($row = $query->fetchArray()){
		echo "<div class='search_result'>
			<h3><a href='?do=article&id={$row['id']}'>{$row['article_title']}</a></h3>
			<p style='margin:0.5rem;'>".substr($row['article_content'], 0, 120).(strlen($row['article_content'])>120?'...':'')."</p>
			<p style='margin-block:0.25rem;font-size:90%;font-style:italic;'>Category: <b><a href='?do=category&id={$row['cat_id']}'>{$row['category_title']}</a></b> posted on ".date('m/d/Y',$row['article_timestamp'])." with {$row['views']} views</p>
		</div>";
		$i++;
	}
	echo "
	<div class='results'>{$i} result(s) found for your query</div>
	<div class='actions'>
		<ul>
			<li><a href='./'>Go home</a></li>
		</ul>	
	</div>";
}else{
	echo "<div class='actions'>
		<ul>
			<li><a href='./'>Go home</a></li>
		</ul>	
	</div>";
}