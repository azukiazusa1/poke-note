<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Model\Entity\User;
use Cake\Http\Cookie\Cookie;

class LoginCookieComponent extends Component
{
	private const KEY = 'SyP34aTYFRgraz92bSdU4jHJjSaPwU37';
	
    public function generate(User $user): void
    {
		$cookie = (new Cookie(self::KEY))
			->withValue($user->tokenGenerate()) 
			->withExpiry(new \Datetime('+1 year')) 
			->withSecure(false)
			->withHttpOnly(true);
		
		Component::getController()->response = Component::getController()->response->withCookie($cookie);
	}

	public function get(): ?string
	{
		return Component::getController()->request->getCookie(self::KEY);
	}

	public function delete(): void
	{
		Component::getController()->response = Component::getController()->response->withExpiredCookie(new Cookie(self::KEY));
	}
}