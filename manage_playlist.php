<?php
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM playlist where id={$_GET['id']}")->fetch_array();
	foreach($qry as $k => $v){
		if($k == 'title')
			$k = 'ptitle';
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-playlist">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="title" class="control-label">Title</label>
			<input type="text" class="form-control form-control-sm" name="title" id="title" value="<?php echo isset($ptitle) ? $ptitle : '' ?>">
		</div>
		<div class="form-group">
			<label for="description" class="control-label">Description</label>
			<textarea name="description" id="description" cols="30" rows="3" class="form-control"><?php echo isset($description) ? $description : "" ?></textarea>
		</div>
		<div class="row">
			<div class="form-group">
			<label for="" class="control-label">Cover Image</label>
				<div class="custom-file">
                  <input type="file" class="custom-file-input" id="customFile" name="cover" accept="image/*" onchange="displayImgCover(this,$(this))">
                  <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
			</div>
		</div>
		<div class="row">
			<div class="form-group d-flex justify-content-center">
				<img src="assets/uploads/<?php echo isset($cover_photo) ? $cover_photo : '' ?>" alt="" id="cover" class="img-fluid img-thumbnail">
			</div>
		</div>
	</form>
</div>
<script>
		$('#manage-playlist').submit(function(e){
			e.preventDefault();
			start_load()
			$.ajax({
				url:'ajax.php?action=save_playlist',
				data: new FormData($(this)[0]),
			    cache: false,
			    contentType: false,
			    processData: false,
			    method: 'POST',
			    type: 'POST',
				success:function(resp){
					if(resp > 0){
						alert_toast("Data successfully saved.","success");
						setTimeout(function(){
							end_load()
            				$('.modal').modal('hide')
							_redirect("index.php?page=view_playlist&id="+resp)
						},1750)
					}
				}
			})
		})
	function displayImgCover(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cover').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
</script>