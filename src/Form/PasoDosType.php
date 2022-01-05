<?php

namespace App\Form;

use App\AuxEntities\PasoDos;
use App\Entity\Solicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\PersonaFisicaCollectionType;
use App\Form\PersonaJuridicaCollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PasoDosType extends AbstractType
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
            ->add('personaFisica', PersonaFisicaCollectionType::class)
            ->add('personaJuridica', PersonaJuridicaCollectionType::class);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            $form["personaFisica"]["cuitCuil"]->setData($data->getCuil());
            $form["personaJuridica"]["cuit"]->setData($data->getCuit());
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Solicitud::class,
        ]);
    }
}
