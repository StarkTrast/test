<?php
namespace UserEntryAndVerification;              //  Написал все в одном файле, но его надо разделить на файл классов и файл пользовательского кода 

abstract class UserEntryAndVerification {		//   Абстрактный класс для поределения структуры классов записи пользователя
	protected  $request = array(); 
		function __construct($request) {
			$this -> reques = $request;
		}
		function UserVerification () {			//   эта функци определена здесь так как она общая для всех классов записи пользователя
			$ans= array (						//   массив ответа верификации данных
				"success" => true,
				"action" => "verification",
				"resp" => array ("name"  => "",
								 "surName"  => "",
								 "pass"  => "",
								 "email" => "")
			);
			if ($this -> reques['pass'] != $this -> reques['repeatPass'] ) {
				$ans['success'] = false;
				$ans['resp']['pass'] = "Вы не ввели пароль или пароль не совпадант с его повторением";
			} 
			if (strripos($this -> reques['email'], "@" ) === false){
				$ans['success'] = false;
				$ans['resp']['email'] = "Вы ввели  некорректный email";
			} 
			if ($this -> reques['name'] == ""){
				$ans['success'] = false;
				$ans['resp']['name'] = "Вы не указали Ваше имя";
			} 
			if ($this -> reques['surName'] == ""){
				$ans['success'] = false;
				$ans['resp']['surName'] = "Вы не указали Вашу фамилию";
			}
			return $ans;
		}
		abstract function UserEntry () ;									
}

class WhereEntry {															//    класс для создания объектов записи пользователя
	private  $whereEntry ; 
	function __construct($whereEntry) {
		$this -> whereEntry = $whereEntry;
	}
	function GetClass ($request) {
		switch ($this -> whereEntry) {
			case 1:
				return new  UserEntryToFile ($request);
			case 2:
				return new  UserEntryToDB ($request);
		}
	}
}

class UserEntryToFile extends UserEntryAndVerification {                  //      класс для записи пользователей в файл
	function UserEntry () {
		$ans= array (
				"success" => true,
				"action" => "entry",
				"resp" => array ("text"  => "")
			);
		$date=date("Y-m-d-H-i-s");
		$fp=fopen("log.txt", "a");
		$allUser=json_decode(file_get_contents("user.txt"), true);
			if (!isset($allUser[$this -> reques['email']])) {
				$id=$date."-".rand(1, 1000);
				$allUser[$this -> reques['email']] = array (
					'name' => $this -> reques['name'],
					'surName' => $this -> reques['surName'],
					'pass' => $this -> reques['pass'],
					'date' => $date,
					'id' => $id
				);
				file_put_contents("user.txt", json_encode($allUser)); 								//    запись пользователя в файл
				fwrite($fp, $date." Add new User ".$this -> reques['email']." ID- ".$id." \r\n");	//    запись в лог
			} else {
				$ans['success'] = false;
				$ans['resp']['text'] = "Такой пользователь уже существует";
				fwrite($fp, $date." This user already exists ".$this -> reques['email']." ID- ".$allUser[$this -> reques['email']]['id']." \r\n");
			}
		fclose($fp);
		return $ans;
	}
}


class UserEntryToDB extends UserEntryAndVerification {          //  класс для записи пользователей в базу данных
	function UserEntry () {	
	}
}



	//    пользовательский код 

 $chek = new WhereEntry(1);
 $user = $chek-> GetClass($_POST);
 $veref = $user -> UserVerification();
  if(!$veref['success'])  {
	echo json_encode($veref);
  } else {
	 $entryUser = $user -> UserEntry();
	 echo json_encode($entryUser);
  };