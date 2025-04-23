<?php

namespace App\Service;

use App\Repository\ProjectRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Project;

class ProjectService
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly ProjectRepository $projectRepo
    ){
    }

    public function getProjectsList(User $user): array
    {
        $projects = [];

        foreach ($user->getProjects() as $project) {
            $projects[$project->getKeyCode()] = [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'keyCode' => $project->getKeyCode(),
                'lead' => (string) $project->getLeadUser(),
            ];
        }

        return $projects;
    }

    public function findOneByKeyCode(string $keyCode)
    {
        return $this->projectRepo->findOneBy(['keyCode' => $keyCode]);
    }

    public function remove(Project $project)
    {
        $this->manager->remove($project);
        $this->manager->flush();
    }
}
