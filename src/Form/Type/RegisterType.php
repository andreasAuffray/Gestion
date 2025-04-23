<?php

namespace App\Form\Type;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'placeholder' => '<i class="bi bi-person-fill-add"></i> Username',
                ],
                'required' => true,
                'label' => '<i class="bi bi-person-fill-add"></i> Username',
                'label_html' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => [
                    'attr' => [
                        'placeholder' => '<i class="bi bi-asterisk"></i> Password',
                    ],
                    'label' => '<i class="bi bi-asterisk"></i> Password',
                    'label_html' => true,
                ],
                'second_options' => [
                    'attr' => [
                        'placeholder' => '<i class="bi bi-asterisk"></i> Confirm password',
                    ],
                    'label' => '<i class="bi bi-asterisk"></i> Confirm password',
                    'label_html' => true,
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-lg btn-primary w-100',
                ],
                'label' => 'Sign up <i class="bi bi-play-fill"></i>',
                'label_html' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
