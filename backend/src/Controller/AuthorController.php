<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private AuthorRepository $authorRepository;

    public function __construct(EntityManagerInterface $entityManager, AuthorRepository $authorRepository)
    {
        $this->entityManager = $entityManager;
        $this->authorRepository = $authorRepository;
    }

    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $author = (new Author())
            ->setName($data['name'])
            ->setBirthday($data['birthday'])
            ->setCountry($data['country'])
            ->setDescription($data['description']);
        $this->entityManager->persist($author);
        $this->entityManager->flush();

        return $this->json(null);
    }

    public function read(int $id): JsonResponse
    {
        $author = $this->authorRepository->find($id);

        if ($author === null) {
            return $this->json(['error' => 'author not found'], 500);
        }

        return $this->json($author);
    }

    public function update(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $author = $this->authorRepository->find($data['authorId']);

        if ($author === null) {
            return $this->json(['error' => 'author not found'], 500);
        }

        $this->entityManager->persist($author);
        $author
            ->setName($data['name'])
            ->setCountry($data['country'])
            ->setBirthday($data['birthday'])
            ->setDescription($data['description']);
        $this->entityManager->flush();

        return $this->json(null);
    }

    public function delete(int $id): JsonResponse
    {
        $author = $this->authorRepository->find($id);

        if ($author === null) {
            return $this->json(['error' => 'author not found'], 500);
        }

        $this->entityManager->remove($author);
        $this->entityManager->flush();

        return $this->json(null);
    }
}