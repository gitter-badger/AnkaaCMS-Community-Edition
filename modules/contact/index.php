<?php




class Contact extends Main{


	function __construct($attributes){
		$this->ContactAssign[$attributes['location']]['template'] = getcwd().'/modules/contact/forms.tpl';
		$this->ContactAssign[$attributes['location']]['module'] = 'Contact';

		$i=0;
		$this->ContactAssign[$attributes['location']]['fields'][$i]['title'] = 'Bedrijfsnaam';
		$this->ContactAssign[$attributes['location']]['fields'][$i]['type'] = 'text';
		$this->ContactAssign[$attributes['location']]['fields'][$i]['name'] = 'company_name';

		$i=1;
		$this->ContactAssign[$attributes['location']]['fields'][$i]['title'] = 'Contactpersoon';
		$this->ContactAssign[$attributes['location']]['fields'][$i]['type'] = 'text';
		$this->ContactAssign[$attributes['location']]['fields'][$i]['name'] = 'contact_name';

		$i=2;
		$this->ContactAssign[$attributes['location']]['fields'][$i]['title'] = 'Telefoonnummer';
		$this->ContactAssign[$attributes['location']]['fields'][$i]['type'] = 'text';
		$this->ContactAssign[$attributes['location']]['fields'][$i]['name'] = 'contact_phone';

		$i=3;
		$this->ContactAssign[$attributes['location']]['fields'][$i]['title'] = 'E-mailadres';
		$this->ContactAssign[$attributes['location']]['fields'][$i]['type'] = 'text';
		$this->ContactAssign[$attributes['location']]['fields'][$i]['name'] = 'contact_email';

		$i=4;
		$this->ContactAssign[$attributes['location']]['fields'][$i]['title'] = 'Opmerking';
		$this->ContactAssign[$attributes['location']]['fields'][$i]['type'] = 'textarea';
		$this->ContactAssign[$attributes['location']]['fields'][$i]['name'] = 'contact_response';

		$i=5;
		$this->ContactAssign[$attributes['location']]['fields'][$i]['title'] = 'Verstuur';
		$this->ContactAssign[$attributes['location']]['fields'][$i]['type'] = 'submit';
		$this->ContactAssign[$attributes['location']]['fields'][$i]['name'] = 'submit';
	}

}





?>