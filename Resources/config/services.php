<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();
    $parameters->set('eo_honeypot.manager.class', \Eo\HoneypotBundle\Manager\HoneypotManager::class);
    $parameters->set('eo_honeypot.form.type.class', \Eo\HoneypotBundle\Form\Type\HoneypotType::class);
    $parameters->set('eo_honeypot.redirect_listener.class', \Eo\HoneypotBundle\EventListener\RedirectListener::class);
    $parameters->set('eo_honeypot.event.bird_in_cage', \Eo\HoneypotBundle\Events::BIRD_IN_CAGE);

    $services->set('eo_honeypot.manager', '%eo_honeypot.manager.class%')
        ->args(['%eo_honeypot.options%']);

    $services->set('eo_honeypot.form.type.honeypot', '%eo_honeypot.form.type.class%')
        ->args([
            service('request_stack'),
            service('eo_honeypot.manager'),
            service('event_dispatcher'),
        ])
        ->tag('form.type', ['alias' => 'honeypot']);

    $services->set('eo_honeypot.redirect_listener', '%eo_honeypot.redirect_listener.class%')
        ->args([
            service('router'),
            service('eo_honeypot.manager'),
            service('event_dispatcher'),
        ])
        ->tag('kernel.event_listener', ['event' => '%eo_honeypot.event.bird_in_cage%', 'method' => 'onBirdInCage']);
};
