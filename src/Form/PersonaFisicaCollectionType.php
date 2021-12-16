<?php

namespace App\Form;

use App\Entity\PersonaFisica;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

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
                    'class' => 'form-control val-cuit',  'disabled' => true,
                ]
            ])
            ->add('cuitCuil', TextType::class, [                
                'label' => "Número CUIT/CUIL",                
                'required' => true,
                'attr' => [
                    'class' => 'form-control val-cuit',   'disabled' => false,                  
                ]
            ])
            ->add('fechaNac', BirthdayType::class, [
                'widget' => 'single_text',
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
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'onPostSubmit']
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonaFisica::class,
        ]);
    }
    
    public function onPostSubmit(FormEvent $event): void
    {
        $per = $event->getData();
        $form = $event->getForm();
	$per->setCuitCuil(str_replace('-','',$per->getCuitCuil()));
	
	$event->setData($per);
    }
}
