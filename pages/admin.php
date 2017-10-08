<div class="container adm">
<div class="row">
	<script>$(function(){
		$('#tabs').tabs()
	});</script>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">Categories</a></li>
			<li><a href="#tabs-2">Products</a></li>
			<li><a href="#tabs-3">Pics</a></li>
		</ul>	
			<div id="tabs-1">
				<form action='' method='post'>
				  			<label for="category">Enter category</label><br>
				    		<input type="text" name="category"><br><br>
				    		<input type='submit' name='addcat' value='Add category'>
							<input type='submit' name='delcat' value='Delete'>
							<?php 
								$db=new ManagerDb('localhost', 'root', '654321', 'ishop');
								$pdo=$db->connect();
									if (isset($_POST['addcat']))
									{
										$category=$_POST['category'];
										$ps=$pdo->prepare('insert into categories (category) value(:category)');
										$data=array('category'=>$category);
										$ps->execute($data);
									}
							?>
							<br><br>					
				</form>
			</div>

			<div id="tabs-2">
				<form action="" method='post' enctype="multipart/form-data">   
					<?php include_once('lists.html');?>
						<br><br>
						<label for="itemname">Enter product name</label><br>
			    		<input type="text" name="itemname"><br><br>
			    		<label for="pricein">Purchase price</label><br>
			    		<input type="text" name="pricein"><br><br>
			    		<label for="pricesale">Selling price</label><br>
			    		<input type="text" name="pricesale"><br><br>
			    		<label for="info">Product info</label><br>
			    		<textarea name="info" id="" cols="60" rows="3"></textarea><br>
			    		<label for="count">Count</label><br>
			    		<input type="text" name="icount"><br><br>
						<input type="file" name="file" multiple accept="image/*"><br> <!-- картинки любых форматов -->
			    		<input type='submit' name='additem' value='Add product'>
						<input type='submit' name='delitem' value='Delete'>
				</form>
					<?php 
						
		  				if (isset($_POST['additem'])) 
		  				{
		  					if (is_uploaded_file($_FILES['file']['tmp_name'])) 
		  					{
		  						$path='images/'.$_FILES['file']['name'];
		  						move_uploaded_file($_FILES['file']['tmp_name'], $path);
		  					}
		  					$catid=$_POST['catid'];
		  					$pricein=$_POST['pricein'];
		  					$pricesale=$_POST['pricesale'];
		  					$info=trim($_POST['info']);
		  					$itemname=trim($_POST['itemname']);
		  					$count=$_POST['icount'];
		  						
		  					$item=new Item($itemname,$catid,$pricein,$pricesale,$info,$path,$count);
		  					$item->intoDb();
		  					
		  					//чтобы форма повторно не отправлялась
		  					//echo "<script>document.location='http://shop/index.php?page=2'</script>";
		  					//header("Location:".$_SERVER["REQUEST_URI"]);
		  					//exit;
		  				
		  				}
		  				if (isset($_POST['delitem']))
		  				{
		  					
		  				}

		  			?>
			</div>

			<div id="tabs-3">
			tab-3				
			</div>
		


	</div>


</div>
</div>