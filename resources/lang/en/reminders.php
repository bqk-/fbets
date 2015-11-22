<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Password Reminder Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines are the default lines which match reasons
	| that are given by the password broker for a password update attempt
	| has failed, such as for an invalid token or invalid new password.
	|
	*/

	"subject" => trans('general.site_title')." Password Reset",

	"message" => "Hello :display,<br />
					To reset your password, please click on the following link :<br /><br />
					<a href=\"".URL::to('recover')."/:token\">".URL::to('recover/')."/:token</a>"

);
