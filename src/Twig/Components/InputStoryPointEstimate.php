<?php

namespace App\Twig\Components;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
class InputStoryPointEstimate
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;
    
    #[LiveProp(writable:['storyPointEstimate'])]
    public \App\Entity\Issue $issue;

    #[LiveAction]
    public function updateStoryPointEstimate(EntityManagerInterface $manager): void
    {
        $this->validate();

        $manager->flush();
    }
}
