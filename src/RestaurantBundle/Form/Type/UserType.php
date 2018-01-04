<?php

namespace RestaurantBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => 'user_username',
                'translation_domain' => 'user',
            ))
            ->add('email', TextType::class, array(
                'label' => 'user_email',
                'translation_domain' => 'user',
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'user_password'),
                'second_options' => array('label' => 'user_repeat_password'),
                'translation_domain' => 'user',
            ))
            ->add('roles', ChoiceType::class, array(
                'choices'  => array(
                    'role_waiter'   => 'ROLE_WAITER',
                    'role_writer'   => 'ROLE_WRITER',
                    'role_reviewer' => 'ROLE_REVIEWER',
                    'role_chief'    => 'ROLE_CHIEF',
                    'role_admin'    => 'ROLE_ADMIN',
                ),
                'label' => 'user_roles',
                'translation_domain' => 'user',
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RestaurantBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'cocktailappbundle_user';
    }


}
