<?php

namespace RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('description', TextareaType::class, array(
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
            ->add('image', FileType::class, array(
                'attr' => array(
                    'class' => 'file',
                    'data-show-upload' => false,
                    'data-show-caption' => true
                ),
                'label' => 'dish_image',
                'translation_domain' => 'dish',
                'required' => false
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
                'label' => false
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
