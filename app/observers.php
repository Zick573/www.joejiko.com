<?php

Event::listen('user.signup', 'Jiko\Mailers\UserMailer@welcome');

// Event::listen('user.signup', 'Jiko\Newsletters\NewsletterInterface');
Event::listen('user.signup', 'Jiko\Newsletters\MailChimp@addToMembersList');