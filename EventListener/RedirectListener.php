<?php
namespace Eo\HoneypotBundle\EventListener;

use Eo\HoneypotBundle\Manager\HoneypotManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class RedirectListener
{
    protected $router;
    protected $honeypotManager;
    protected $eventDispatcher;

    public function __construct(RouterInterface $router, HoneypotManager $honeypotManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->router = $router;
        $this->honeypotManager = $honeypotManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onBirdInCage()
    {
        $this->eventDispatcher->addListener('kernel.response', array($this, 'onKernelResponse'));
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $options = $this->honeypotManager->getOptions();

        $url = $options['redirect']['url'];
        $route = $options['redirect']['route'];
        $parameters = $options['redirect']['route_parameters'];

        if ($url) {
            $target = $url;
        } elseif ($route) {
            $target = $this->router->generate($route, $parameters);
        } else {
            $target = $event->getRequest()->getUri();
        }

        $event->setResponse(new RedirectResponse($target));
    }
}
