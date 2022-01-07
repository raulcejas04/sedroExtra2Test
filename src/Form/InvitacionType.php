<?php

namespace App\Form;

use App\Entity\Invitacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\PersonaFisicaCollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class InvitacionType extends AbstractType

{
    //todo esto es para utilizar el ->getUser() como parámetro de la query
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dispositivo', EntityType::class, [
                'class' => 'App:Dispositivo',
                'placeholder' => '-- Seleccionar Dispositivo --',

                //query para mostrar en el select los dispositivos a cargo del usuario
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->join('d.usuarioDispositivos', 'ud')
                        ->join('ud.usuario', 'u')
                        ->where('u.id = :user')
                        //TODO: Parametrizar nivel
                        ->andWhere('ud.nivel IN(1,2)')
                        ->setParameter('user', $this->token->getToken()->getUser()->getId())
                        ->orderBy('d.nicname', 'ASC');
                },
                'choice_label' => 'nicname',
                'choice_value' => 'id',
                'label' => 'Dispositivo',
                'attr' => ['class' => 'form-control']
            ])

            ->add('personaFisica', PersonaFisicaCollectionType::class)
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                ],
                'first_options'  => ['label' => 'Email'],
                'second_options' => ['label' => 'Repetir Email'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invitacion::class,
        ]);
    }
}
