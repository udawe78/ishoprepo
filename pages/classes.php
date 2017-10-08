<?php
class ManagerDb
{
	private $host;
	private $user;
	private $pass;
	private $dbname;
	function __construct($host,$user,$pass,$dbname)
	{
		$this->host=$host;
		$this->user=$user;
		$this->pass=$pass;
		$this->dbname=$dbname;
	}
	function connect()
	{
		$dsn='mysql:host='.$this->host.';dbname='.$this->dbname.';charset=utf8;';
		$options=array(
			PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
			PDO::MYSQL_ATTR_INIT_COMMAND=>'set names utf8'
		);
		$pdo=new PDO($dsn, $this->user, $this->pass, $options);
		return $pdo;
	}
	function Show()
	{
		echo "host: ".$this->host."<br/>";
		echo " user: ".$this->user."<br/>";
		echo " pass: ".$this->pass."<br/>";
		echo " dbname: ".$this->dbname."<br/>";
	}

}
// $m=new ManagerDb('localhost', 'root', '654321', 'ishop');
// $m->Show();

class User
{
	private $id, $login, $pass, $roleid, $discount, $total, $imagepath;
	function __construct($login, $pass, $imagepath='images/avatar.jpg', $id=0)
	{
		$this->login=$login;
		$this->pass=$pass;
		$this->imagepath=$imagepath;
		$this->id=$id;
		$this->discount=0;
		$this->total=0;
		$this->roleid=1;
	}
	function intoDb() //заносиит объект в таблицу бд
	{
		$db=new ManagerDb('localhost', 'root', '654321', 'ishop');
		$pdo=$db->connect();
		$ins='insert into Users (login,pass,imagepath,id,discount,total,roleid) values(?,?,?,?,?,?,?)';
		$ps=$pdo->prepare($ins);
		$ps->execute(array($this->login,$this->pass,$this->imagepath,$this->id,$this->discount,$this->total,$this->roleid));
	}
	static function fromDb($id) //можно вызывать без объекта!!! ибо static
	{
		$db=new ManagerDb('localhost', 'root', '654321', 'ishop');
		$pdo=$db->connect();
		$sel='select * from Users where id=?';
		$ps=$pdo->prepare($sel);
		$ps->execute(array($id));
		$row=$ps->fetch(PDO::FETCH_LAZY);
		$user=new User($row['login'],$row['pass'],$row['imagepath'],$row['id'],$row['discount'],$row['total'],$row['roleid']);
		return $user;
	}
}//end of User class

class Item
{
	private $id, $itemname, $catid, $pricein, $pricesale, $info,  $imagepath, $count, $rate, $action;

	function __construct($itemname,$catid,$pricein,$pricesale,$info,$imagepath="images/itemava.jpg",$count=1,$rate=0.0,$action=0,$id=0) 
	{
		$this->id=$id;
		$this->itemname=$itemname;
		$this->catid=$catid;
		$this->pricein=$pricein;
		$this->pricesale=$pricesale;
		$this->info=$info;
		$this->imagepath=$imagepath;
		$this->count=$count;
		$this->rate=$rate;
		$this->action=$action;
	}

	function intoDb() //заносиит объект в таблицу бд
	{
		$db=new ManagerDb('localhost', 'root', '654321', 'ishop');
		$pdo=$db->connect();
		$ins='insert into Items (itemname, catid, pricein, pricesale, info, imagepath, count, rate, action) values(?,?,?,?,?,?,?,?,?)';
		$ps=$pdo->prepare($ins);
		$ps->execute(array($this->itemname, $this->catid, $this->pricein, $this->pricesale, 
			$this->info, $this->imagepath, $this->count, $this->rate, $this->action));
	}

