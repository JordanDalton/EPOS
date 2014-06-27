<?php

class LoginControllerTest extends TestCase {

	public function tearDown()
	{
		Mockery::close();
	}

	public function test_displays_form_to_submit_login_request()
	{
		$this->call('GET', 'login');

		$this->assertResponseOk();
	}

	public function test_submits_login_request_upon_form_submissions()
	{
		$postData = [
			'username' => '[some_username]',
			'password' => '[some_password]'
		];

		$this->call('POST', 'login', $postData);

		$this->assertRedirectedToRoute('pos.index', null);
	}
}