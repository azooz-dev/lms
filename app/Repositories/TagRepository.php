<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Tag);
    }

    public function getAllLatest(): Collection
    {
        return Tag::latest()->get();
    }

    public function createWithSlug(array $data): Tag
    {
        $data['slug'] = $this->generateSlug($data['name']);

        return Tag::create($data);
    }

    public function findByName(string $name): ?Tag
    {
        return Tag::where('name', $name)->first();
    }

    private function generateSlug(string $name): string
    {
        return strtolower(str_replace(' ', '-', $name));
    }
}
