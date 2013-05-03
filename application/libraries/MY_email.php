<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Email extends CI_Email {

	/**
	 * Overrides function, sends email with the codeigniter class
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return bool
	 * @author Alan Rodriguez
	 **/
	function send_email($email,$subject,$message,$attachment = NULL)
	{
		// Load Library
		//$CI->load->library('email');

		// Used for security
		$this->set_newline("\r\n");

		// Create Message
		$this->from('noreply@andrescacho.com', 'Andrés Cacho');
		$this->to($email);
		$this->subject($subject);
		$this->message($message);

		return ($this->send()) ? true : false ;
	}

}