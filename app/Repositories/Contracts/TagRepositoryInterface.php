<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

interface TagRepositoryInterface extends RepositoryInterface
{
    public function getAllLatest(): Collection;

    public function createWithSlug(array $data): Tag;

    public function findByName(string $name): ?Tag;
}
