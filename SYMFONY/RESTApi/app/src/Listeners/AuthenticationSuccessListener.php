<?php

namespace App\Listeners;

use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;
use App\Entity\User;

class AuthenticationSuccessListener
{
    private $secure = false;
    private $tokenTTL;

    public function __construct($tokenTTL)
    {
        $this->tokenTTL = $tokenTTL;
    }
    # This now returns cookie with token only without refresh token. - You can use the same token for how long u want if you will refresh it with the refresh_token.
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $response = $event->getResponse();
        $data = $event->getData();
        $user = $event->getUser();

        $token = $data['token'];
        //unset($data['token']);        #Uncoment this if you dont want to return the token and just want to return the refresh token 
        $event->setData($data);
        $response->headers->setCookie(new Cookie('BEARER', $token, (new \DateTime())->add(new \DateInterval('PT' . $this->tokenTTL . 'S')), '/', null, $this->secure, true));
        $response->headers->setCookie(new Cookie('USER_ID', $user->getId(), (new \DateTime())->add(new \DateInterval('PT' . $this->tokenTTL . 'S')), '/', null, $this->secure, true));
    }
}
