<?php

namespace App\Service;

use App\Entity\Attachment;
use App\Entity\Issue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class AttachmentService
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        #[Autowire('%absolute_attachments_directory%')] private readonly string $absoluteAttachmentsDirectory,
        #[Autowire('%attachments_directory%')] private readonly string $attachmentsDirectory,
    )
    {
    }

    public function handleUploadedAttachment(Issue $issue, Request $request): ?Attachment
    {
        /** @var ?UploadedFile $attachmentFile */
        $attachmentFile = $request->files->get('attachment');

        if (!$attachmentFile) {
            return null;
        }

        $filename = $this->uniqueFilename($attachmentFile);

        $attachment = new Attachment();
        $attachment->setIssue($issue);
        $attachment->setOriginalName($attachmentFile->getClientOriginalName());

        $attachment->setPath($this->absoluteAttachmentsDirectory . '/' . $filename);
        $attachmentFile->move($this->attachmentsDirectory, $filename);

        $this->manager->persist($attachment);
        $this->manager->flush();

        return $attachment;
    }

    private function uniqueFilename(UploadedFile $file): string
    {
        return uniqid(more_entropy: true) . '.' . $file->guessExtension();
    }

    public function delete(Attachment $attachment): void
    {
        $dbPath = $attachment->getPath();
        $filename = basename($dbPath);

        $filepath = $this->attachmentsDirectory . '/' . $filename;

        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
}
