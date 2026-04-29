<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Book;

final class BookRepository
{
    private string $filePath;

    public function __construct()
    {
        $this->filePath = __DIR__ . '/../../data/books.json';
        $this->ensureDatabaseExists();
    }

    /** @return Book[] */
    public function all(): array
    {
        return array_map(fn (array $book): Book => Book::fromArray($book), $this->read());
    }

    public function find(int $id): ?Book
    {
        foreach ($this->read() as $book) {
            if ((int) $book['id'] === $id) {
                return Book::fromArray($book);
            }
        }

        return null;
    }

    public function create(array $data): Book
    {
        $books = $this->read();
        $now = date('c');
        $nextId = empty($books) ? 1 : max(array_column($books, 'id')) + 1;

        $book = [
            'id' => $nextId,
            'title' => trim((string) $data['title']),
            'author' => trim((string) $data['author']),
            'category' => trim((string) $data['category']),
            'published_year' => (int) $data['published_year'],
            'status' => $data['status'] ?? 'available',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $books[] = $book;
        $this->write($books);

        return Book::fromArray($book);
    }

    public function update(int $id, array $data): ?Book
    {
        $books = $this->read();

        foreach ($books as $index => $book) {
            if ((int) $book['id'] !== $id) {
                continue;
            }

            $updated = array_merge($book, array_filter([
                'title' => isset($data['title']) ? trim((string) $data['title']) : null,
                'author' => isset($data['author']) ? trim((string) $data['author']) : null,
                'category' => isset($data['category']) ? trim((string) $data['category']) : null,
                'published_year' => isset($data['published_year']) ? (int) $data['published_year'] : null,
                'status' => isset($data['status']) ? trim((string) $data['status']) : null,
            ], fn ($value) => $value !== null));

            $updated['updated_at'] = date('c');
            $books[$index] = $updated;
            $this->write($books);

            return Book::fromArray($updated);
        }

        return null;
    }

    public function delete(int $id): bool
    {
        $books = $this->read();
        $filtered = array_values(array_filter($books, fn (array $book): bool => (int) $book['id'] !== $id));

        if (count($books) === count($filtered)) {
            return false;
        }

        $this->write($filtered);
        return true;
    }

    private function ensureDatabaseExists(): void
    {
        if (!is_dir(dirname($this->filePath))) {
            mkdir(dirname($this->filePath), 0777, true);
        }

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    private function read(): array
    {
        $content = file_get_contents($this->filePath);
        $data = json_decode($content ?: '[]', true);

        return is_array($data) ? $data : [];
    }

    private function write(array $books): void
    {
        file_put_contents($this->filePath, json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
