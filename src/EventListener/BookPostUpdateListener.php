<?php

namespace App\EventListener;

use App\Entity\Book;
use Doctrine\ORM\Event\PostUpdateEventArgs;

class BookPostUpdateListener
{
    public function postUpdate(Book $book, PostUpdateEventArgs $args): void
    {
        if ($args->hasChangedField('title')) {
            file_put_contents(
                dirname(__DIR__, 2) . '/var/log/book.log',
                "Book {$book->getId()} title changed\n",
                FILE_APPEND
            );
        }
    }
}
