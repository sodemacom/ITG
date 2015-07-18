<?php


class DB
{
	public $db;
	public $rs;

	function __construct()
	{
		$this->db = new CoreMySQL();
		return $this->db->open();
	}
	 
	function __destruct()
	{
		//$this->db->free_result($this->rs);
		//return $this->db->close();
	}

	public function que($sql)
	{
		if($this->rs = $this->db->query($sql))
		{
			return $this->rs;
		}else{$this->db = new CoreMySQL();
		return $this->db->open();
			print $sql; return false;
		}
	}

	public function obj()
	{
		return $this->db->fetch_object($this->rs);
	}

	public function arr()
	{
		return $this->db->fetch_array($this->rs);
	}

	public function num()
	{
		return $this->db->num_row($this->rs);
	}

	public function one($sql)
	{
		$this->fld($sql);
		while($fldF = $this->obj())
		{
			if($fldF->Key == "PRI")
			{	
				if(GG($fldF->Field)!="")
				{
					$sql .= " WHERE ".$fldF->Field."=".GG($fldF->Field);
					$flg = 1;
				}else{
					$PK = $fldF->Field;
				}
				break;
			}
		}
		if(!$flg)
		{
			print "ERROR... Not found \$_GET[$PK]";
		}else{
			$this->sel($sql);
			return $this->obj();
		}
	}
	
	public function del($sql)
	{
		$this->fld($sql);
		while($fldF = $this->obj())
		{
			if($fldF->Key == "PRI")
			{	
				if(GG($fldF->Field)!="")
				{
					$sql .= " WHERE ".$fldF->Field."=".GG($fldF->Field);
					$flg = 1;
				}else{
					$PK = $fldF->Field;
				}
				break;
			}
		}
		if(!$flg)
		{
			print "ERROR... Not found \$_GET[$PK]";
		}else{
			return $this->que("DELETE FROM ".$sql);
		}
	}

	public function ins($sql)
	{
		$this->fld($sql);
		$sql = "INSERT INTO ".$sql." VALUES(";
		while($fldF = $this->obj())
		{
			if($fldF->Key == "PRI" && $fldF->Extra == "auto_increment")
			{	
				$sql .= "NULL,";
			}else if($this->chkTime($fldF->Type)&&(GP($fldF->Field)==""))
			{
				$sql .= "NOW(),";
			}else if(GP($fldF->Field)!="")
			{
				if($this->chkQuote($fldF->Type)){$quo = "'";}
				$sql .= $quo.GP($fldF->Field).$quo.",";
			}else{
				print "ERROR... \$_POST[$fldF->Field] not value";
				$flg = 1;
			}
		}
		if(!$flg)
		{
			return $this->que(substr($sql,0,-1).")");
		}
	}

	public function upd($sql)
	{
		$this->fld($sql);
		$sql = "UPDATE ".$sql." SET ";
		while($fldF = $this->obj())
		{
			if($fldF->Key == "PRI")
			{	
				if(GP($fldF->Field)!="")
				{
					$where .= " WHERE ".$fldF->Field."=".GP($fldF->Field);
					$flg = 1;
				}else{
					$PK = $fldF->Field;
				}
			}
			else if(GP($fldF->Field)!="")
			{
				if($this->chkQuote($fldF->Type)){$quo = "'";}
				if(GP($fldF->Field) == "NOW()"){$quo = "";}
				$sql .= $fldF->Field."=".$quo.GP($fldF->Field).$quo.",";
			}
		}
		if(!$flg)
		{
			print "ERROR... Not found \$_POST[$PK]";
		}else{
			return $this->que(substr($sql,0,-1).$where);
		}
	}

	public function sel($sql)
	{
		$sqlr = "SELECT * FROM ";
		if(count($sql)>1)
		{
			$sqlr .= $sql[0].$this->interPreter($sql[1]);
		}else{$sqlr .= $sql;}

		return $this->que($sqlr);
	}

	private function chkQuote($type)
	{
		if($this->chkTime($type)||$this->chkChar($type))
		{return true;}else{return false;}
	}
	
