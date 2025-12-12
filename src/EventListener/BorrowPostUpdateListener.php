<?php

namespace App\EventListener;

use App\Entity\Borrow;
use Doctrine\ORM\Event\PostUpdateEventArgs;

class BorrowPostUpdateListener
{
    public function postUpdate(Borrow $borrow, PostUpdateEventArgs $args): void
    {
        if ($borrow->getStatus() === 'returned') {
            file_put_contents(
                dirname(__DIR__, 2) . '/var/log/borrow.log',
                "Borrow {$borrow->getId()} returned\n",
                FILE_APPEND
            );
        }
    }
}
