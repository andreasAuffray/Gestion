<?php

namespace App\Entity;

use App\Enum\IssueStatus;
use App\Enum\IssueType;
use App\Repository\IssueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IssueRepository::class)]
class Issue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'issues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\Column(type: Types::SMALLINT, enumType: IssueType::class)]
    private ?IssueType $type = null;

    #[Assert\Length(max: 100)]
    #[ORM\Column(length: 100)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $storyPointEstimate = null;

    #[ORM\ManyToOne(inversedBy: 'assignedIssues')]
    private ?User $assignee = null;

    #[ORM\ManyToOne(inversedBy: 'reportedIssues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $reporter = null;

    /**
     * @var Collection<int, Attachment>
     */
    #[ORM\OneToMany(targetEntity: Attachment::class, mappedBy: 'issue', orphanRemoval: true)]
    private Collection $attachment;

    #[ORM\Column(type: Types::SMALLINT, enumType: IssueStatus::class)]
    private ?IssueStatus $status = null;


    private ?string $keyCode = null;

    public function __construct()
    {
        $this->attachment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getType(): ?IssueType
    {
        return $this->type;
    }

    public function setType(IssueType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStoryPointEstimate(): ?int
    {
        return $this->storyPointEstimate;
    }

    public function setStoryPointEstimate(?int $storyPointEstimate): static
    {
        $this->storyPointEstimate = $storyPointEstimate;

        return $this;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    public function setAssignee(?User $assignee): static
    {
        $this->assignee = $assignee;

        return $this;
    }

    public function getReporter(): ?User
    {
        return $this->reporter;
    }

    public function setReporter(?User $reporter): static
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * @return Collection<int, Attachment>
     */
    public function getAttachment(): Collection
    {
        return $this->attachment;
    }

    public function addAttachment(Attachment $attachement): static
    {
        if (!$this->attachment->contains($attachement)) {
            $this->attachment->add($attachement);
            $attachement->setIssue($this);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachement): static
    {
        if ($this->attachment->removeElement($attachement)) {
            // set the owning side to null (unless already changed)
            if ($attachement->getIssue() === $this) {
                $attachement->setIssue(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?IssueStatus
    {
        return $this->status;
    }

    public function setStatus(IssueStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getKeyCode(): ?string
    {
        return $this->project->getKeyCode(). '-' . $this->id;
    }
}
