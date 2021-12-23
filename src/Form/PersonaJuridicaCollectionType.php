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
use Symfony\Component\Form\CallbackTransformer;

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
                    'maxlength' => 13
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
        
        
        $builder->get('cuit')
            ->addModelTransformer(new CallbackTransformer(
                function ($cuit) {
                	//20183019571
                	//01234567890
                    return substr($cuit,0,2)."-".substr($cuit,2,8)."-".substr($cuit,10,1);

                },
                function ($cuit) {
                     return str_replace('-','',$cuit);
                }
            ));

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
