<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait PrePersistCreatedAtTrait {
    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }
}