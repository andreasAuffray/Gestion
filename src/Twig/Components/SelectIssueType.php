<?php 

namespace App\Twig\Components;

use App\Enum\IssueType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
class SelectIssueType
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp]
    public \App\Entity\Issue $issue;

    /** @var IssueType[]  */
    #[LiveProp]
    public array $types = [];

    #[LiveProp(writable: true)]
    public ?IssueType $type;

    #[LiveAction]
    public function updateType(EntityManagerInterface $manager): void
    {
        $this->validate();

        $this->issue->setType($this->type);

        $manager->flush(); 
    }
}
