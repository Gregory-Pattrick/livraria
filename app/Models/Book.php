<?php

declare(strict_types=1);

namespace App\Models;

final class Book
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $author,
        public readonly string $category,
        public readonly int $publishedYear,
        public readonly string $status,
        public readonly string $createdAt,
        public readonly string $updatedAt
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['id'],
            (string) $data['title'],
            (string) $data['author'],
            (string) $data['category'],
            (int) $data['published_year'],
            (string) $data['status'],
            (string) $data['created_at'],
            (string) $data['updated_at']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'category' => $this->category,
            'published_year' => $this->publishedYear,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
