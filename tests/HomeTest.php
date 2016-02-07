<?php

class HomeTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function rootAvailable()
	{
		$response = $this->call('GET', '/');

		$this->assertEquals(200, $response->getStatusCode());
	}

}
