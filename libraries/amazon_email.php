<?php

class Amazon_Email
{

	private $_config, $_recipients, $_message, $_from, $_reply_to, $_ses;
	

	function __construct($conf = array())
	{

		if ( !class_exists("Amazon_SES") )
		{
			require_once("AWSSDKforPHP/sdk.class.php");
		}

		$this->_ci = &get_instance();
		$this->_ci->load->helper("email_helper");

		$this->initialize($conf);
		$this->clear();
	}

	function initialize($conf)
	{
		$this->_config = $conf;
		if (isset($this->_config['aws_access_key'])){
			$this->_ses = new AmazonSES($this->_config['aws_access_key'], $this->_config['aws_secret_key']);
		}
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

	function from($email, $name = null)
	{
		if ( is_null($name) )
		{
			$name = $email;
		}

		$this->_from = array( "name" => $name, "email" => $email);
	}

	function reply_to($email, $name=null)
	{
		if ( empty($email) )
		{
			show_error("Amazon SES: No Reply To Address Provided");
		}

		if ( is_null($name) )
		{
			$name = $email;
		}

		if ( is_array($email) )
		{
			foreach ($email as $name => $e)
			{
				if (! valid_email($e) )
				{
					show_error("Amazon SES: Invalid Email");
				}
				$this->_reply_to[] = $name . ' <'.$e.'>';
			}
			return;
		}

		if (! valid_email($email) )
		{
			show_error("Amazon SES: Invalid Email");
		}
		$this->_reply_to[] = $name . ' <'.$email.'>';
	}

	function subject($sub)
	{
		$this->_message['subject'] = $sub;
	}

	function message($message)
	{
		$this->_message['body_html'] = $message;
	}

	function set_alt_message($message)
	{
		$this->_message['body_text'] = $message;
	}

	function clear()
	{
		$this->_recipients = array( "to" => array(), "cc" => array(), "bcc" => array() );
		$this->_message = array( "subject" => "", "body_html" => "", "body_text" => "");
	}

	function send()
	{

		if ( $this->_message['subject'] == "")
		{
			show_error("Amazon SES: Missing Subject");
		}

		// Fix the data so it's actually fine

		// Message
		if ( $this->_message['body_text'] == "")
		{
			$this->_message['body_text'] = strip_tags($this->_message['body_html']);
		}

		if ( $this->_message['body_html'] == "")
		{
			$this->_message['body_html'] = $this->_message['body_text'];
		}

		if ( $this->_message['body_text'] == "")
		{
			show_error("Amazon SES: Missing Body Content");
		}

		// Recipients
		if ( empty($this->_recipients["to"]) )
		{
			show_error("Amazon SES: No recipient specified in the To field");
		}

		// Additional Options
		$opt = array();

		// Reply to?
		if ( !empty($this->_reply_to) )
		{
			$opt['ReplyToAddresses'] = $this->_reply_to;
		}

		// Actually send via SES
		$response = $this->_ses->send_email(
			$this->_from['name'] . ' <'. $this->_from['email'] .'>',
			array('ToAddresses' => $this->_recipients["to"], 'CcAddresses' => $this->_recipients["cc"], 'BccAddresses' => $this->_recipients["bcc"]),
			array(
				'Subject.Data' => $this->_message['subject'],
				'Body.Text.Data' => $this->_message['body_text'],
				'Body.Html.Data' => $this->_message['body_html']
			), $opt
		);

		// Success?
		$resp = (object) array(
			"success" => $response->isOk(),
			"http_code" => $response->status
		);

		return $resp->success;		
	}

	function attach()
	{
		show_error("Amazon SES: Sorry, this function is not yet supported");
	}

	function print_debugger()
	{
		
	}

	// CI Compat
	function set_newline($newline = "\n")
	{
		if ($newline != "\n" AND $newline != "\r\n" AND $newline != "\r")
		{
			$this->newline	= "\n";
			return;
		}

		$this->newline	= $newline;
	}


	// Private

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
}
