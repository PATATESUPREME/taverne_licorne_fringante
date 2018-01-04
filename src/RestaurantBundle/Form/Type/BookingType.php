<?php

namespace RestaurantBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('day', TextType::class, array(
                'attr' => array(
                    'class' => 'datepicker',
                    'readonly' => true
                ),
                'data' => (new \DateTime())->format('d/m/Y'),
                'label' => 'booking_day',
                'translation_domain' => 'booking',
            ))
            ->add('hour', TextType::class, array(
                'attr' => array(
                    'class' => 'timepicker',
                    'readonly' => true
                ),
                'label' => 'booking_hour',
                'translation_domain' => 'booking',
            ))
            ->add('peopleBooked', IntegerType::class, array(
                'label' => 'booking_people',
                'translation_domain' => 'booking',
            ))
            ->add('email', TextType::class, array(
                'label' => 'booking_email',
                'translation_domain' => 'booking',
            ))
            ->add('name', TextType::class, array(
                'label' => 'booking_name',
                'translation_domain' => 'booking',
            ))
            ->add('phone', TextType::class, array(
                'label' => 'booking_phone',
                'translation_domain' => 'booking',
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RestaurantBundle\Entity\Booking'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'restaurantbundle_booking';
    }


}
