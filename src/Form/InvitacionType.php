<?php

namespace App\Form;

use App\Entity\Invitacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\PersonaFisicaCollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
USE Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


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
                    ->join('d.personaJuridica', 'pj')
                    ->join('pj.representaciones', 'r')
                    ->join('r.personaFisica', 'pf')
                    ->join('pf.users', 'u')
                    ->where('u.id = :user')
                    ->setParameter('user', $this->token->getToken()->getUser()->getId())
                    ->orderBy('d.nicname', 'ASC');
                },
                'choice_label' => 'nicname',
                'choice_value' => 'id',
                'label' => 'Dispositivo',
                'attr' => ['class' => 'form-control']
            ])

            ->add('personaFisica', PersonaFisicaCollectionType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invitacion::class,
        ]);
    }
}