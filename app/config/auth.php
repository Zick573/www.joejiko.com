<?php
return [
	'driver' => 'jiko',
	'model' => 'User',
  'username' => 'email',
	'table' => 'users',
	'reminder' => [
		'email' => 'emails.auth.reminder',
		'table' => 'password_reminders'
	]
];