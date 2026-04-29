<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Repositories\BookRepository;

final class BookController
{
    private BookRepository $repository;

    public function __construct()
    {
        $this->repository = new BookRepository();
    }

    public function index(): void
    {
        $books = array_map(fn ($book): array => $book->toArray(), $this->repository->all());

        Response::json([
            'success' => true,
            'message' => 'Livros encontrados com sucesso.',
            'data' => $books,
            'total' => count($books),
        ]);
    }

    public function show(string $id): void
    {
        $book = $this->repository->find((int) $id);

        if (!$book) {
            Response::json(['success' => false, 'message' => 'Livro não encontrado.'], 404);
            return;
        }

        Response::json(['success' => true, 'data' => $book->toArray()]);
    }

    public function store(): void
    {
        $data = Request::json();
        $errors = $this->validate($data, true);

        if ($errors) {
            Response::json(['success' => false, 'message' => 'Dados inválidos.', 'errors' => $errors], 422);
            return;
        }

        $book = $this->repository->create($data);

        Response::json([
            'success' => true,
            'message' => 'Livro cadastrado com sucesso.',
            'data' => $book->toArray(),
        ], 201);
    }

    public function update(string $id): void
    {
        $data = Request::json();
        $errors = $this->validate($data, false);

        if ($errors) {
            Response::json(['success' => false, 'message' => 'Dados inválidos.', 'errors' => $errors], 422);
            return;
        }

        $book = $this->repository->update((int) $id, $data);

        if (!$book) {
            Response::json(['success' => false, 'message' => 'Livro não encontrado.'], 404);
            return;
        }

        Response::json([
            'success' => true,
            'message' => 'Livro atualizado com sucesso.',
            'data' => $book->toArray(),
        ]);
    }

    public function destroy(string $id): void
    {
        if (!$this->repository->delete((int) $id)) {
            Response::json(['success' => false, 'message' => 'Livro não encontrado.'], 404);
            return;
        }

        Response::json(['success' => true, 'message' => 'Livro removido com sucesso.']);
    }

    private function validate(array $data, bool $required): array
    {
        $errors = [];
        $requiredFields = ['title', 'author', 'category', 'published_year'];

        foreach ($requiredFields as $field) {
            if ($required && (!isset($data[$field]) || trim((string) $data[$field]) === '')) {
                $errors[$field][] = 'Campo obrigatório.';
            }
        }

        foreach (['title', 'author', 'category'] as $field) {
            if (isset($data[$field]) && strlen(trim((string) $data[$field])) < 2) {
                $errors[$field][] = 'Informe pelo menos 2 caracteres.';
            }
        }

        if (isset($data['published_year'])) {
            $year = (int) $data['published_year'];
            $currentYear = (int) date('Y');
            if ($year < 1000 || $year > $currentYear) {
                $errors['published_year'][] = "Informe um ano entre 1000 e {$currentYear}.";
            }
        }

        if (isset($data['status']) && !in_array($data['status'], ['available', 'borrowed'], true)) {
            $errors['status'][] = 'Use available ou borrowed.';
        }

        return $errors;
    }
}