	private function chkTime($type)
	{
		if($type == "date" || $type == "time" || $type == "datetime")
		{return true;}else{return false;}
	}

	private function chkChar($type)
	{	$type = substr($type,0,4);
		if($type == "char" || $type == "varc" || $type == "text")
		{return true;}else{return false;}
	}

	private function interPreter($str)
	{
		$str = str_replace("~?"," WHERE ",$str);
		$str = str_replace("~>"," DESC ",$str);
		$str = str_replace("~-"," ORDER BY ",$str);
		$str = str_replace("~^"," LIMIT ",$str);
		$str = str_replace("~!"," NOT ",$str);
		$str = str_replace("~&"," AND ",$str);
		$str = str_replace("~|"," OR ",$str);
		$str = str_replace("~%"," LIKE ",$str);
		return $str;	
	}

	public function fld($tb)
	{
		return $this->rs = $this->db->listField($tb);	
	}

	public function tbl()
	{
		return $this->rs = $this->db->listTable();	
	}

	public function str()
	{
		return $this->rs =  $this->db->trsStart();	
	}

	public function cmt()
	{
		return $this->rs =  $this->db->commit();
	}

	public function rbk()
	{
		return $this->rs =  $this->db->roback();
	}

}

global $conn;
$conn = null;

class CoreMySQL
{
	private $hst;
	private $usr;
	private $pwd;
	private $db;
	private $con;
	private $rs;
	public  $st;
	
	function __construct()
	{
		global $cfg;
		$this->hst = $cfg->hst;
		$this->usr = $cfg->usr;
		$this->pwd = $cfg->pwd;
		$this->db = $cfg->db;
	}

	

	public function open()
	{
		global $conn;
		if($conn == null){
		$this->con = @mysql_pconnect($this->hst,$this->usr,$this->pwd);
		$conn =$this->con;
		if(!$this->con){die('Cannot connect to server!');}
		$use = mysql_query("USE ".$this->db);
		if(!$use)
		{
			die('Not found database!');
		}
		else
		{	
			$this->query("SET NAMES utf8");
			$this->query("SET collation_connection utf8_general_ci");
		}
		}
	}
        
	public function openVS()
	{	$this->hst = GS('vshst');
		$this->usr = GS('vsusr');
		$this->pwd = GS('vspwd');
		$this->db = GS('vsdb');
		global $conn;
		if($conn == null){
		$this->con = @mysql_pconnect($this->hst,$this->usr,$this->pwd);
		$conn =$this->con;
		if(!$this->con){$this->st = 0;die("{success:true, conn:0}");}
		$use = mysql_query("USE ".$this->db);
		if(!$use)
		{
			$this->st = -1; 
		}
		else
		{	
			$this->query("SET NAMES utf8");
			$this->query("SET collation_connection utf8_general_ci");
			$this->st = 1;
			
		}
		}
	}

	public function query($sql)
	{ 
		return mysql_query($sql);
	}

	public function close()
	{
		@mysql_close($this->con)	;
	}

	public function fetch_object($result)
	{
		return mysql_fetch_object($result);	
	}

	public function num_row($result)
	{
		return mysql_num_rows($result);
	}

	public function fetch_array($result)
	{
		return mysql_fetch_array($result);	
	}

	public function free_result($result)
	{		
		@mysql_free_result($result);	
	}

	public function listField($tb)
	{
		$sql = "SHOW COLUMNS FROM ".$tb;
		return $this->query($sql);
	} 

	public function listTable()
	{
		$sql = "SHOW TABLES ";
		return $this->query($sql);
	}
	
	public function trsStart()
	{
		$this->query("SET autocommit=0");
		$this->query("START TRANSACTION");
		return true;	
	}

	public function commit()
	{
		$this->query("COMMIT");	
		$this->query("SET autocommit=1");	
		return true;
	}

	public function roback()
	{
		$this->query("ROLLBACK");	
		$this->query("SET autocommit=1");	
		return true;
	}
}

?>
