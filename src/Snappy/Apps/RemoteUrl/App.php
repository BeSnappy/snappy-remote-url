<?php namespace Snappy\Apps\RemoteUrl;

use Snappy\Apps\App as BaseApp;
use Snappy\Apps\ContactLookupHandler;

class App extends BaseApp implements ContactLookupHandler {

	/**
	 * The name of the application.
	 *
	 * @var string
	 */
	public $name = 'Custom Contact Lookup';

	/**
	 * The application description.
	 *
	 * @var string
	 */
	public $description = 'Lookup contact information using a custom, remote URL.';

	/**
	 * Any notes about this application
	 *
	 * @var string
	 */
	public $notes = 'We recommend using HTTPS URLs when using this application.';

	/**
	 * The application's icon filename.
	 *
	 * @var string
	 */
	public $icon = 'remote_url.png';

	/**
	 * The application author name.
	 *
	 * @var string
	 */
	public $author = 'UserScape, Inc.';

	/**
	 * The application author e-mail.
	 *
	 * @var string
	 */
	public $email = 'it@userscape.com';

	/**
	 * The settings required by the application.
	 *
	 * @var array
	 */
	public $settings = array(
		array('name' => 'url', 'type' => 'text', 'help' => 'Enter the URL that will lookup contacts', 'validate' => 'required'),
		array('name' => 'token', 'type' => 'text', 'help' => 'Security token for message verification', 'validate' => 'required'),
	);

	/**
	 * Create a new application instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->settings[1]['value'] = str_random(12);
	}

	/**
	 * Handle a contact look-up request.
	 *
	 * @param  array  $contact
	 * @return array
	 */
	public function handleContactLookup(array $contact)
	{
		$guzzle = new \Guzzle\Http\Client;
		$guzzle->setSslVerification(false);

		$request = $guzzle->post($this->config['url']);
		$request->setPostField('contact', json_encode($contact));
		$request->setPostField('token', $this->config['token']);

		try
		{
			return purify((string) $request->send()->getBody());
		}
		catch (\Exception $e)
		{
			return $e->getMessage();
		}
	}

}