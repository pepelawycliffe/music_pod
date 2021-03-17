<?php if(!isset($conn)){ include 'db_connect.php'; } ?>

<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-body">
			<form action="" id="manage-music">

        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group text-dark">
              <label for="" class="control-label">Genre</label>
              <select name="genre_id" id="genre_id" class="form-control select2 text-dark">
                <option value=""></option>
                <?php
                  $genres = $conn->query("SELECT * FROM genres order by genre asc");
                  while($row = $genres->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($genre_id) && $genre_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['genre']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
        </div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Title</label>
							<input type="text" class="form-control form-control-sm" name="title" value="<?php echo isset($mtitle) ? $mtitle : '' ?>">
						</div>
					</div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Artist</label>
              <input type="text" class="form-control form-control-sm" name="artist" value="<?php echo isset($artist) ? $artist : '' ?>">
            </div>
          </div>
				</div>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label for="" class="control-label">Description</label>
							<textarea name="description" id="" cols="30" rows="4" class="summernote form-control"><?php echo isset($description) ? $description : '' ?></textarea>
						</div>
					</div>
				</div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Upload Music</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="customFile" name="audio" accept="audio/*" <?php echo !isset($upath) || (isset($upath) && empty($upath)) ? "required" : '' ?>>
                  <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>
            <?php if(isset($upath) && !empty($upath)): ?>
            <div class="form-group d-flex justify-content-start align-items-center">
              <?php 
              $upath = explode("_", $upath,2);
              ?>
             <h3 class="mr-4"><i class="fa fa-music text-gradient-primary"></i></h3><p><?php echo $upath[1] ?></p>
            </div>
          <?php endif; ?>
          </div>
          <div class="col-md-6">
           <div class="form-group">
              <label for="" class="control-label">Cover Image</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="customFile" name="cover" accept="image/*" onchange="displayImgCover(this,$(this))">
                  <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>
            <div class="form-group d-flex justify-content-center">
              <img src="assets/uploads/<?php echo isset($cover_image) ? $cover_image : '' ?>" alt="" id="cover" class="img-fluid img-thumbnail">
            </div>
          </div>
        </div>
        </form>
    	</div>
    	<div class="card-footer border-top border-info">
    		<div class="d-flex w-100 justify-content-center align-items-center">
    			<button class="btn btn-flat  bg-gradient-primary mx-2" form="manage-music">Save</button>
    			<button class="btn btn-flat bg-gradient-secondary mx-2" type="button">Cancel</button>
    		</div>
    	</div>
	</div>
</div>
<script>
	$('#manage-music').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_music',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved',"success");
					setTimeout(function(){
            end_load()
              _redirect('index.php?page=music_list')
					},2000)
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