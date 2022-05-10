<?php

namespace App\Listeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;

class RequestListener implements EventSubscriberInterface
{
    public function __construct()
    {
    }

    public function onKernelRequest(KernelEvent $event)
    {
        $request = $event->getRequest();

        $request->attributes->set("refresh_token", $request->cookies->get("REFRESH_TOKEN"));
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                "onKernelRequest"
            ]
        ];
    }
}
