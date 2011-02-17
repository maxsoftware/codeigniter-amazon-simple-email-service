<?php

class Amazon_SES
{

	private $_recipients, $_message, $_from, $_reply_to;
	

	function __construct()
	{
		$this->_recipients = array( "to" => array(), "cc" => array(), "bcc" => array() );
		$this->_message = array( "subject" => "", "body" => "");
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
		show_error("Sorry, this function is not yet supported");
	}

	function clear()
	{

	}

	function send()
	{

	}

	function attach()
	{
		show_error("Sorry, this function is not yet supported");
	}

	function print_debugger()
	{
		
	}

	function _add_recipient($type, $recipients)
	{

	}

}
