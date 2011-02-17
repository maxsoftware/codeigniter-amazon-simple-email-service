<?php

class Amazon_SES_Demo extends Controller
{

	function Amazon_SES_Demo()
	{
		parent::Controller();
		$this->load->library("amazon_ses", array(), "email");
	}

	function index()
	{
		$this->email->cc(array( "wayne.bloore@maxswl.com", "steve.frost@maxswl.com" ) );
		$this->email->bcc("m@michaelaheap.com");
		$this->email->debug();
	}

}
