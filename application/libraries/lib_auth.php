<?php

/**
 *
 * �������� �����: ���������� ����������� 
 *
 * @������ 10.9.2009
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');


class lib_auth {
	
	//��������� �������� �� ������������ ������ � ������
	//� ������ ����� - ������������
	public function do_login($login, $pass) {
		
		$CI = &get_instance ();
		
		//���������� ������
		$user = $CI->M_User->getUser($login);

		//�������� �� ������������
        if(!empty($user)){
    		if (($user['login']==$login) && ($user['pass']==md5(sha1(md5($pass.'test555'))))) {
    			//���� ��������� - ���������� ������
    			$ses = array ();
    			$ses['logined'] = 'ok'; //�����
                $ses['user_id'] = $user['user_id'];
    			//�������������� ������ - ���
    			$ses['hash'] = $this->the_hash();
    			//������
    			$CI->session->set_userdata($ses);
    		
    			//�������� �� �������
    			redirect ('chat/index');
    			
    		} else {
    			//����� - �������� �� ��������� �����
    			redirect ('login');
    		}
        }
		
	}
	
	//��������� �������������� ��� �������� 
	public function the_hash () {
		
		$CI = &get_instance ();
		
		//��������� ���: ������+IP+���.�����
		$hash = md5($CI->config->item('pass').$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].'WINROL');
		
		return $hash;
		
	}
    
	//�������� - �������� �� ����
	public function check_user($redirect=true){
		
		$CI = &get_instance ();
		
		if (($CI->session->userdata('logined')=='ok') && ($CI->session->userdata('hash')==$this->the_hash())) {	
                    $user = $CI->M_User->getUserById($CI->session->userdata('user_id'));
                    if(!$user)
                        $this->logout();
                    else
					   return TRUE; //���� �� � ������� - ������ ������� 
					
				} else {
					
					//����� �������� �� �������� �����
                    if($redirect)
                        redirect ('login');
                    else 
                        return false;
				}
		
	}
	
	//������ - ������ ������
	public function logout(){
		
		$CI = &get_instance ();

		$ses = array ();
		$ses['logined'] = ''; 
		$ses['hash'] = '';
		$ses['user_id'] = '';	
		$CI->session->unset_userdata($ses); //������� ������
		$CI->session->sess_destroy();
		//�������� �� ��������� �����
		redirect ('chat/index');		
	}
    
    
    //
    public function generateStr($length = 30){
		$chars = 'abcdef-_+=()*ghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ123456789';
		$code = "";
		$clen = strlen($chars) - 1;  
        
		while (strlen($code) < $length) {
                $code .= $chars[mt_rand(0, $clen)];
                
        }
        $str = time();
        $str = (string)$str;
        $code.='_'.$str;
		return md5($code);
	}
	
	
}


?>