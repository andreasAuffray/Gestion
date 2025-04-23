<?php

namespace App\Form\Type;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function __construct(
        private readonly Security $security
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $connectedUser */
        $connectedUser = $this->security->getUser();

        $disabled = $connectedUser->getId() != $options['data']->getId();

        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'placeholder' => 'Username'
                ],
                'disabled' => $disabled,
                'label' => '<i class="bi bi-person-fill"></i> Username',
                'label_html' => true,
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => 'First name'
                ],
                'disabled' => $disabled,
                'label' => 'First name',
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Last name'
                ],
                'disabled' => $disabled,
                'label' => 'Last name',
            ])
        ;

        if (!$disabled) {
            $builder
                ->add('submit', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-primary'
                    ],
                    'label' => '<i class="bi bi-floppy2-fill"></i> Save',
                    'label_html' => true,
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
