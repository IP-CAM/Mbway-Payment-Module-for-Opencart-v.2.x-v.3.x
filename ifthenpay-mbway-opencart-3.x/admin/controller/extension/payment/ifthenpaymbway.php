<?php
class ControllerExtensionPaymentIfthenpayMbway extends Controller {

    private $error = array();

    public function index() {

        $this->load->language('extension/payment/ifthenpaymbway');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $post_info = $this->request->post;
            
            $this->model_setting_setting->editSetting('payment_ifthenpaymbway',  $post_info);

			//$callback_sent = $this->sendCallbackEmail();
			//$post_info["payment_ifthenpaymbway_cb_sent"] = $callback_sent;
			//$this->model_setting_setting->editSetting('payment_ifthenpaymbway',  $post_info);

			$this->session->data['success'] = $this->language->get('text_success');

			//$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
			$this->response->redirect($this->url->link('extension/payment/ifthenpaymbway', 'user_token=' . $this->session->data['user_token'], true));
		}

        $data['heading_title'] = $this->language->get('heading_title');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['text_success'] = (isset($this->session->data['success'])?$this->session->data['success']:"");

		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_order_status_complete'] = $this->language->get('entry_order_status_complete');
		$data['entry_mbwkey'] = $this->language->get('entry_mbwkey');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_cb'] = $this->language->get('entry_cb');
		$data['entry_url'] = $this->language->get('entry_url');
		$data['entry_ap'] = $this->language->get('entry_ap');

		//send callback stuff
		$data['entry_button_send_cb'] = $this->language->get('entry_button_send_cb');
		$data['button_send_cb'] = $this->language->get('button_send_cb');
		$data['text_send_cb'] = $this->language->get('text_send_cb');


		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['tab_general'] = $this->language->get('tab_general');

