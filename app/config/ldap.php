<?php

return array(

	// LDAP Admin Credentials.
	// 
	'admin' => array(
		'username' => 'username', 
		'password' => 'password'
	),

	// Distinguished Name
	// 
	'dn' => 'CN=Users,DC=yourdomain,DC=com',

	// Response fields.
	// 
    'fields' => array(
    	'displayname' 	 => 'display_name',
        'mail' 			 => 'email',
        'givenname' 	 => 'first_name',
        'memberof'		 => 'groups',
        'sn' 			 => 'last_name', 
        'manager' 		 => 'manager', 
        'samaccountname' => 'username',
    ),

	// The LDAP server.
	// 
	'server' => 'ldapserver.yourdomain.com',

	// Suffix (is appended to each username)
	// 
	'suffix' => '@yourdomain.com'
);