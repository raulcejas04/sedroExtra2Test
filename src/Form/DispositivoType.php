<?php

namespace App\Form;

use App\Entity\Dispositivo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DispositivoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nicname', TextType::class, [                
                'label' => "Nombre corto del dispositivo",                
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => true,
                ]
            ])
            //->add('fechaAlta')
            //->add('fechaBaja')
            //->add('personaJuridica')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dispositivo::class,
        ]);
    }
}