		//user token
		$data['user_token'] = $this->session->data['user_token'];
		$data['email_cb_sended'] = $this->config->get('payment_ifthenpaymbway_cb_sent');
		$data['email_confirmation'] = $this->language->get('email_confirmation');
		$data['email_sended_info'] = $this->language->get('email_sended_info');
		$data['email_success_info'] = $this->language->get('email_success_info');
		$data['email_error_info'] = $this->language->get('email_error_info');
		
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/ifthenpaymbway', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/payment/ifthenpaymbway', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);


        if (isset($this->request->post['payment_ifthenpaymbway_mbwkey'])) {
            $data['payment_ifthenpaymbway_mbwkey'] = $this->request->post['payment_ifthenpaymbway_mbwkey'];
        } else {
            $data['payment_ifthenpaymbway_mbwkey'] = $this->config->get('payment_ifthenpaymbway_mbwkey');
        }


        if (isset($this->request->post['payment_ifthenpaymbway_order_status_id'])) {
            $data['payment_ifthenpaymbway_order_status_id'] = $this->request->post['payment_ifthenpaymbway_order_status_id'];
        } else {
            $data['payment_ifthenpaymbway_order_status_id'] = $this->config->get('payment_ifthenpaymbway_order_status_id');
        }
        
        if (isset($this->request->post['payment_ifthenpaymbway_order_status_complete_id'])) {
			$data['payment_ifthenpaymbway_order_status_complete_id'] = $this->request->post['payment_ifthenpaymbway_order_status_complete_id'];
		} else {
			$data['payment_ifthenpaymbway_order_status_complete_id'] = $this->config->get('payment_ifthenpaymbway_order_status_complete_id');
		}

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['payment_ifthenpaymbway_geo_zone_id'])) {
            $data['payment_ifthenpaymbway_geo_zone_id'] = $this->request->post['payment_ifthenpaymbway_geo_zone_id'];
        } else {
            $data['payment_ifthenpaymbway_geo_zone_id'] = $this->config->get('payment_ifthenpaymbway_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();


        if (isset($this->request->post['payment_ifthenpaymbway_status'])) {
            $data['payment_ifthenpaymbway_status'] = $this->request->post['payment_ifthenpaymbway_status'];
        } else {
            $data['payment_ifthenpaymbway_status'] = $this->config->get('payment_ifthenpaymbway_status');
        }

        if (isset($this->request->post['payment_ifthenpaymbway_sort_order'])) {
            $data['payment_ifthenpaymbway_sort_order'] = $this->request->post['payment_ifthenpaymbway_sort_order'];
        } else {
            $data['payment_ifthenpaymbway_sort_order'] = $this->config->get('payment_ifthenpaymbway_sort_order');
        }

		$data['payment_ifthenpaymbway_show_ap'] = true;
        

		if (isset($this->request->post['payment_ifthenpaymbway_ap'])) {
			$data['payment_ifthenpaymbway_ap'] = $this->request->post['payment_ifthenpaymbway_ap'];
		} else {

			$anti_phishing = $this->config->get('payment_ifthenpaymbway_ap');

			if(empty($anti_phishing)) {
				$anti_phishing = substr(hash('sha512', $this->config->get('config_name') . $this->config->get('config_title') . $this->config->get('config_owner') . $this->config->get('config_email') . date("D M d, Y G:i")), -50);

				$data['payment_ifthenpaymbway_ap'] 
					= $anti_phishing;
				$data['payment_ifthenpaymbway_show_ap'] = false;
				$this->model_setting_setting->editSetting('payment_ifthenpaymbway',  $data);
				
			} else {
				$data['payment_ifthenpaymbway_ap'] = $anti_phishing;
			}
		}
		
		$data['payment_ifthenpaymbway_url'] = ($this->config->get('config_secure') ? rtrim(HTTP_CATALOG, '/') : rtrim(HTTPS_CATALOG, '/')) . "/index.php?route=extension/payment/ifthenpaymbway/callback&chave=[CHAVE_ANTI_PHISHING]&referencia=[REFERENCIA]&idpedido=[ID_TRANSACAO]&valor=[VALOR]&estado=[ESTADO]";
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('extension/payment/ifthenpaymbway', $data));

    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/payment/ifthenpaymbway')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }
	
	private function sendCallbackEmail(){

		$mbway_key = $this->request->post['payment_ifthenpaymbway_mbwkey'];
		$url_cb = ($this->config->get('config_secure') ? rtrim(HTTP_CATALOG, '/') : rtrim(HTTPS_CATALOG, '/')) . "/index.php?route=extension/payment/ifthenpaymbway/callback&chave=[CHAVE_ANTI_PHISHING]&referencia=[REFERENCIA]&idpedido=[ID_TRANSACAO]&valor=[VALOR]&estado=[ESTADO]";

		$ap_key_cb = $this->request->post['payment_ifthenpaymbway_ap'];

		$sent_ap =$this->config->get('payment_ifthenpaymbway_cb_sent') ;

		if(!empty($mbway_key) && !empty($url_cb) && !empty($ap_key_cb) && !$sent_ap){

			$store_name = $this->config->get('config_name');

			$msg = "Ativar Callback MBWAY para loja Opencart \n\n";
			$msg .= "MBWAY KEY: $mbway_key \n\n";
			$msg .= "Chave Anti-Phishing: $ap_key_cb \n\n";
			$msg .= "Url Callback:: $url_cb \n\n\n\n\n\n";
			$msg .= "Pedido enviado automaticamente pelo sistema OpenCart da loja [$store_name]";

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));

			$mail->setSubject("Ativar Callback MBWAY");
			$mail->setText($msg);

			$mail->setTo("callback@ifthenpay.com");
			$mail->send();

			return true;
		}

		return $sent_ap;
	}

	public function activatecallback(){
		$json = array();
		$json['sended']=false;
		//load settings model
		$this->load->model('setting/setting');

		$settings = $this->model_setting_setting->getSetting('payment_ifthenpaymbway'); 
		$mbway_key = $settings['payment_ifthenpaymbway_mbwkey'];
		$url_cb = ($this->config->get('config_secure') ? rtrim(HTTP_CATALOG, '/') : rtrim(HTTPS_CATALOG, '/')) . "/index.php?route=extension/payment/ifthenpaymbway/callback&chave=[CHAVE_ANTI_PHISHING]&referencia=[REFERENCIA]&idpedido=[ID_TRANSACAO]&valor=[VALOR]&estado=[ESTADO]";
		$ap_key_cb = $settings['payment_ifthenpaymbway_ap'];

		if(!empty($mbway_key) && !empty($url_cb) && !empty($ap_key_cb)){
			$store_name = $this->config->get('config_name');

			$msg = "Ativar Callback MBWAY para loja Opencart \n\n";
			$msg .= "MBWAY KEY: $mbway_key \n\n";
			$msg .= "Chave Anti-Phishing: $ap_key_cb \n\n";
			$msg .= "Url Callback:: $url_cb \n\n\n\n\n\n";
			$msg .= "Pedido enviado pelo sistema OpenCart da loja [$store_name]";

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));

			$mail->setSubject("Ativar Callback MBWAY");
			$mail->setText($msg);

			$mail->setTo("callback@ifthenpay.com");
			$mail->send();

			//atualizar settings
			$settings["payment_ifthenpaymbway_cb_sent"] = true;
			$this->model_setting_setting->editSetting('payment_ifthenpaymbway',  $settings);

			$json['sended']=true;
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}

?>
