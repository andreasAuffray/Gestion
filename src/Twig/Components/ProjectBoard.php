<?php

namespace App\Twig\Components;

use App\Enum\IssueStatus;
use App\Service\IssueService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ProjectBoard
{
    use DefaultActionTrait;

    /** @var \App\Entity\Issue[] */
    #[LiveProp(writable: true)]
    public array $newIssues = [];

    /** @var \App\Entity\Issue[] */
    #[LiveProp(writable: true)]
    public array $readyIssues = [];

    /** @var \App\Entity\Issue[] */
    #[LiveProp(writable: true)]
    public array $inProgressIssues = [];

    /** @var \App\Entity\Issue[] */
    #[LiveProp(writable: true)]
    public array $resolvedIssues = [];

    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly IssueService $service,
    )
    {
    }

    public function mount(): void
    {
        $this->getIssues();
    }

    #[LiveAction]
    public function getIssues(): void
    {
        $this->newIssues = $this->service->getNewIssues();
        $this->readyIssues = $this->service->getReadyIssues();
        $this->inProgressIssues = $this->service->getInProgressIssues();
        $this->resolvedIssues = $this->service->getResolvedIssues();
    }

    #[LiveAction]
    public function updateIssueStatus(#[LiveArg] string $id, #[LiveArg] IssueStatus $status): void
    {
        $issue = $this->manager->getRepository(\App\Entity\Issue::class)->find($id);

        $issue->setStatus($status);
        $this->manager->persist($issue);
        $this->manager->flush();

        $this->getIssues();
    }
}
