<?php

namespace App\Form;

use App\Entity\Location;
#use Doctrine\DBAL\Types\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            #->add('city')
            #->add('country')
            #->add('latitude')
            #->add('longitude')

            ->add('city',null, [
                'attr' => [
                    'placeholder' => 'Enter city name',
                ],
            ])
            ->add('country', ChoiceType::class, [
                'choices' => [
                    'Poland' => 'PL',
                    'Germany' => 'GE',
                    'France' => 'FR',
                    'Spain' => 'ES',
                    'Italy' => 'IT',
                    'United Kingdom' => 'UK',
                    'United States' => 'US',
                ],
            ])
            ->add('latitude', NumberType::class, [
                'html5' => true,
                'scale' => 7,
                'attr' => [
                    'step' => 'any',
                    'min' => -90,
                    'max' => 90,
                ],
            ])
            ->add('longitude', NumberType::class, [
                'html5' => true,
                'scale' => 7,
                'attr' => [
                    'step' => 'any',
                    'min' => -180,
                    'max' => 180,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
