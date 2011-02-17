<?php

class Amazon_SES_Demo extends Controller
{

	function Amazon_SES_Demo()
	{
		parent::Controller();
		$this->load->library("amazon_email", "", "email");
	}

	function index()
	{
		$this->email->subject("Example Subject");
		$this->email->message("Welcome To The System");
		$this->email->to("user@example.com");
		$this->email->from("person@company.com", "Company Support");

		$this->email->reply_to(array("Name" => "name@example.com"));
		$r = $this->email->send();

		var_dump($r);
	}

}
