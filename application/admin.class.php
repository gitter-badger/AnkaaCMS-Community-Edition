<?php

class admin{

	private $loggedin;
	public $output;


	public function __construct(){		
		if(system::request()[0] == 'admin'){
			$this->run();
		}
	}

	private function run(){
		if(isset($_SESSION['user'])){
			if($this->checkUser === TRUE){
				$this->loggedin = TRUE;
			} else {
				$this->loggedin = FALSE;
			}
		} else{
			$this->loggedin = FALSE;
		}

		if($this->loggedin === TRUE){
			if(count(system::request()) > 2){
				if(!empty(system::request()[1])){
					$this->loadModule(system::request()[1]);
				}
			} else {
				$this->dashboard();
			}
		} else {
			$this->showLoginscreen();
		}
	}

	private function showLoginscreen(){
		$this->output['template'] = 'fe.tpl';
	}

	private function loadModule($mod){
		include('modules/'.$mod.'/admin_index.tpl');
	}

	private function dashboard(){
		echo 'dash';
	}


}

?>