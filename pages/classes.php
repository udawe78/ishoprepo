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
	private $id, $itemname, $catid, $pricein, $pricesale, $info, $rate, $count, $imagepath, $action;
		function __construct($itemname, $catid, $pricein, $pricesale, $info, 
			$rate="", $count=1, $imagepath='images/itemava.jpg', $action="", $id=0) 
	{
		$this->id=$id;
		$this->itemname=$itemname;
		$this->catid=$catid;
		$this->pricein=$pricein;
		$this->pricesale=$pricesale;
		$this->info=$info;
		$this->imagepath=$imagepath;
		$this->rate=$rate;
		$this->count=$count;
		$this->action=$action;
	}
	function intoDb() //заносиит объект в таблицу бд
	{
		$db=new ManagerDb('localhost', 'root', '654321', 'ishop');
		$pdo=$db->connect();
		$ins='insert into Items (itemname, catid, pricein, pricesale, info, rate, count, imagepath, action) values(?,?,?,?,?,?,?,?,?)';
		$ps=$pdo->prepare($ins);
		$ps->execute(array($this->itemname, $this->catid, $this->pricein, $this->pricesale, 
			$this->info, $this->rate, $this->count, $this->imagepath, $this->action));
	}
	static function fromDb($id)
	{
		$db=new ManagerDb('localhost', 'root', '654321', 'ishop');
		$pdo=$db->connect();
		$sel='select * from Items where id=?';
		$ps=$pdo->prepare($sel);
		$ps->execute(array($id));
		$row=$ps->fetch(PDO::FETCH_LAZY);
		$item=new Item($row['itemname'], $row['catid'], $row['pricein'],$row['pricesale'], $row['info'], $row['rate'], $row['count'], $row['imagepath'], $row['action'], $row['id']);
		return $item;
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
	private $id, $userid, $itemid, $datain, $price;
	

	function __construct($userid, $itemid, $datain, $price, $id=0)
	{
		$this->id=$id;
		$this->userid=$userid;
		$this->itemid=$itemid;
		$this->datain=$datain;
		$this->price=$price;
	}
function intoDb() //заносиит объект в таблицу бд
	{
		$db=new ManagerDb('localhost', 'root', '654321', 'ishop');
		$pdo=$db->connect();
		$ins='insert into Carts (userid, itemid, datain, price) values(?,?,?,?)';
		$ps=$pdo->prepare($ins);
		$ps->execute(array($this->userid, $this->itemid, $this->datain, $this->price));
	}
	static function fromDb($id)
	{
		$db=new ManagerDb('localhost', 'root', '654321', 'ishop');
		$pdo=$db->connect();
		$sel='select * from Carts where id=?';
		$ps=$pdo->prepare($sel);
		$ps->execute(array($id));
		$row=$ps->fetch(PDO::FETCH_LAZY);
		$cart=new Item($row['userid'], $row['itemid'], $row['datain'], $row['price'], $row['id']);
		return $cart;
	}
} //end of Cart class
?>
