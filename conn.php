<?php
//Create a new SQLite3 Database
$db = new SQLite3('blog.db');

//Create a new table to our database 
$query = "CREATE TABLE IF NOT EXISTS articles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_title VARCHAR(255), article_content TEXT, article_timestamp INTEGER, cat_id INTEGER, views INTEGER)";
$db->exec($query);
$query = "CREATE TABLE IF NOT EXISTS category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_title VARCHAR(255),category_timestamp INTEGER)";
$db->exec($query);
$query = "CREATE VIEW IF NOT EXISTS vw_blog AS SELECT a.id,a.article_title,a.article_content,a.article_timestamp,a.cat_id,c.category_title,a.views,a.article_title || '-' || a.article_content as search FROM articles a LEFT JOIN category c ON c.id = a.cat_id";
$db->exec($query);

function getCatName($id){
	$dbh = new SQLite3('blog.db');
	$getCatName = $dbh->query("SELECT category_title FROM category WHERE id = '{$id}'")->fetchArray();
	return $getCatName['category_title'];
}
function getCatPages($id){
	$dbp = new SQLite3('blog.db');
	$getCatPages = $dbp->query("SELECT COUNT(id) AS counter FROM articles WHERE cat_id = '{$id}'")->fetchArray();
	return $getCatPages['counter'];
}
function parseMarkUp($txt){
	/* Markup
		[ul][/ul]
		[ol][/ol]
		[b][/b]
		[i][/i]
		[u][/u]
		[s][/s]
	*/
	$txt = str_replace("[ul]", "<ul class='markUp'><li>", $txt);
	$txt = str_replace("[/ul]", "</li></ul>", $txt);
	$txt = str_replace("[ol]", "<ol class='markUp'><li>", $txt);
	$txt = str_replace("[/ol]", "</li></ol>", $txt);
	$txt = str_replace("[b]", "<strong class='markUp'>", $txt);
	$txt = str_replace("[/b]", "</strong>", $txt);
	$txt = str_replace("[i]", "<em class='markUp'>", $txt);
	$txt = str_replace("[/i]", "</em>", $txt);
	$txt = str_replace("[u]", "<u class='markUp'>", $txt);
	$txt = str_replace("[/u]", "</u>", $txt);
	$txt = str_replace("[s]", "<strike class='markUp'>", $txt);
	$txt = str_replace("[/s]", "</strike>", $txt);
	$txt = str_replace("[img]", "<img class='markUp' src=\"", $txt);
	$txt = str_replace("[/img]", "\">", $txt);
	$txt = str_replace("[caption]", "<span class='markUp_caption'>", $txt);
	$txt = str_replace("[/caption]", "</span>", $txt);
	return $txt;
}


?>