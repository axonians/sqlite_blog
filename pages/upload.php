<?php
if((isset($_GET['action']) AND $_GET['action']=='add') AND (isset($_FILES["fileToUpload"]))){

$target_dir = "./images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "<span style='color:crimson'>File is not an image.</span>";
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($target_file)) {
  echo "<span style='color:crimson'>Sorry, file already exists.</span>";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "<span style='color:crimson'>Sorry, your file is too large.</span>";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "<span style='color:crimson'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</span>";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "<span style='color:crimson'>Sorry, your file was not uploaded.</span>";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
  } else {
    echo "<span style='color:crimson'>Sorry, there was an error uploading your file.</span>";
  }
}
	$refId = $_POST['refId']=='NaN'?'':'&id='.$_POST['refId'];
	echo "<a href='?do=upload{$refId}'>Go back</a>";
}else{ ?>

<form id="add_new" class="content" action="?do=upload&action=add" method="POST" enctype="multipart/form-data">
	<div class="header">
		Upload Image
	</div>
	<div class="body">
		<input type="hidden" name="refId" value="<?php echo isset($_GET['id'])?$_GET['id']:'NaN'?>">
		<input type="file" name="fileToUpload" id="fileToUpload">
	</div>
	<div class="submit">
		<input type="submit" value="Upload">
	</div>
	<div class="footer">
	<?php if(isset($_GET['id'])){ ?>
		<a title="Go back to article" href="?do=article&id=<?php echo $_GET['id'] ?>" class="border">Go back to article</a>
	<?php } else { ?>
		<a title="Go back to editing" href="?do=article&action=new" class="border">Go back to editing</a>
	<?php } ?>
	</div>
</form>
<div class="file_list">
	<ul><b>File List</b>
	<?php
	$dir = "./images/";
	$a = scandir($dir);
	foreach($a as $files){
		if($files != '..'){
			echo "<li><a href='./images/{$files}' target='_new'>{$files}</a></li>";
		}
	}

	?>
	</ul>
</div>

<?php } ?>