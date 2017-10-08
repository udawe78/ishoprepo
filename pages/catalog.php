<div class="row">
<form action="index.php?menu=1" method="post">
	<?php
		$db=new ManagerDb('localhost', 'root', '654321', 'ishop');
		$pdo=$db->connect();
		$res=$pdo->query("select * from Categories");
		echo '<select name="catid">';
		while($row=$res->fetch())
		{
			echo '<option value="'.$row['id'].'">';
			echo $row['category'];
			echo '</option>';
		}
		echo '</select>';
		echo '<input type="submit" name="btcatid" value="Ok">';
		if(isset($_REQUEST['btcatid']))
		{
			$_SESSION['catid']=$_REQUEST['catid'];
		}
	?>
</form>
</div>
<div class="row">
	<form action="index.php?menu=1" method="post">
		<?php
			$items=array();
			if(isset($_SESSION['catid']))
			{
				$items=Item::GetItems($_SESSION['catid']);	
			}
			else
			{
				$items=Item::GetItems();	
			}
			
			foreach($items as $i)
			{
				$i->Draw();
			}

			$orderid=$_SESSION['orderid'];
			foreach ($_REQUEST as $k => $v)
			{
				if(substr($k,0,4)=='cart')
				{
					$iid=substr($k,4);

					if(isset($_SESSION['userid']))
						$uid=$_SESSION['userid'];
					else
						$uid=1;

					
					$date=@date('Y-m-d H:i:s');
					$ins='insert into carts (userid, itemid, datein, price, orderid)
										value (?,?,?,?,?)';
					$db=new ManagerDb('localhost', 'root', '654321', 'ishop');
					$pdo=$db->connect();

					$ps=$pdo->prepare('select pricesale from items where id=?');
					$ps->execute(array($iid));
					$row=$ps->fetch();
					$price=$row['pricesale'];

					$ps1=$pdo->prepare($ins);
					$ps1->execute(array($uid,$iid,$date,$price,$orderid));
				}
			}
		?>
	</form>
</div>


