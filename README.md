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

### Step 1: Download EoHoneypotBundle using Composer
Add EoHoneypotBundle to your project by running the command:
```bash
$ composer require eo/honeypot-bundle
```

Composer will install the bundle to your project's `vendor/eo` directory.

### Step 2: Enable the bundle
If you use Symfony Flex - skip this step. Otherwise, enable the bundle in `bundles.php`:
```php
<?php
// config/bundles.php

<?php
return [
    // ...
    Eo\HoneypotBundle\EoHoneypotBundle::class => ['all' => true],
];
```

### Step 3 (optional): Configure bundle to use database
To save honeypot catched requests into database you have to enable it in your configuration file:
*All parameters are optional*

```yaml
# config/packages/eo_honeypot.yaml
eo_honeypot:
    storage:
        database:
            enabled: false
            driver: mongodb # orm and mongodb are supported
            class: ApplicationEoHoneypotBundle:HoneypotPrey
        # You can also use file format to store honeypot preys.
        # This may come handy if you need to parse logs with fail2ban
        # file:
            # enabled: false
            # output: /var/log/honeypot.log
    redirect:
        enabled: true
        url: "/"
        # route: homepage
        # route_parameters: ~
```

If you enable the database storage, you must create a class which extends
the `Eo\HoneypotBundle\<Entity|Document>\HoneypotPrey` base class :

```php
<?php
namespace Application\Eo\HoneypotBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eo\HoneypotBundle\Entity\HoneypotPrey as BaseHoneypotPrey;

/**
 * @ORM\Entity
 */
class HoneypotPrey extends BaseHoneypotPrey
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}

```

or


```php
<?php
namespace Application\Eo\HoneypotBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Eo\HoneypotBundle\Document\HoneypotPrey as BaseHoneypotPrey;

/**
 * @MongoDB\Document
 */
class HoneypotPrey extends BaseHoneypotPrey
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}
```


## Usage
Once installed and configured you can start using `Eo\HoneypotBundle\Form\Type\HoneypotType`
form type in your forms.

### Basic usage example:
```php
<?php

namespace Acme\DemoBundle\Form\Type;

use Eo\HoneypotBundle\Form\Type\HoneypotType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FooType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType);
        $builder->add('email', EmailType);

        // Honeypot field
        $builder->add('SOME-FAKE-NAME', HoneypotType::class);
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
