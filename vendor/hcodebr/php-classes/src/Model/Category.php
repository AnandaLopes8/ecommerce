<?php 
namespace Hcode\Model;

use \Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Mailer;


class Category extends Model{
	

	public static function listAll(){

		$sql = new Sql();
		return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");

	}
	
	public function save (){
		$sql = new Sql();
		$results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(
			":idcategory"=>$this->getidcategory(),
			":descategory"=>$this->getdescategory()
		));
		$this->setData($results[0]);
	}


	public function get($idcategory){
 
		$sql = new Sql();
		 
		$results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory;", array(":idcategory" => $idcategory
		));
		 
		$data = $results[0];
		 
		$this->setData($data);
	 
	}

	public function delete (){

		$sql = new Sql();

		$results = $sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory;", array(":idcategory" => $this->getidcategory()));
	}
}?>