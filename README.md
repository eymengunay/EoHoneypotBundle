# EoHoneypotBundle

[![Build Status](https://travis-ci.org/eymengunay/EoHoneypotBundle.svg?branch=master)](https://travis-ci.org/eymengunay/EoHoneypotBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/eymengunay/EoHoneypotBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/eymengunay/EoHoneypotBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/eymengunay/EoHoneypotBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/eymengunay/EoHoneypotBundle/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/eo/honeypot-bundle/v/stable.svg)](https://packagist.org/packages/eo/honeypot-bundle) 
[![Total Downloads](https://poser.pugx.org/eo/honeypot-bundle/downloads.svg)](https://packagist.org/packages/eo/honeypot-bundle)


Honeypot for Symfony2 forms.

## What is Honey pot?
> A honey pot trap involves creating a form with an extra field that is hidden to human visitors but readable by robots.
> The robot fills out the invisible field and submits the form, leaving you to simply ignore their spammy submission or blacklist their IP.
> It’s a very simple concept that can be implemented in a few minutes and it just works – add them to your contact and submission forms to help reduce spam.

## Prerequisites
This version of the bundle requires Symfony 2.1+

## Installation

### Step 1: Download EoHoneypotBundle using composer
Add EoHoneypotBundle in your composer.json:
```
{
    "require": {
        "eo/honeypot-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:
```
$ php composer.phar update eo/honeypot-bundle
```
Composer will install the bundle to your project's vendor/eo directory.

### Step 2: Enable the bundle
Enable the bundle in the kernel:
```
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Eo\HoneypotBundle\EoHoneypotBundle(),
    );
}
```

### Step 3 (optional): Configure bundle to use database
To save honeypot catched requests into database you have to enable it in your configuration file:
*All parameters are optional*

```
# app/config.yml
...
eo_honeypot:
    storage:
        database:
            enabled: false
            driver: mongodb # orm and mongodb are supported
            class: EoHoneypotBundle:HoneypotPrey
        # You can also use file format to store honeypot preys.
        # This may come handy if you need to parse logs with fail2ban
        # file:
            # enabled: false
            # output: /var/log/honeypot.log
    redirect:
        enabled: true
        to: "/"
```

## Usage
Once installed and configured you can start using `honeypot` type in your forms.

### Basic usage example:
```
<?php

namespace Acme\DemoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FooType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('email', 'text');

        // Honeypot field
        $builder->add('SOME-FAKE-NAME', 'honeypot');
    }

    public function getName()
    {
        return 'foo';
    }
}
```

### Events

If the hidden honeypot field has some data bundle will dispatch a `bird.in.cage` event. You can create an event listener to execute custom actions. See [Eo\HoneypotBundle\Event\BirdInCage](https://github.com/eymengunay/EoHoneypotBundle/blob/master/Event/BirdInCageEvent.php) and [How to Register Event Listeners and Subscribers](http://symfony.com/doc/current/cookbook/doctrine/event_listeners_subscribers.html) for more information.

## License
This bundle is under the MIT license. See the complete license in the bundle:
```
Resources/meta/LICENSE
```

## Reporting an issue or a feature request
Issues and feature requests related to this bundle are tracked in the Github issue tracker https://github.com/eymengunay/EoHoneypotBundle/issues.
