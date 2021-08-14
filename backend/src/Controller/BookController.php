<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private AuthorRepository $authorRepository;
    private BookRepository $bookRepository;

    public function __construct(EntityManagerInterface $entityManager, AuthorRepository $authorRepository, BookRepository $bookRepository)
    {
        $this->entityManager = $entityManager;
        $this->authorRepository = $authorRepository;
        $this->bookRepository = $bookRepository;
    }

    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $author = $this->authorRepository->find($data['authorId']);

        if ($author === null) {
            return $this->json(['error' => 'unknown author'], 500);
        }

        $book = (new Book())
            ->setAuthor($author)
            ->setTitle($data['title'])
            ->setGenre($data['genre'])
            ->setWritingDate(new DateTime($data['writingDate']))
            ->setDescription($data['description']);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $this->json($book->getId());
    }

    public function read(int $id): JsonResponse
    {
        $book = $this->bookRepository->find($id);

        if ($book === null) {
            return $this->json(['error' => 'book not found'], 500);
        }

        return $this->json($book);
    }

    public function update(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $book = $this->bookRepository->find($data['bookId']);

        if ($book === null) {
            return $this->json(['error' => 'book not found'], 500);
        }

        $this->entityManager->persist($book);
        $book
            ->setAuthor($data['authorId'])
            ->setTitle($data['title'])
            ->setGenre($data['genre'])
            ->setWritingDate($data['writingDate'])
            ->setDescription($data['description']);
        $this->entityManager->flush();

        return $this->json(null);
    }

    public function delete(int $id): JsonResponse
    {
        $book = $this->bookRepository->find($id);

        if ($book === null) {
            return $this->json(['error' => 'book not found'], 500);
        }

        $this->entityManager->remove($book);
        $this->entityManager->flush();

        return $this->json(null);
    }
}