<?php

namespace App\Service;

use App\Enum\IssueStatus;
use App\Repository\IssueRepository;
use Symfony\Bundle\SecurityBundle\Security;

class IssueService
{
    public function __construct(
        private readonly IssueRepository $repo,
        private readonly Security $security
    )
    {
    }

    public function getNewIssues(): array
    {
        return $this->getIssuesByStatus([IssueStatus::NEW]);
    }

    public function getReadyIssues(): array
    {
        return $this->getIssuesByStatus([IssueStatus::READY]);
    }

    public function getInProgressIssues(): array
    {
        return $this->getIssuesByStatus([IssueStatus::IN_DEVELOPMENT, IssueStatus::IN_REVIEW]);
    }

    public function getResolvedIssues(): array
    {
        return $this->getIssuesByStatus([IssueStatus::RESOLVED]);
    }

    public function getIssuesByStatus(array $statuses): array
    {
        $user = $this->security->getUser();

        return $user
            ->getSelectedProject()
            ->getIssues()
            ->filter(fn($issue) => \in_array($issue->getStatus(), $statuses))
            ->toArray();
    }
}
