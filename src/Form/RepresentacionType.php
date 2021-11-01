<?php

namespace App\Form;

use App\Entity\Representacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\PersonaFisicaCollectionType;
use App\Form\PersonaJuridicaCollectionType;

class RepresentacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('personaFisica', PersonaFisicaCollectionType::class)
            ->add('personaJuridica', PersonaJuridicaCollectionType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Representacion::class,
        ]);
    }
}
