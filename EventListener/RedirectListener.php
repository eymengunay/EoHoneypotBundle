<?php
namespace Eo\HoneypotBundle\EventListener;

use Eo\HoneypotBundle\Manager\HoneypotManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\RouterInterface;

class RedirectListener
{
    protected $router;
    protected $request;
    protected $honeypotManager;
    protected $eventDispatcher;

    public function __construct(RouterInterface $router, Request $request, HoneypotManager $honeypotManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->router = $router;
        $this->request = $request;
        $this->honeypotManager = $honeypotManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onBirdInCage()
    {
        $this->eventDispatcher->addListener('kernel.response', array($this, 'onKernelResponse'));
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
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
            $target = $this->request->getUri();
        }

        $event->setResponse(new RedirectResponse($target));
    }
}
