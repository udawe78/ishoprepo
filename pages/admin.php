<div class="container adm">
<div class="row">
	<script>$(function(){
		$('#tabs').tabs()
	});</script>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">Категории товаров</a></li>
			<li><a href="#tabs-2">Товары</a></li>
			<li><a href="#tabs-3">Картинки</a></li>
		</ul>	
			<div id="tabs-1">
				<form action='' method='post'>
				  			<label for="category">Введите категорию</label><br>
				    		<input type="text" name="category"><br><br>
				    		<input type='submit' name='addcat' value='Добавить категорию'>
							<input type='submit' name='delcat' value='Удалить'>
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
						<label for="itemname">Введите название товара</label><br>
			    		<input type="text" name="itemname"><br><br>
			    		<label for="pricein">Цена закупки</label><br>
			    		<input type="text" name="pricein"><br><br>
			    		<label for="pricesale">Цена продажи</label><br>
			    		<input type="text" name="pricesale"><br><br>
			    		<label for="info">Информация о товаре</label><br>
			    		<textarea name="info" id="" cols="30" rows="10"></textarea>
			    		<br><br>
						<input type="file" name="file" multiple accept="image/*"><br> <!-- картинки любых форматов -->
			    		<input type='submit' name='additem' value='Добавить товар'>
						<input type='submit' name='delitem' value='Удалить'>
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
		  					//$data=array('itemname'=>$itemname,'catid'=>$catid,'pricein'=>$pricein,'pricesale'=>$pricesale,'info'=>$info,'imagepath'=>$path);
		  					// var_dump($data);
		  					$item=new Item($itemname, $catid, $pricein, $pricesale, $info);
		  					$item->intoDb();
		  					//чтобы форма повторно не отправлялась
		  					//echo "<script>document.location='http://shop/index.php?page=2'</script>";
		  					//header("Location:".$_SERVER["REQUEST_URI"]);
		  					//exit;
		  				
		  				}
		  				if (isset($_POST['delitem']))
		  				{
		  					$i=Item::fromDB(k);
		  					$i->Show();
		  				}

		  			?>
			</div>

			<div id="tabs-3">3333333
				
			</div>
		


	</div>


</div>
</div>