<?php

namespace App\Form\Type;

use App\Entity\Issue;
use App\Entity\Project;
use App\Entity\User;
use App\Enum\IssueType as IssueTypeEnum;
use App\Enum\IssueStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType
{
    public function __construct(
        private readonly Security $security
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Project $project */
        $project = $this->security->getUser()->getSelectedProject();

        $builder
            ->add('project', EntityType::class, [
                'attr' => [
                    'class' => 'd-none',
                ],
                'class' => Project::class,
                'data' => $project,
                'label' => false,
            ])
            ->add('type', EnumType::class, [
                'choice_label' => fn(\App\Enum\IssueType $type) => $type->label(),
                'class' => \App\Enum\IssueType::class,
                'data' => \App\Enum\IssueType::BUG,
                'label' => '<i class="bi bi-sign-intersection-t-fill"></i> Type',
                'label_html' => true,
            ])
            ->add('status', EnumType::class, [
                'choice_label' => fn(IssueStatus $status) => $status->label(),
                'class' => \App\Enum\IssueStatus::class,
                'data' => \App\Enum\IssueStatus::NEW,
                'label' => '<i class="bi bi-stoplights-fill"></i> Status',
                'label_html' => true,
            ])
            ->add('summary', TextType::class, [
                'label' => '<i class="bi bi-justify-left"></i> Summary',
                'label_html' => true,
            ])
            ->add('assignee', EntityType::class, [
                'class' => User::class,
                'choices' => !$project ? [] : $project->getMembers(),
                'placeholder' => 'Assignee',
                'label' => '<i class="bi bi-person-fill"></i> Assignee',
                'label_html' => true,
            ])
            ->add('reporter', EntityType::class, [
                'class' => User::class,
                'choices' => !$project ? [] : $project->getMembers(),
                'data' => $this->security->getUser(),
                'label' => '<i class="bi bi-megaphone-fill"></i> Reporter',
                'label_html' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => '<i class="bi bi-plus-circle-fill"></i> Create',
                'label_html' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Issue::class,
        ]);
    }
}
