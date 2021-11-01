<?php

namespace App\Form;

use App\Entity\PersonaFisica;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonaFisicaCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('apellido', TextType::class, [
                'label' => "Apellido",
                'required' => true,
                'label' => "Apellido",
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('nombres', TextType::class, [
                'label' => "Nombres",
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('tipoDocumento', EntityType::class, [
                'class' => 'App:TipoDocumento',
                'label' => "Tipo de Documento",
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('nroDoc', TextType::class, [
                'label' => "Número de Documento",
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('sexo', EntityType::class, [
                'class' => 'App:Sexo',
                'label' => "Sexo (tal como aparece en el documento)",
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('estadoCivil', EntityType::class, [
                'class' => 'App:EstadoCivil',
                'label' => "Estado Civil",
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('tipoCuitCuil', EntityType::class, [
                'class' => 'App:TipoCuitCuil',
                'label' => "Tipo CUIT/CUIL",
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('cuitCuil', TextType::class, [                
                'label' => "Número CUIT/CUIL",                
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => true,
                ]
            ])
            ->add('fechaNac', BirthdayType::class, [
                'label' => "Fecha de Nacimiento",
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('nacionalidad', EntityType::class, [
                'class' => 'App:Nacionalidad',
                'label' => "Nacionalidad Actual",
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonaFisica::class,
        ]);
    }
}
