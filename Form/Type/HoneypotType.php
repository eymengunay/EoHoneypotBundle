<?php

/*
 * This file is part of the EoHoneypotBundle package.
 *
 * (c) Eymen Gunay <eymen@egunay.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eo\HoneypotBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Eo\HoneypotBundle\Document\HoneypotPrey;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HoneypotType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    protected $om;

    /**
     * @var boolean
     */
    protected $useDB = false;

    /**
     * Class constructor
     */
    public function __construct($useDB = false)
    {
        $this->useDB = $useDB;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_BIND, function(FormEvent $event) {
            if ($event->getData() !== "") {
                $protocol = $_SERVER['HTTPS'] == 'off' ? 'http' : 'https';
                $url = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
                header('Location: ' . $url);

                // Check if we need to save request in db
                if ($this->useDB) {
                    $prey = new HoneypotPrey();
                    $prey->setRequest($_REQUEST);
                    $prey->setServer($_SERVER);
                    $this->om->persist($prey);
                    $this->om->flush();
                }

                exit;
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'required' => false,
            'virtual' => true
        ));
    }

    /**
     * Set objectManager
     *
     * @param ObjectManager $om
     */
    public function setObjectManager(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'honeypot';
    }
}