<?php

namespace App\Form;

use App\Entity\Solicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Entity\TipoDispositivo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NuevaSolicitudType extends AbstractType
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
                        ->andWhere('ud.nivel IN(1)')
                        ->setParameter('user', $this->token->getToken()->getUser()->getId())
                        ->orderBy('d.nicname', 'ASC');
                },
                'choice_label' => 'nicname',
                'choice_value' => 'id',
                'label' => 'Dispositivo',
                'attr' => ['class' => 'form-control']
            ])
            ->add('cuil', TextType::class, [
                'label' => "CUIL Persona Física",
                'required' => true,
                'attr' => [
                    'class' => "val-cuit"
                ]
            ])
            ->add('denominacion', TextType::class, [
                'label' => "Nombre y apellido",
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'readonly' => true
                ],
                'disabled' => "true"
            ])
            ->add('mail', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => 'Los emails deben ser iguales',
                'options' => ['attr' => ['class' => 'form-control val-email']],
                'required' => true,
                'first_options'  => ['label' => 'E-mail'],
                'second_options' => ['label' => 'Repita E-mail'],
            ])
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'onPostSubmit']
            );;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Solicitud::class,
        ]);
    }

    public function onPostSubmit(FormEvent $event): void
    {
        $sol = $event->getData();
        $form = $event->getForm();
        $sol->setCuit(str_replace('-', '', $sol->getCuit()));
        $sol->setCuil(str_replace('-', '', $sol->getCuil()));

        $dispositivo = $sol->getDispositivo();
        $pj = $dispositivo->getPersonaJuridica();
        $sol->setPersonaJuridica($pj);
        $sol->setNicname($dispositivo->getNicname());
        $sol->setCuit($pj->getCuit());
        //dd($sol);
        $event->setData($sol);
    }
}
