<?php

/**
 *
 */
 
class ControllerExtensionModuleHelloworld extends Controller {
	
	public function index(){
		
		$this->language->load('extension/module/helloworld'); // loads the language file of helloworld
 
		$data['heading_title'] = $this->language->get('heading_title'); // set the heading_title of the module
		 
		$data['helloworld_value'] = html_entity_decode($this->config->get('helloworld_text_field')); // gets the saved value of helloworld text field and parses it to a variable to use this inside our module view
		 
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/helloworld.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/extension/module/helloworld.tpl', $data);
        } else {
            return $this->load->view('extension/module/helloworld.tpl', $data);
        }
		
	}

}

?>
