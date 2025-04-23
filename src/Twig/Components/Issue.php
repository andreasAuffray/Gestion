<?php

namespace App\Twig\Components;

use App\Entity\Attachment;
use App\Entity\Issue as IssueEntity;
use App\Service\AttachmentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
class Issue
{
    use ComponentToolsTrait;
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: ['description', 'summary'])]
    public IssueEntity $issue;

    /** @var Attachment[] */
    #[LiveProp]
    public array $attachments = [];

    #[LiveProp]
    public bool $isEditingSummary = false;
    
    #[LiveProp]
    public bool $isEditingDescription = false;

    public function __construct(
        private readonly AttachmentService $attachmentService,
        private readonly EntityManagerInterface $manager,
        private readonly ValidatorInterface $validator
    )
    {
    }

    #[LiveAction]
    public function activateEditingSummary(): void
    {
        $this->isEditingSummary = true;
    }

    #[LiveAction]
    public function activateEditingDescription(): void
    {
        $this->isEditingDescription = true;
    }

    #[LiveAction]
    public function saveSummary(): void
    {
        $errors = $this->validator->validate($this->issue);
        
        if (count($errors) > 0) {
            // [TODO] Handle validation issues here
            foreach ($errors as $error) {
            }

            return;
        }

        $this->isEditingSummary = false;
        
        $this->manager->flush();
    }

    #[LiveAction]
    public function saveDescription(): void
    {
        $errors = $this->validator->validate($this->issue);
        
        if (count($errors) > 0) {
            // [TODO] Handle validation issues here
            foreach ($errors as $error) {
            }

            return;
        }

        $this->isEditingDescription = false;
        
        $this->manager->flush();
    }

    #[LiveAction]
    public function addAttachment(Request $request)
    {
        $attachment = $this->attachmentService->handleUploadedAttachment($this->issue, $request);
        
        if ($attachment) {
            $this->attachments = $this->issue->getAttachment()->toArray();
        }
    }
    
    #[LiveAction]
    public function deleteAttachment(#[LiveArg] int $attachmentId): void
    {
        $attachment = $this->manager->getRepository(Attachment::class)->find($attachmentId);
        
        if (!$attachment) {
            return;
        }

        if ($attachment->getIssue()->getId() !== $this->issue->getId()) {
            return;
        }

        $this->issue->removeAttachment($attachment);

        $this->manager->remove($attachment);
        $this->manager->flush();

        $this->attachments = $this->issue->getAttachment()->toArray();
    }
}
