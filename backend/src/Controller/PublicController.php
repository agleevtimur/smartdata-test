<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class PublicController extends AbstractController
{
    public function getAllBookAuthor(BookRepository $bookRepository): JsonResponse
    {
        $result = [];
        $booksAuthor = $bookRepository->findAllWithAuthor();

        array_walk($booksAuthor, function ($value) use (&$result) {
            $author = $value['author'];
            unset($value['author']);
            if (isset($result[$author['id']])) {
                $result[$author['id']]['books'][] = $value;
            } else {
                $result[$author['id']] = ['author' => $author, 'books' => [$value]];
            }
        });

        return $this->json(array_values($result), 200, ['Access-Control-Allow-Origin' => '*']);
    }
}