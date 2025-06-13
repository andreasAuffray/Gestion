<?php 

namespace App\Twig\Components;

use App\Enum\IssueStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
class SelectIssueStatus
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp]
    public \App\Entity\Issue $issue;

    /** @var IssueStatus[]  */
    #[LiveProp]
    public array $statuses = [];

    #[LiveProp(writable: true)]
    public ?IssueStatus $status;

    #[LiveAction]
    public function updateStatus(EntityManagerInterface $manager): void
    {
        $this->validate();

        $this->issue->setStatus($this->status);

        $manager->flush(); 
    }
}
