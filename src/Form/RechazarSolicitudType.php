<?php

namespace App\Form;

use App\Entity\Solicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechazarSolicitudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('observacionRechazo', TextareaType::class, [
                'label' => 'Motivo del rechazo',
                'required' => true,
                'attr' => [
                    "rows" => 6
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Solicitud::class,
        ]);
    }
}
