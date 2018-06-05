<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FAQCategoryRepository")
 */
class FAQCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\OneToMany(
     *      targetEntity="FAQ",
     *      mappedBy="category",
     *      orphanRemoval=true,
     *      cascade={"persist"}
     * )
     */
    private $faqs;

    /**
     * @ORM\Column(name="enable", type="boolean")
     */
    private $enable = true;

    /**
     * @ORM\Column(name="published_at", type="datetime")
     */
    private $publishedAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
        $this->updatedAt   = new \DateTime();
        $this->faqs        = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFAQs(): Collection
    {
        return $this->faqs;
    }
    public function addFAQ(?FAQ $faq): void
    {
        $faq->setCategory($this);
        if (!$this->faqs->contains($faq))
            $this->faqs->add($faq);
    }
    public function removeFAQ(FAQ $faq): void
    {
        $faq->setCategory(null);
        $this->faqs->removeElement($faq);
    }

    public function setEnable(bool $enable): void
    {
        $this->enable = $enable;
    }

    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeInterface $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}

/*
    public function updateVideoPosition(ProgramId $programId, VideoId $videoId, int $position): void
    {
        $currentPosition = $this->getVideoPosition($programId, $videoId);
        $maxPosition = $this->getMaxPosition($programId);

        if ($position > $maxPosition) {
            throw new \InvalidArgumentException("Position can't be higher then {$maxPosition}");
        }

        if ($position === $currentPosition) {
            return;
        }

        $this->em->beginTransaction();

        if ($position > $currentPosition) {
            $query = $this->em->createQuery('
                UPDATE
                    Core:Video\ProgramVideo programVideo
                SET
                  programVideo.position = programVideo.position - 1
                WHERE
                  programVideo.position > :currentPosition AND
                  programVideo.position <= :position AND
                  programVideo.programId = :programId AND
                  programVideo.videoId <> :videoId
            ');

            $query->execute([
                'currentPosition' => $currentPosition,
                'position' => $position,
                'programId' => $programId,
                'videoId' => $videoId
            ]);
        }
        else {
            $query = $this->em->createQuery('
                UPDATE
                    Core:Video\ProgramVideo programVideo
                SET
                  programVideo.position = programVideo.position + 1
                WHERE
                  programVideo.position >= :position AND
                  programVideo.position < :currentPosition AND
                  programVideo.programId = :programId AND
                  programVideo.videoId <> :videoId
            ');

            $query->execute([
                'currentPosition' => $currentPosition,
                'position' => $position,
                'programId' => $programId,
                'videoId' => $videoId
            ]);
        }

        $query = $this->em->createQuery('
            UPDATE
                Core:Video\ProgramVideo programVideo
            SET
                programVideo.position = :position
            WHERE
                programVideo.programId = :programId AND
                programVideo.videoId = :videoId
        ');

        $query->execute([
            'position' => $position,
            'programId' => $programId,
            'videoId' => $videoId
        ]);

        $this->em->commit();
    }
 */