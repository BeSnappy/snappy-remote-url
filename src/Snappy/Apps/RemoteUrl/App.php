<?php namespace Snappy\Apps\RemoteUrl;

use Snappy\Apps\App as BaseApp;
use Snappy\Apps\ContactLookupHandler;

class App extends BaseApp implements ContactLookupHandler {

	/**
	 * The name of the application.
	 *
	 * @var string
	 */
	public $name = 'Application Name';

	/**
	 * The application description.
	 *
	 * @var string
	 */
	public $description = 'Application Description';

	/**
	 * Any notes about this application
	 *
	 * @var string
	 */
	public $notes = 'Application Notes';

	/**
	 * The application's icon filename.
	 *
	 * @var string
	 */
	public $icon = 'icon.png';

	/**
	 * The application author name.
	 *
	 * @var string
	 */
	public $author = 'Your Name';

	/**
	 * The application author e-mail.
	 *
	 * @var string
	 */
	public $email = 'Your Email';

	/**
	 * The settings required by the application.
	 *
	 * @var array
	 */
	public $settings = array(
		array('name' => 'account', 'type' => 'text', 'help' => 'Enter your Highrise Account Name'),
		array('name' => 'token', 'type' => 'text', 'help' => 'Security token for message verification'),
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

		$request = $guzzle->post($this->config['url']);
		$request->setPostField('contact', json_encode($contact));
		$request->setPostField('token', $this->config['token']);

		try
		{
			return (string) $request->send()->getBody();
		}
		catch (\Exception $e)
		{
			return $e->getMessage();
		}
	}

}