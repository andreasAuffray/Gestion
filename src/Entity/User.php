<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'leadUser')]
    private Collection $leadedProjects;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'members')]
    private Collection $projects;

    /**
     * @var Collection<int, Issue>
     */
    #[ORM\OneToMany(targetEntity: Issue::class, mappedBy: 'assignee')]
    private Collection $assignedIssues;

    /**
     * @var Collection<int, Issue>
     */
    #[ORM\OneToMany(targetEntity: Issue::class, mappedBy: 'reporter')]
    private Collection $reportedIssues;

    #[ORM\ManyToOne]
    private ?Project $selectedProject = null;

    public function __construct()
    {
        $this->leadedProjects = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->assignedIssues = new ArrayCollection();
        $this->reportedIssues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __toString(): string
    {
        return $this->username;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getLeadedProjects(): Collection
    {
        return $this->leadedProjects;
    }

    public function addLeadedProject(Project $leadedProject): static
    {
        if (!$this->leadedProjects->contains($leadedProject)) {
            $this->leadedProjects->add($leadedProject);
            $leadedProject->setLeadUser($this);
        }

        return $this;
    }

    public function removeLeadedProject(Project $leadedProject): static
    {
        if ($this->leadedProjects->removeElement($leadedProject)) {
            // set the owning side to null (unless already changed)
            if ($leadedProject->getLeadUser() === $this) {
                $leadedProject->setLeadUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addMember($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeMember($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Issue>
     */
    public function getAssignedIssues(): Collection
    {
        return $this->assignedIssues;
    }

    public function addAssignedIssue(Issue $assignedIssue): static
    {
        if (!$this->assignedIssues->contains($assignedIssue)) {
            $this->assignedIssues->add($assignedIssue);
            $assignedIssue->setAssignee($this);
        }

        return $this;
    }

    public function removeAssignedIssue(Issue $assignedIssue): static
    {
        if ($this->assignedIssues->removeElement($assignedIssue)) {
            // set the owning side to null (unless already changed)
            if ($assignedIssue->getAssignee() === $this) {
                $assignedIssue->setAssignee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Issue>
     */
    public function getReportedIssues(): Collection
    {
        return $this->reportedIssues;
    }

    public function addReportedIssue(Issue $reportedIssue): static
    {
        if (!$this->reportedIssues->contains($reportedIssue)) {
            $this->reportedIssues->add($reportedIssue);
            $reportedIssue->setReporter($this);
        }

        return $this;
    }

    public function removeReportedIssue(Issue $reportedIssue): static
    {
        if ($this->reportedIssues->removeElement($reportedIssue)) {
            // set the owning side to null (unless already changed)
            if ($reportedIssue->getReporter() === $this) {
                $reportedIssue->setReporter(null);
            }
        }

        return $this;
    }

    public function getSelectedProject(): ?Project
    {
        return $this->selectedProject;
    }

    public function setSelectedProject(?Project $selectedProject): static
    {
        $this->selectedProject = $selectedProject;

        return $this;
    }
}