	static function fromDb($id)
	{
		$db=new ManagerDb('localhost', 'root', '654321', 'ishop');
		$pdo=$db->connect();
		$sel='select * from Items where id=?';
		$ps=$pdo->prepare($sel);
		$ps->execute(array($id));
		$row=$ps->fetch(PDO::FETCH_LAZY);
		$item=array($row['itemname'], $row['catid'], $row['pricein'],$row['pricesale'], $row['info'], $row['imagepath'], $row['count'], $row['rate'], $row['action'], $row['id']);
		return $item;
	}

	function Draw()
	{
		echo '<div class="col-sm-3" style="height:300px">';
		echo '<a href=pages/iteminfo.php?item='.$this->id.' target="_blank"><h3>'.$this->itemname.'</h3></a>';
		echo '<div>
			<a href=pages/iteminfo.php?item='.$this->id.' target="_blank"><img class="" src="'.$this->imagepath.'" height="150px" style="max-width:150px"></a>
			<span class=" iprice" style="font-size:18pt;color:red">$ '.$this->pricesale.'</span>	
			</div>';
		echo '<div style="overflow:hidden;height:45px"><a href=pages/iteminfo.php?item='.$this->id.' target="_blank">'.$this->info.'</a></div>';
		echo '<div><button class="btn btn-success" name="cart'.$this->id.'" type="submit">Add to Cart</button>		
			<span class="pull-right" style="font-size:12pt;color:blue">In stock: <span class="badge">'.$this->count.'</span> pcs</span>
				</div>';
		echo "</div>";
		
	}

	static function GetItems($id=0)
	{
		$db=new ManagerDb('localhost', 'root', '654321', 'ishop');
		$pdo=$db->connect();
		$ps="";
		if($id==0)
		{
			$ps=$pdo->prepare('select * from items');
			$ps->execute();
		}
		else
		{
			$ps=$pdo->prepare('select * from items where catid=?');
			$ps->execute(array($id));
		}
		$items=array();
		while($row=$ps->fetch())
		{
			$items[]=new Item($row['itemname'], $row['catid'], $row['pricein'],
				$row['pricesale'], $row['info'], $row['imagepath'], $row['count'], $row['rate'], $row['action'], $row['id']);
		}
		return $items;
	}

	function Show()
	{
		echo "id: ".$this->id."<br/>";
		echo "itemname: ".$this->itemname."<br/>";
		echo "catid: ".$this->catid."<br/>";
		echo "pricein: ".$this->pricein."<br/>";
		echo "pricesale: ".$this->pricesale."<br/>";
		echo "info: ".$this->info."<br/>";
		echo "rate: ".$this->rate."<br/>";
		echo "count: ".$this->count."<br/>";
		echo "imagepath: ".$this->imagepath."<br/>";
		echo "action: ".$this->action."<br/>";
	}
}//end of Item class

class Cart
{
	private $id, $userid, $itemid, $datein, $price, $orderid;
	

	function __construct($userid, $itemid, $datein, $price, $id=0)
	{
		$this->id=$id;
		$this->userid=$userid;
		$this->itemid=$itemid;
		$this->datein=$datein;
		$this->price=$price;
	}
function intoDb() //заносиит объект в таблицу бд
	{
		$db=new ManagerDb('localhost', 'root', '654321', 'ishop');
		$pdo=$db->connect();
		$ins='insert into Carts (userid, itemid, datein, price) values(?,?,?,?)';
		$ps=$pdo->prepare($ins);
		$ps->execute(array($this->userid, $this->itemid, $this->datein, $this->price));
	}
	static function fromDb($id)
	{
		$db=new ManagerDb('localhost', 'root', '654321', 'ishop');
		$pdo=$db->connect();
		$sel='select * from Carts where id=?';
		$ps=$pdo->prepare($sel);
		$ps->execute(array($id));
		$row=$ps->fetch(PDO::FETCH_LAZY);
		$cart=new Item($row['userid'], $row['itemid'], $row['datein'], $row['price'], $row['id']);
		return $cart;
	}
} //end of Cart class
?>
