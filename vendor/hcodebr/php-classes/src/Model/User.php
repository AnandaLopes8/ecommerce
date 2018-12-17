<?php 
namespace Hcode\Model;

use \Hcode\Model;
use \Hcode\DB\Sql;


class User extends Model{

	const SESSION ="User";
	const SECRET = "hcoprePhp7_Secret";

	public static function login($login, $password){
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin= :LOGIN",array(":LOGIN"=>$login));

		if(count($results)==0){
			throw new \Exception("Usuário inexistente ou senha inválida: 1");
			
		}
		$data = $results[0];

		if(password_verify($password,$data["despassword"])===true){

			$user = new User();
			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getValues();

			return $user;


		}else{
			throw new \Exception("Usuário inexistente ou senha inválida: 2");
			
		}

	}
	public static function verifylogin($inadmin=true){
		if(!isset($_SESSION[User::SESSION])||
			!$_SESSION[User::SESSION]||
			!(int)$_SESSION[User::SESSION]["iduser"]>0||
			(bool)$_SESSION[User::SESSION]["inadmin"]!=$inadmin
		){
			echo"verificando";
			header("Location: /admin/login");
			exit;
		}else{

		}

	}
	public static function logout (){
		$_SESSION[User::SESSION]=NULL;

	}

	public static function listAll(){

		$sql = new Sql();
		return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");

	}
	public function get($iduser){
 
		$sql = new Sql();
		 
		$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser;", array(":iduser" => $iduser
		));
		 
		$data = $results[0];
		 
		$this->setData($data);
	 
	}

	public function save (){

		$sql = new Sql();

		$results = $sql->select("CALL sp_users_save(:desperson, :deslogin,:despassword,:desemail,:nrphone,:inadmin)", array(
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()

		));

		$this->setData($results[0]);
	}
	public function update (){

		$sql = new Sql();

		$results = $sql->select("CALL sp_usersupdate_save(:iduser,:desperson, :deslogin,:despassword,:desemail,:nrphone,:inadmin)", array(
			":iduser"=>$this->getiduser(),
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()

		));

		$this->setData($results[0]);
	}
	public function delete (){

		$sql = new Sql();

		$results = $sql->select("CALL sp_users_delete(:iduser)", array(
			":iduser"=>$this->getiduser()
		));
	}

	public static function getForgot($email){

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_persons INNER JOIN tb_usersb USING (idperson) WHERE a.desemail = :email", array(
			":email"=>$email
		));

		if(count($results)===0){
			throw new Exception("Não foi possivel encontrar o email");
			
		}else{
			$DATA = $results[0];

			$results2 = $sql->select("CALL sp_userpasswordsrecoveries_create(:iduser, :desip", array(
				"iduser"=>$date["iduser"],
				"desip"=>$_SERVER["REMOTE_ADDR"]
			));
			if(count($results2)===0){
				throw new Exception("Não foi possivel encontrar o email");
				
			}else{
				$dataRecovery = $results2[0];
				$code = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, User::SECRET, $dataRecovery["idrecovery"], MCRYPT_MODE_ECB));
				$link = "http://www.ecommerce.com.br/admin/forgot/reset?cod=$code";

				$malier = new Mailer($data["desemail"],$data["desperson"],"redefinir senha Projeto","forgot",array(
					'name' => $data["desperson"],
					"link=>"=> $link
				));
				$malier->send();
				return $data;

			}

		}

	}

	}

 ?>