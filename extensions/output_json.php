<?php


class output_json{
	
	public $return;

	public function __construct($data, $show = 'display'){
			$this->setHeaders();
			if(system::request()[0] == 'admin'){
				$output = $this->handleRequestAdmin($data);
			} else {
				$output = $this->handleRequest($data);
			}
            $this->giveOutput($output, $show);           
	}

	private function setHeaders(){
		header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
	}

	private function giveOutput($output, $show){
		if($show == 'display'){
			if(is_array($output)){
				$this->return = json_encode($output);
			} else {
				$this->return = json_encode(array($output));
			}
	    	echo $this->return;
	    } else {
	    	$this->return = json_encode($output);
	    } 
	}

	private function handleRequestAdmin($data){
		$request = system::request();
		foreach($request as $key=>$value){
			if(empty($value)){
				unset($request[$key]);
			}
		}
        if(count($request) > 2){
        	unset($request[0]);
        	if(count($request) > 1){
        		switch(count($request)){
        			case 2:
        				$output = $data[$request[1]][$request[2]];
        				break;
        			case 3:
        				$output = $data[$request[1]][$request[2]][$request[3]];
        				break;
        			case 4:
        				$output = $data[$request[1]][$request[2]][$request[3]][$request[4]];
        				break;
        			case 5:
        				$output = $data[$request[1]][$request[2]][$request[3]][$request[4]][$request[5]];
        				break;
        		}
        	} else {
        		$output = $data[$request[2]];
        	}
        } else {
        	$output = $data;
        }
        return $output;
	}

	private function handleRequest($data){
		$request = system::request();
		foreach($request as $key=>$value){
			if(empty($value)){
				unset($request[$key]);
			}
		}
        if(count($request) > 2){
        	unset($request[0], $request[1]);
        	if(count($request) > 1){
        		switch(count($request)){
        			case 2:
        				$output = $data[$request[2]][$request[3]];
        				break;
        			case 3:
        				$output = $data[$request[2]][$request[3]][$request[4]];
        				break;
        			case 4:
        				$output = $data[$request[2]][$request[3]][$request[4]][$request[5]];
        				break;
        			case 5:
        				$output = $data[$request[2]][$request[3]][$request[4]][$request[5]][$request[6]];
        				break;
        		}
        	} else {
        		$output = $data[$request[2]];
        	}
        } else {
        	$output = $data;
        }
        return $output;
	}

}


?>