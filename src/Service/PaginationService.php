<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;

class PaginationService
{
    public function paginate(QueryBuilder $qb, int $page = 1, int $limit = 10): array
    {
        $page = max(1, $page);
        $limit = max(1, $limit);

        $qb->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);

        $items = $qb->getQuery()->getResult();

        return [
            'items' => $items,
            'page'  => $page,
            'limit' => $limit,
            'count' => count($items),
        ];
    }
}
