<?php

namespace App\Form;

use App\Entity\PersonaJuridica;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DispositivoType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PersonaJuridicaCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cuit', TextType::class, [
                'label' => "CUIT Persona Jurídica",
                'required' => true,
                'attr' => [
                    'class' => 'form-control val-cuit',
                    'readonly' => true,
                ]
            ])
            ->add('razonSocial', TextType::class, [
                'label' => "Razón Social",
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('dispositivos', CollectionType::class, [                
                'entry_type' => DispositivoType::class,
                'required' => true,                                
            ])
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'onPostSubmit']
            );
            //->add('fechaAlta')
            //->add('fechaBaja')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonaJuridica::class,
        ]);
    }
    
    public function onPostSubmit(FormEvent $event): void
    {
        $per = $event->getData();
        $form = $event->getForm();
	$per->setCuit(str_replace('-','',$per->getCuit()));
	
	$event->setData($per);
    }

}
