<?php

declare(strict_types=1);

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ListRequestDTO
{
    private const DEFAULT_LIMIT = 50;
    public function __construct(
        #[Assert\Range(min: 1)]
        public readonly int $limit = self::DEFAULT_LIMIT,
        #[Assert\Range(min: 1)]
        public readonly int $page = 1,
    ) {
    }

    public function offset(): int
    {
        return $this->limit * ($this->page - 1);
    }
}
