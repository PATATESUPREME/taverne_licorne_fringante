<?php

namespace RestaurantBundle\Form\Type\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurityButtonTypeExtention extends AbstractTypeExtension
{
    /**
     * The security authorization checker
     * @var AuthorizationCheckerInterface
     */
    private $authorization_checker;

    /**
     * SecurityButtonTypeExtention constructor.
     *
     * @param AuthorizationCheckerInterface $authorization_checker
     */
    public function __construct(AuthorizationCheckerInterface $authorization_checker)
    {
        $this->authorization_checker = $authorization_checker;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ButtonType::class;
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $grant = !empty($options['is_granted']) ? $options['is_granted'] : null;
        if (null === $grant || $this->authorization_checker->isGranted($grant)) {
            return;
        }
        /*
        // Event listener not supported by button builder
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            if ($form->isRoot()) {
                return;
            }

            $form->getParent()->remove($form->getName());
        });
        */
    }
}
