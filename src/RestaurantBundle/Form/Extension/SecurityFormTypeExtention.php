<?php

namespace RestaurantBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurityFormTypeExtention extends AbstractTypeExtension
{
    /**
     * The security authorization checker
     * @var AuthorizationCheckerInterface
     */
    private $authorization_checker;

    /**
     * Object constructor
     */
    public function __construct(AuthorizationCheckerInterface $authorization_checker)
    {
        $this->authorization_checker = $authorization_checker;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $grant = !empty($options['is_granted']) ? $options['is_granted'] : null;
        if (null === $grant || $this->authorization_checker->isGranted($grant)) {
            return;
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            if ($form->isRoot()) {
                return;
            }

            $form->getParent()->remove($form->getName());
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined('is_granted');
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return FormType::class;
    }
}