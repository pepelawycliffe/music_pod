<?php include'db_connect.php' ?>
<style>
	.music-item:hover{
		background: rgb(110,109,109);
		background: radial-gradient(circle, rgba(110,109,109,1) 0%, rgba(55,54,54,1) 23%, rgba(28,27,27,1) 56%);
	}
</style>
<div class="col-lg-12">
	<div class="d-flex justify-content-between align-items-center w-100">
		<div class="form-group" style="width:calc(50%) ">
			<div class="input-group">
              <input type="search" id="filter" class="form-control form-control-sm" placeholder="Search music using keyword">
              <div class="input-group-append">
                  <button type="button" id="search" class="btn btn-sm btn-dark">
                      <i class="fa fa-search"></i>
                  </button>
              </div>
          </div>
		</div>
		<a class="btn btn-sm btn-primary bg-gradient-primary" href="index.php?page=new_music"><i class="fa fa-plus"></i> Add New</a>
	</div>
	<button class="btn btn-primary" id="play_all">Play All</button>
	<div class="row" id="music-list">
		<?php 
			$musics= $conn->query("SELECT u.*,g.genre FROM uploads u inner join genres g on g.id = u.genre_id order by u.title asc");
			while($row=$musics->fetch_assoc()):
				$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
				unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
				$desc = strtr(html_entity_decode($row['description']),$trans);
				$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
		?>
		<div class="card bg-black music-item my-2 mx-1" data-id="<?php echo $row['id'] ?>" data-upath="<?php echo $row['upath'] ?>" style="width:15vw;cursor:pointer;">
			<div class="card-img-top flex-w-100 position-relative py-2 px-3">
				<?php if($_SESSION['login_type'] == 1 || $_SESSION['login_id'] == $row['user_id']): ?>
                	<div class="dropdown position-absolute" style="right:.5em;top:.5em">
	                  <button type="button" class="btn btn-tool py-1" data-toggle="dropdown" title="Manage" style="background: #000000ab;z-index: 1">
	                    <i class="fa fa-ellipsis-v"></i>
	                  </button>
	                  <div class="dropdown-menu bg-dark">
              			<a class="dropdown-item bg-dark" data-id="<?php echo $row['id'] ?>" href="index.php?page=edit_music&id=<?php echo $row['id'] ?>">Edit</a>
              			<a class="dropdown-item delete_music bg-dark" data-id="<?php echo $row['id'] ?>" href="javascript:void(0)">Delete</a>
	                  </div>
	                  </div>
	              <?php endif; ?>
				<span class="position-absolute" style="bottom:.5em;left:.5em;z-index: 2"><div class="btn bg-green rounded-circle d-flex justify-content-center align-items-center" style="width: 2rem;height: 2rem;cursor: pointer;" onclick="play_music({0:{id:'<?php echo $row['id'] ?>',upath:'assets/uploads/<?php echo $row['upath'] ?>'}})"><i class="fa fa-play"></i></div></span>
		<a href="index.php?page=view_music&id=<?php echo $row['id'] ?>">

				<img src="assets/uploads/<?php echo $row['cover_image'] ?>" class="card-img-top"  style="object-fit: cover;max-width: 100%;height:26vh" alt="music Cover">
			</div>
			<div class="card-body border-top border-primary" style="min-height:20vh">
				<h5 class="card-title w-100"><?php echo ucwords($row['title']) ?></h5>
				<h6 class="card-subtitle mb-2 text-muted w-100">Artist: <?php echo ucwords($row['artist']) ?></h6>
				<p class="card-text truncate text-white"><small><?php echo strip_tags($desc) ?></small></p>
			</div>
		</a>
		</div>
		<?php endwhile; ?>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
	$('.delete_music').click(function(){
	_conf("Are you sure to delete this music?","delete_music",[$(this).attr('data-id')])
	})
	$('#manage_music').click(function(){
		uni_modal("New music",'manage_music.php')
	})
	$('.edit_music').click(function(){
		uni_modal("New music",'manage_music.php?id='+$(this).attr('data-id'))
	})
	check_list()
	})
	function delete_music($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_music',
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
		$('.music-item').each(function(){
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
		var count = $('.music-item:visible').length
		if(count > 0){
			if($('#ns').length > 0)
				$('#ns').remove()
		}else{
			var ns = $('<div class="col-md-12 text-center text-white" id="ns"><b><i>No data to be display.</i></b></b></div>')
			$('#music-list').append(ns)
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
	  $('#play_all').click(function(){
	  	var _src = {};
	  	var i = 0
		  $('.music-item').each(function(){
		  	_src[i++] ={id:$(this).attr('data-id'),upath:'assets/uploads/'+$(this).attr('data-upath')}
		  })
		  play_music(_src)
	  })
</script>