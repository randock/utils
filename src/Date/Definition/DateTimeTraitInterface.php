<?php

declare(strict_types=1);

namespace Randock\Utils\Date\Definition;

/**
 * Interface DateTimeTraitInterface.
 */
interface DateTimeTraitInterface
{
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;

    /**
     * @param \Datetime $createdAt
     *
     * @return DateTimeTraitInterface
     */
    public function setCreatedAt(\Datetime $createdAt): self;

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime;

    /**
     * @param \Datetime $updatedAt
     *
     * @return DateTimeTraitInterface
     */
    public function setUpdatedAt(\Datetime $updatedAt): self;

    /**
     * @return \DateTime|null
     */
    public function getDeletedAt(): ?\DateTime;

    /**
     * @param \Datetime $deletedAt
     *
     * @return DateTimeTraitInterface
     */
    public function setDeletedAt(\Datetime $deletedAt): ?self;
}
