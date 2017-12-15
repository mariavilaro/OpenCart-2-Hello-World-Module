<?php

/*
 *
 */

class ControllerExtensionModuleHelloworld extends Controller {
	
	private $error = array(); // This is used to set the errors, if any.
 
	/*
	 *
	 */
	
	public function index() {   // Default function 
		
		$this->load->language('module/helloworld'); // Loading the language file of helloworld 
	 
		$this->document->setTitle($this->language->get('heading_title')); // Set the title of the page to the heading title in the Language file i.e., Hello World
	 
		$this->load->model('setting/setting'); // Load the Setting Model  (All of the OpenCart Module & General Settings are saved using this Model )
	 
	 
	 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			// Validates and check if data is coming by save (POST) method
			
			$this->model_setting_setting->editSetting('helloworld', $this->request->post); // Parse all the coming data to Setting Model to save it in database.	 
			$this->session->data['success'] = $this->language->get('text_success'); // To display the success text on data save	 
			$this->response->redirect($this->url->link('extension/module/helloworld', 'token=' . $this->session->data['token'], 'SSL'));
			//$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], true));
			//$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')); // Redirect to the Module Listing
			
		} 
	 
		// Assign the language data for parsing it to view 
		$data['heading_title'] = $this->language->get('heading_title');
	 
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');      
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
	 
		$data['entry_code'] = $this->language->get('entry_code');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
	 
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_remove'] = $this->language->get('button_remove');
		 
	 
		// This Block returns the warning if any
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
	 
		// This Block returns the error code if any
		if (isset($this->error['code'])) {
			$data['error_code'] = $this->error['code'];
		} else {
			$data['error_code'] = '';
		}		
	 
	 
		/* Making of Breadcrumbs to be displayed on site*/
		$data['breadcrumbs'] = array();
	 
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
	 
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
	 
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/helloworld', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
	 
	 
		$data['action'] = $this->url->link('extension/module/helloworld', 'token=' . $this->session->data['token'], 'SSL'); // URL to be directed when the save button is pressed 
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'); // URL to be redirected when cancel button is pressed
	 
		
		// This block checks, if the hello world text field is set it parses it to view otherwise get the default hello world text field from the database and parse it 
		if (isset($this->request->post['helloworld_text_field'])) {
			$data['helloworld_text_field'] = $this->request->post['helloworld_text_field'];
		} else {
			$data['helloworld_text_field'] = $this->config->get('helloworld_text_field');
		}   
	 
		/*$data['modules'] = array();
	 
		// This block parses the Module Settings such as Layout, Position, Status, & Order Status to the view
		if (isset($this->request->post['helloworld_module'])) {
			$data['modules'] = $this->request->post['helloworld_module'];
		} elseif ($this->config->get('helloworld_module')) { 
			$data['modules'] = $this->config->get('helloworld_module');
		}*/
		
		// This block parses the status (enabled / disabled)
        if (isset($this->request->post['helloworld_status'])) {
            $data['helloworld_status'] = $this->request->post['helloworld_status'];
        } else {
            $data['helloworld_status'] = $this->config->get('helloworld_status');
        }
	 
		$this->load->model('design/layout'); // Loading the Design Layout Models
	 
		$data['layouts'] = $this->model_design_layout->getLayouts(); // Getting all the Layouts available on system
	 
		/*$this->template = 'module/helloworld.tpl'; // Loading the helloworld.tpl template
		$this->children = array(
			'common/header',
			'common/footer'
		);  // Adding children to our default template i.e., helloworld.tpl */
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
	 
		$this->response->setOutput($this->load->view('extension/module/helloworld.tpl', $data)); // Rendering the Output
		//$this->load->view('extension/module/helloworld.tpl',$data);
		
	}
	
	/*
	 * Function that validates the data when Save Button is pressed 
	 */
	
    protected function validate() {
 
        // Block to check the user permission to manipulate the module
        if (!$this->user->hasPermission('modify', 'extension/module/helloworld')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
 
        // Block to check if the helloworld_text_field is properly set to save into database, otherwise the error is returned
        if (!$this->request->post['helloworld_text_field']) {
            $this->error['code'] = $this->language->get('error_code');
        }
		
        // Block returns true if no error is found, else false if any error detected
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
		
    }
	
}

?>
