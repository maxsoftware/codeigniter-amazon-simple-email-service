<?php

class Amazon_SES
{

	private $_recipients, $_message, $_from, $_reply_to;
	

	function __construct()
	{
		$this->_recipients = array( "to" => array(), "cc" => array(), "bcc" => array() );
		$this->_message = array( "subject" => "", "body" => "");

		$this->_ci = &get_instance();
		$this->_ci->load->helper("email_helper");
	}

	function to($recipients)
	{
		$this->_add_recipient("to", $recipients);
	}

	function cc($recipients)
	{
		$this->_add_recipient("cc", $recipients);
	}

	function bcc($recipients)
	{
		$this->_add_recipient("bcc", $recipients);
	}

	function from($email, $name)
	{

	}

	function reply_to($email, $name)
	{

	}

	function subject($sub)
	{

	}

	function message($message)
	{

	}

	function set_alt_message($message)
	{
		show_error("Amazon SES: Sorry, this function is not yet supported");
	}

	function clear()
	{

	}

	function send()
	{

	}

	function attach()
	{
		show_error("Amazon SES: Sorry, this function is not yet supported");
	}

	function print_debugger()
	{
		
	}

	function _add_recipient($type, $recipients)
	{
		if ( empty($recipients) )
		{
			show_error("Amazon SES: No Recipients Provided");
		}

		if ( is_array($recipients) )
		{
			foreach ($recipients as $recip)
			{
				if (! valid_email($recip) )
				{
					show_error("Amazon SES: Invalid Email");
				}
				$this->_recipients[$type][] = $recip;
			}
			return;
		}

		if (! valid_email($recipients) )
		{
			show_error("Amazon SES: Invalid Email");
		}
		$this->_recipients[$type][] = $recipients;
	}

	function debug()
	{
		echo '<pre>';
		print_r($this->_recipients);
		echo '</pre>';
	}

}
