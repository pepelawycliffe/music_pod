<?php session_start() ?>
<?php include 'db_connect.php' ?>
<style>
	.column {
	  float: left;
	  width: 100%;
	  padding: 10px;
	}

	.column img,.column video {
	  margin-top: 12px;
	  max-width: 100%;
	  max-height: 20vh;
	}
	.c-row {
	  display: flex;
	  flex-wrap: wrap;
	  padding: 0 4px;
	}
</style>
<?php 
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM posts where id = {$_GET['id']}")->fetch_array();
	foreach($qry as $k => $v){
		$$k= $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage_post">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="d-flex w-100 align-items-center">
			<div class="rounded-circle mr-1" style="width: 40px;height: 40px;top:-5px;left: -40px">
	              <img src="assets/uploads/<?php echo $_SESSION['login_profile_pic'] ?>" class="image-fluid image-thumbnail rounded-circle" alt="" style="max-width: calc(100%);height: calc(100%);">
	        </div>
	        <div class="ml-4 text-left" style="width:calc(80%)">
	        	<div><small><?php echo ucwords($_SESSION['login_firstname']).' '.$_SESSION['login_lastname'] ?></small></div>
	        	<input type="hidden" name="type" value="<?php echo isset($type) ? $type : 0 ?>">
	        	<div class="dropdown">
	        		<a href="javascript:void(0)" class="badge badge-pill badge-secondary text-white dropdown-toggle " id="type-selected" data-toggle="dropdown">
	        			<?php if(isset($type) && $type == 1): ?>
	        				<i class="fa fa-lock"></i> Only Me
        				<?php else: ?>
	        			<i class="fa fa-globe-asia"></i> Public
        				<?php endif; ?>
	        		</a>
	        		<div class="dropdown-menu">
	        			 <a class="dropdown-item type-item" href="javascript:void(0)" data-type="0"><i class="fa fa-globe-asia"></i> Public</a>
	        			 <a class="dropdown-item type-item" href="javascript:void(0)" data-type="1"><i class="fa fa-lock"></i> Only Me</a>
	        		</div>
	        	</div>
	        </div>
		</div>
		<div class="form-group">
			<textarea name="content" id="content" cols="30" rows="2" class="form-control" placeholder="What's on your mind, <?php echo ucwords($_SESSION['login_firstname']) ?>?" style="resize:none;border: none"><?php echo isset($content) ? $content : '' ?></textarea>
		</div>
		<div class="c-row" id="">
			<div id="file-display" class="column">

				<?php 
				if(isset($id)):
				if(is_dir('assets/uploads/'.$id)):
				$gal = scandir('assets/uploads/'.$id);
				unset($gal[0]);
				unset($gal[1]);
				foreach($gal as $k=>$v):
					$mime = mime_content_type('assets/uploads/'.$id.'/'.$v);
					$img = file_get_contents('assets/uploads/'.$id.'/'.$v); 
					$data = base64_encode($img); 
				?>
					<div class="imgF">
						<span class="rem badge badge-primary" onclick="rem_func($(this))" style="cursor: pointer;"><i class="fa fa-times"></i></span>
						<input type="hidden" name="img[]" value="<?php echo $data ?>">
						<input type="hidden" name="imgName[]" value="<?php echo $v ?>">
						<?php if(strstr($mime,'image')): ?>
						<img class="imgDropped" src="assets/uploads/<?php echo $id.'/'.$v ?>">
					    <?php else: ?>
						<video src="assets/uploads/<?php echo $row['file_path'] ?>"></video>
				    	<?php endif; ?>
				</div>
				<?php endforeach; ?>
				<?php endif; ?>
				<?php endif; ?>
				
			</div>
		</div>
		<input type="file" name="file[]" multiple="multiple" onchange="" id="postF" onchange="displayUpload(this)" class="d-none" accept="image/*,video/*">
		<div class="card solid">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center">
					<small>Add to Your Post</small>
					<span>
						<label for="postF" style="cursor: pointer;"><a class="rounded-circle"><i class="fa fa-photo-video text-success"></i></a></label>
					</span>
				</div>
			</div>
		</div>
		
	</form>
</div>
<div class="modal-footer display py-1 px-1">
	<div class="d-block w-100">
		<button class="btn btn-block btn-primary btn-sm" form="manage_post">POST</button>
	</div>
</div>
<div class="imgF" style="display: none " id="img-clone">
			<span class="rem badge badge-primary" onclick="rem_func($(this))" style="cursor: pointer;"><i class="fa fa-times"></i></span>
	</div>
<style>
	#uni_modal .modal-footer{
		display: none;
	}
	#uni_modal .modal-footer.display{
		display: block !important;
	}

</style>
<script>
	if('<?php echo isset($_GET['upload']) ?>' == 1){
		$('#postF').trigger('click')
	}
	if('<?php echo isset($_GET['id']) ?>' == 1){
		$('[name="content"]').trigger('keyup')
	}
	$('[name="file[]"]').change(function(){
		displayUpload(this)
	})
	
    function displayUpload(input){
    	if (input.files) {
    			Object.keys(input.files).map(function(k){
    				var reader = new FileReader();
    				 var t = input.files[k].type;
				    	var _types = ['video/mp4','image/x-png','image/png','image/gif','image/jpeg','image/jpg'];
				    	if(_types.indexOf(t) == -1)
				    		return false;
				        reader.onload = function (e) {
				        	// $('#cimg').attr('src', e.target.result);

          				var bin = e.target.result;
          				var fname = input.files[k].name;
          				var imgF = document.getElementById('img-clone');
						  	imgF = imgF.cloneNode(true);
						  imgF.removeAttribute('id')
						  imgF.removeAttribute('style')
				        	if(t == "video/mp4"){
								var img = document.createElement("video");
								}else{
								var img = document.createElement("img");
								}
					          var fileinput = document.createElement("input");
					          var fileinputName = document.createElement("input");
					          fileinput.setAttribute('type','hidden')
					          fileinputName.setAttribute('type','hidden')
					          fileinput.setAttribute('name','img[]')
					          fileinputName.setAttribute('name','imgName[]')
					          fileinput.value = bin
					          fileinputName.value = fname
					          img.classList.add("imgDropped")
					          img.src = bin;
					          imgF.appendChild(fileinput);
					          imgF.appendChild(fileinputName);
					          imgF.appendChild(img);
					          document.querySelector('#file-display').appendChild(imgF)
				        }
		        reader.readAsDataURL(input.files[k]);
    			})
    			rem_func()
    }
    }
    	 
    function rem_func(_this){
			_this.closest('.imgF').remove()
			if($('#drop .imgF').length <= 0){
				$('#drop').append('<span id="dname" class="text-center">Drop Files Here <br> or <br> <label for="chooseFile"><strong>Choose File</strong></label></span>')
			}
	}
   
	$('#content').on('change keyup keydown paste cut', function () {
		if(this.scrollHeight <= 250)
        $(this).height(0).height(this.scrollHeight);
    })
	$('.type-item').click(function(){
		$('[name="type"').val($(this).attr('date-type'))
		$('#type-selected').html($(this).html())
	})
    $('#manage_post').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:"ajax.php?action=save_post",
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					location.reload()
				}
			}
		})
	})
</script>