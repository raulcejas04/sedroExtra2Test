<?php

namespace App\Form;

use App\Entity\Solicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class NuevaSolicitudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cuit', TextType::class, [
                'label' => "CUIT",
                'required' => true,
                'attr' => [
                    'class' => "form-control"
                ]
            ])
            ->add('cuil', TextType::class, [
                'label' => "CUIL",
                'required' => true,
                'attr' => [
                    'class' => "form-control"
                ]
            ])
            ->add('nicname', TextType::class, [
                'label' => "NicName",
                'required' => true,
                'attr' => [
                    'class' => "form-control"
                ]
            ])
            ->add('mail', RepeatedType::class, [
                'type' => TextType::class,
                'invalid_message' => 'Los emails deben ser iguales',
                'options' => ['attr' => ['class' => 'form-control']],
                'required' => true,
                'first_options'  => ['label' => 'E-mail'],
                'second_options' => ['label' => 'Repita E-mail'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Solicitud::class,
        ]);
    }
}
