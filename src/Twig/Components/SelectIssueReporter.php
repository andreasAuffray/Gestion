<?php 

namespace App\Twig\Components;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
class SelectIssueReporter
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp]
    public \App\Entity\Issue $issue;

    /** @var User[]  */
    #[LiveProp]
    public array $people = [];

    #[LiveProp(writable: true)]
    public User $reporter;

    #[LiveAction]
    public function updateReporter(EntityManagerInterface $manager): void
    {
        $this->validate();

        $this->issue->setReporter($this->reporter);

        $manager->flush(); 
    }
}
