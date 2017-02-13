<?php

namespace RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DishType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'dish_title',
                'translation_domain' => 'dish',
            ))
            ->add('description', TextType::class, array(
                'label' => 'dish_description',
                'translation_domain' => 'dish',
            ))
            ->add('price', TextType::class, array(
                'label' => 'dish_price',
                'translation_domain' => 'dish',
            ))
            ->add('homeMade', CheckboxType::class, array(
                'required' => false,
                'label' => 'dish_home_made',
                'translation_domain' => 'dish',
            ))
            ->add('image', TextType::class, array(
                'label' => 'dish_image',
                'translation_domain' => 'dish',
            ))
            ->add('category', ChoiceType::class, array(
                'choices'  => array(
                    'dish_starter' => 'starter',
                    'dish_dish'    => 'dish',
                    'dish_dessert' => 'dessert',
                ),
                'label' => 'dish_category',
                'translation_domain' => 'dish',
            ))
            ->add('allergens', CollectionType::class, array(
                'entry_type' => TextType::class,
                'allow_add'  => true,
                'allow_delete'  => true,
                'label' => false,
                'prototype' => true,
                'prototype_data' =>
                    '<input type="text"
                        id="form_allergens___name__"
                        name="form[allergens][__name__]"
                        value=""
                    />',
            ))
            ->add('status', ChoiceType::class, array(
                'choices'  => array(
                    'draft'         => 'draft',
                    'in_validation' => 'in_validation',
                    'refuse'        => 'refuse',
                    'valid'         => 'valid',
                ),
                'label' => 'status',
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
            'data_class' => 'RestaurantBundle\Entity\Dish'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'restaurantbundle_dish';
    }


}
