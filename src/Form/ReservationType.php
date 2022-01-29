<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\User;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'constraints' => [
                    new NotNull(),
                ]
            ])
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'multiple' => true,
                'constraints' => [
                    new NotNull(),
                ]
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotNull(),
                ]
            ])
            ->add('status', TextType::class, [
                'constraints' => [
                    new NotNull(),
                ]
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => Reservation::class, 'csrf_protection' => false));
    }
}
