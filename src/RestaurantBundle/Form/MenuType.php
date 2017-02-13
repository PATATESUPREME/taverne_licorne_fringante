<?php

namespace RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
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
            ->add('dishes', CollectionType::class, array(
                'entry_type' => DishType::class,
                'allow_add'  => true,
                'allow_delete'  => true,
                'label' => false,
                'translation_domain' => 'menu',
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
