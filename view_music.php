<?php
include 'db_connect.php';
$qry = $conn->query("SELECT u.*,g.genre FROM uploads u inner join genres g on g.id = u.genre_id where u.id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	if($k=='title')
		$k = 'mtitle';
	$$k = $v;
}
?>
<div class="col-lg-12">
	<div class="row">
		<div class="col-md-4">
			<center>
				<div class="d-flex img-thumbnail bg-gradient-1 position-relative" style="width: 12rem">
					<img src="assets/uploads/<?php echo $cover_image ?>" alt="" style="object-fit: cover;max-width: 100%;height:14rem">
					<span class="position-absolute" style="bottom:.5em;left:.5em"><div class=" bg-green rounded-circle d-flex justify-content-center align-items-center" style="width: 2rem;height: 2rem;cursor: pointer;" onclick="play_music('assets/uploads/<?php echo $upath ?>')"><i class="fa fa-play"></i></div></span>
				</div>
			</center>
			<div>
			</div>
		</div>
		<div class="col-md-8">
			<h5 class="text-white">Title: <?php echo ucwords($mtitle); ?></h5>
			<h6 class="text-white">Artist: <?php echo ucwords($artist); ?></h6>
			<h6 class="text-white">Genre: <?php echo ucwords($genre); ?></h6>
			<h6 class="text-white border-bottom border-primary"><b class="text-white">Description:</b></h6>
			<div class="text-white">
				<?php echo html_entity_decode($description) ?>
			</div>
		</div>
	</div>
</div>