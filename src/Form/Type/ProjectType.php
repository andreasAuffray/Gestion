<?php

namespace App\Form\Type;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function __construct(
        private readonly Security $security
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => '<i class="bi bi-explicit-fill"></i> Name',
                'label_html' => true,
            ])
            ->add('keyCode', TextType::class, [
                'label' => '<i class="bi bi-tag-fill"></i> Key',
                'label_html' => true,
            ])
            ->add('leadUser', EntityType::class, [
                'attr' => [
                    'class' => 'd-none',
                ],
                'class' => User::class,
                'data' => $this->security->getUser(),
                'label' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => '<i class="bi bi-plus-circle-fill"></i> Create project',
                'label_html' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
