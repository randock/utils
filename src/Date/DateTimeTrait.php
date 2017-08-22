<?php

declare(strict_types=1);

namespace Randock\Utils\Date;

use Randock\Utils\Date\Definition\DateTimeTraitInterface;

/**
 * Class DateTimeTrait.
 */
trait DateTimeTrait
{
    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     */
    protected $updatedAt;

    /**
     * @var \DateTime|null
     */
    protected $deletedAt;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return DateTimeTraitInterface
     */
    public function setCreatedAt(\DateTime $createdAt): DateTimeTraitInterface
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return DateTimeTraitInterface
     */
    public function setUpdatedAt(\DateTime $updatedAt): DateTimeTraitInterface
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime $deletedAt
     *
     * @return DateTimeTraitInterface
     */
    public function setDeletedAt(\DateTime $deletedAt): DateTimeTraitInterface
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
