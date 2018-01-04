<?php

namespace RestaurantBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'menu_title',
                'translation_domain' => 'menu',
            ))
            ->add('price', MoneyType::class, array(
                'label' => 'menu_price',
                'translation_domain' => 'menu',
            ))
            ->add('displayOrder', IntegerType::class, array(
                'label' => 'menu_display_order',
                'translation_domain' => 'menu',
            ))
            ->add('dishes', EntityType::class, array(
                'class' => 'RestaurantBundle:Dish',
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'select2'
                ),
                'label' => 'menu_dishes',
                'translation_domain' => 'menu',
            ))
            ->add('draft', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-info float-left mr-1'
                ),
                'label' => 'draft',
                'translation_domain' => 'general',
            ))
            ->add('in_validation', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-primary float-left mr-1'
                ),
                'label' => 'in_validation',
                'translation_domain' => 'general',
            ))
            ->add('refuse', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-danger float-left mr-1'
                ),
                'is_granted' => 'ROLE_REVIEWER',
                'label' => 'refuse',
                'translation_domain' => 'general',
            ))
            ->add('valid', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-success float-left mr-1'
                ),
                'is_granted' => 'ROLE_REVIEWER',
                'label' => 'valid',
                'translation_domain' => 'general',
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RestaurantBundle\Entity\Menu'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'restaurantbundle_menu';
    }


}
