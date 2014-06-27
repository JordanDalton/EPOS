<?php namespace Acme\Events;

use Acme\Interfaces\Db\UserRepositoryInterface as User;
use App, Artisan, Auth, Ldap, Mail;

class PoEventHandler {

	/** 
	 * The user repository implementation.
	 *  
	 * @var Acme\Repositories\Db\UserRepository
	 */
	protected $users;

	/**
	 * Create new PoEventHandler instance.
	 * 
	 * @param User $users
	 * @return
	 */
	public function __construct( User $users )
	{
		$this->users = $users;
	}

	/**
	 * Handle on po created event.
	 */
	public function onPoCreated($record)
	{
		$fetchLdapManager = Ldap::searchForUser( Auth::user()->manager );

		// Check and see if the user exists in our database.
		// 
		$user = $this->users->findByUsername( $fetchLdapManager['username'] );

		// The user doesn't exist.
		// 
		if( is_null($user) )
		{
			// Create a new user record.
			// 
			$user = $this->users->create( $fetchLdapManager );
		}

		// Now save the changes.
		// 
		$record->save();

		// If the recors is not marked as a draft we will need to notify
		// the accounting department.
		// 
		if( $record->draft == 0 )
		{
			// Prepare the data that is to be inserted into the email.
			// 
			$data = array();
			$data['data']['id'] = $record->getPoIdentifier();

			// Now dispatch an email to to the manager
			// 
			Mail::queue(array('emails.notifications.accounting-html','emails.notifications.accounting-text'), $data, function($message) use($record)
			{
				// Set the to address.
				// 
				$to = App::environment() === 'development' 
					? Auth::user()->email
					: 'jordandalton@wrsgroup.com';

				$message->to($to)->subject('New Purchase Order Requires Your Approval');
			});

			// Process the next job on a queue
			// 
			Artisan::call('queue:work');
		}
	}

	/**
	 * Handle on po accountant approved
	 */
	public function onAccountantApproved($record){}

	/**
	 * Handle on po manager approved
	 */
	public function onManagerApproved($record){}

	/**
	 * Handle on po updated event.
	 */
	public function onPoUpdated($record)
	{
		// Obtain the original draft value prior to the update.
		// 
		$original_draft = $record->getOriginal('draft', 0);

		// If the recors is not marked as a draft we will need to notify
		// the accounting department.
		// 
		if( $original_draft == 1 && $record->draft == 0 )
		{
			// Prepare the data that is to be inserted into the email.
			// 
			$data = array();
			$data['data']['id'] = $record->getPoIdentifier();

			// Now dispatch an email to to the manager
			// 
			Mail::queue(array('emails.notifications.accounting-html','emails.notifications.accounting-text'), $data, function($message) use($record)
			{
				// Set the to address.
				// 
				$to = App::environment() === 'development' 
					? Auth::user()->email
					: 'jordandalton@wrsgroup.com';

				$message->to($to)->subject('New Purchase Order Requires Your Approval');
			});

			// Process the next job on a queue
			// 
			Artisan::call('queue:work');
		}
	}

	/** 
	 * Register the listeners for the subscriber.
	 * 
	 * @param  Illuminate\Events\Dispatcher $events 
	 * @return array
	 */
	public function subscribe($events)
	{
		$events->listen('po.created', 'PoEventHandler@onPoCreated');
		$events->listen('po.updated', 'PoEventHandler@onPoUpdated');
		$events->listen('po.accountant.approved', 'PoEventHandler@onAccountantApproved');
		$events->listen('po.manager.approved', 'PoEventHandler@onManagerApproved');
	}
}