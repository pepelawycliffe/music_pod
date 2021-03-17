<?php session_start() ?>
<?php 
include 'db_connect.php';
$items = $conn->query("SELECT p.*,m.title,m.artist,m.upath FROM playlist_items p inner join uploads m on m.id = p.music_id where p.playlist_id ={$_GET['pid']} ");
?>
<?php include 'db_connect.php'; ?>
<div class="container-fluid">
	<form action="" id="manage_playlist_items">
	<input type="hidden" name="playlist_id" value="<?php echo $_GET['pid'] ?>">
		<div class="row">
			<div class="form-group col-md-12">
			<label for="" class="control-label">Find Music</label>
				<div class="position-relative w-100">
					<input type="search" class="form-control form-control-sm w-100" id="msearch" placeholder="Enter music title or artist" autocomplete="off">
					<div class="position-absolute w-100" id="suggest" style="z-index:5;max-height: 50vh;overflow: auto;display:none">
						<ul class="list-group">
							
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<table class="table table-stripped" id="plist">
				<tbody>
					<?php 
					while($row = $items->fetch_assoc()):
					?>
					<tr data-id='<?php echo $row['id'] ?>'>
						<td><span class="btn bg-gradient-success rounded-circle d-flex justify-content-center align-items-center" style="width:30px;height:30px;z-index:2" onclick="play_music({0:{id:<?php echo $row['id'] ?>,upath:'assets/uploads/<?php echo $row['upath'] ?>'}})"><div class="fa fa-play text-white"></div></span></td>
						<td><input name='music_id[]' value='<?php echo $row['id'] ?>' type='hidden'><i class='fa fa-music text-gradient-primary mr-2'></i><?php echo $row['title'] ?></td>
						<td class='text-right'><button type='button' onclick='$(this).closest("tr").remove()' class='btn btn-danger btn-sm'><i class='fa fa-times'></i></button></td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</form>
</div>
<div id="list_clone" class="d-none">
	<li class="list-group-item suggest-item">
	<div id="pdet" class="d-flex justify-content-between align-items-center">
		<div class="d-flex">
			<img src="assets/uploads/play.jpg" alt="" class="img-thumbnail bg-gradient-1" style="width: 50px;height: 50px;object-fit: cover">
			<div class="ml-2 mr-4">
				<div><b><large class="mtitle">Title</large></b>
				</div>
				<div>
					<b><small class="martist">Artist</small></b>
					</div>
				</div>
			</div>
		</span>
		<span class="btn bg-gradient-success bp rounded-circle d-flex justify-content-center align-items-center" style="width:30px;height:30px;z-index:2"><div class="fa fa-play text-white"></div></span>
</li>
</div>
<style>
	.suggest-item{
		cursor:pointer;
	}
	.suggest-item:hover{
		background: black;
    	color: white;
	}
</style>
<script>
	$('#manage_playlist_items').submit(function(e){
		e.preventDefault()
		start_load()
		if($('#plist tbody tr').length <= 0){
			alert_toast("You must add atleast 1 music to list list",'warning')
			return false;
		}
		$.ajax({
			url:"ajax.php?action=save_playlist_items",
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data succsfully saved',"success");
					$('.modal').modal('hide')
					_redirect('index.php?page=view_playlist&id=<?php echo $_GET['pid'] ?>')
					end_load()
				}
			}
		})
	})
	$('#msearch').keyup(function(){
		var _f = $(this).val()
		$('#suggest_load').remove()
		if(_f ==''){
			$("#suggest").hide()
			$("#suggest ul").html('')
		}else{
			$("#suggest").prepend('<div id="suggest_load">Loading...</div>')
			$.ajax({
				url:'ajax.php?action=find_music',
				method:'POST',
				data:{search:_f},
				success:function(resp){
					if(resp){
						resp = JSON.parse(resp)
						if(resp.lenght <=0){
							$("#suggest").hide()
							$("#suggest ul").html('')
						}else{
							$('#suggest_load').remove()
							$("#suggest ul").html('')
							Object.keys(resp).map(k=>{
								var li = $('#list_clone li').clone()
								li.find('img').attr('src','assets/uploads/'+resp[k].cover_image)
								li.find('.mtitle').text(resp[k].title)
								li.find('.martist').text(resp[k].artist)
								li.find('.bp').attr('onclick',"play_music({0:{id:"+resp[k].id+",upath:'assets/uploads/"+resp[k].upath+"'}})")
								li.attr('data-json',(JSON.stringify(resp[k])))
								$('#suggest ul').append(li)
							})
							$("#suggest").show()
							li_func()
						}

					}
				}
			})
		}
	})
	function li_func(){
		$('#msearch').val('')
		$('.suggest-item').click(function(){
			var data = $(this).attr('data-json')
				data = JSON.parse(data)
			if($('#plist tbody').find('tr[data-id="'+data.id+'"]').length > 0){
				alert_toast("Music already on the list","error")
				return false;
			}

			var tr = $('<tr data-id="'+data.id+'"></tr>')
			tr.append('<td><span class="btn bg-gradient-success rounded-circle d-flex justify-content-center align-items-center" style="width:30px;height:30px;z-index:2" onclick="play_music({0:{id:'+data.id+',upath:\'assets/uploads/'+data.upath+'\'}})"><div class="fa fa-play text-white"></div></span></td>')
			tr.append("<td><input name='music_id[]' value='"+data.id+"' type='hidden'><i class='fa fa-music text-gradient-primary'></i>"+data.title+"</td>")
			tr.append("<td class='text-right'><button type='button' onclick='$(this).closest(\"tr\").remove()' class='btn btn-danger btn-sm'><i class='fa fa-times'></i></button></td>")
			$('#plist tbody').prepend(tr)
			$("#suggest").hide()
			$("#suggest ul").html('')
		})
	}
	$('#msearch').on('search', function () {
		$("#suggest").hide()
		$("#suggest ul").html('')
	  })
</script>