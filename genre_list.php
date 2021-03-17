<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="d-flex justify-content-between align-items-center w-100">
		<div class="form-group" style="width:calc(50%) ">
			<div class="input-group">
              <input type="search" id="filter" class="form-control form-control-sm" placeholder="Search Genre using keyword">
              <div class="input-group-append">
                  <button type="button" id="search" class="btn btn-sm btn-dark">
                      <i class="fa fa-search"></i>
                  </button>
              </div>
          </div>
		</div>				
		<?php if($_SESSION['login_type'] == 1): ?>
		<button class="btn btn-sm btn-primary bg-gradient-primary" type="button" id="manage_genre"><i class="fa fa-plus"></i> Add New</button>
		<?php endif; ?>
	</div>
	<div class="row" id="genre-list">
		<?php 
			$genres= $conn->query("SELECT * FROM genres order by genre asc");
			while($row=$genres->fetch_assoc()):
		?>
		<div class="card bg-black genre-item my-2 mx-1" date-id="<?php echo $row['id'] ?>" style="width:15vw">
			<div class="card-img-top flex-w-100 position-relative">
				<?php if($_SESSION['login_type'] == 1): ?>
                	<div class="dropdown position-absolute" style="right:.5em;top:.5em">
	                  <button type="button" class="btn btn-tool py-1" data-toggle="dropdown" title="Manage" style="background: #000000ab;">
	                    <i class="fa fa-ellipsis-v"></i>
	                  </button>
	                  <div class="dropdown-menu bg-dark">
              			<button class="dropdown-item edit_genre bg-dark" data-id="<?php echo $row['id'] ?>" type="button">Edit</button>
              			<button class="dropdown-item delete_genre bg-dark" data-id="<?php echo $row['id'] ?>" type="button">Delete</button>
	                  </div>
	                  </div>
	              <?php endif; ?>
		<a href="index.php?page=view_genre&id=<?php echo $row['id'] ?>">
				<img src="assets/uploads/<?php echo $row['cover_photo'] ?>" class="card-img-top"  style="object-fit: cover;max-width: 100%;height:26vh" alt="Genre Cover">
			</div>
			<div class="card-body" style="height: 20vh">
				<div class="card-title"><?php echo ucwords($row['genre']) ?></div>
				<p class="card-text truncate text-white"><?php echo $row['description'] ?></p>
			</div>
			</a>
		</div>
		<?php endwhile; ?>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
	$('.delete_genre').click(function(){
	_conf("Are you sure to delete this Genre?","delete_genre",[$(this).attr('data-id')])
	})
	$('#manage_genre').click(function(e){
		e.preventDefault()
		uni_modal("New Genre",'manage_genre.php')
	})
	$('.edit_genre').click(function(e){
		e.preventDefault()
		uni_modal("Edit Genre",'manage_genre.php?id='+$(this).attr('data-id'))
	})
	check_list()
	})
	function delete_genre($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_genre',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					$('.modal').modal('hide')
					_redirect(document.href)
					end_load()
				}
			}
		})
	}
	function _filter(){
		var _ftxt = $('#filter').val().toLowerCase()
		$('.genre-item').each(function(){
			var _content = $(this).text().toLowerCase()
			if(_content.includes(_ftxt) == true){
				$(this).toggle(true)
			}else{
				$(this).toggle(false)
			}
		})
		check_list()
	}
	function check_list(){
		var count = $('.genre-item:visible').length
		if(count > 0){
			if($('#ns').length > 0)
				$('#ns').remove()
		}else{
			var ns = $('<div class="col-md-12 text-center text-white" id="ns"><b><i>No data to be display.</i></b></b></div>')
			$('#genre-list').append(ns)
		}
	}
	$('#search').click(function(){
	    _filter()
	  })
	  $('#filter').on('keypress',function(e){
	    if(e.which ==13){
	    _filter()
	     return false; 
	    }
	  })
	  $('#filter').on('search', function () {
	      _filter()
	  })
</script>