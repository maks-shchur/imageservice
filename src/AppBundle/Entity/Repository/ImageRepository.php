<?php

namespace AppBundle\Entity\Repository;

use \Doctrine\ORM\EntityRepository;
use \Doctrine\ORM\QueryBuilder;
use \Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ImageRepository
 */
class ImageRepository extends EntityRepository
{
    /**
     * @param int $page
     * @param int $maxItems
     * @return array
     */
    public function findAllByPagination($page = 1, $maxItems = 10)
    {
        /** @var QueryBuilder $query */
        $query = $this->createQueryBuilder("i");

        return $this->paginate($query->getQuery(), $page, $maxItems);
    }

    /**
     * @param $tags
     * @return array
     */
    public function findAllByTags($tags)
    {
        /** @var QueryBuilder $query */
        $query = $this->createQueryBuilder("i");
        $query
            ->leftJoin('i.tags', 't')
            ->where($query->expr()->in('t.tag', $tags));

        return $query->getQuery()->getResult();
    }

    /**
     * @param Query $query
     * @param int $page
     * @param int $maxItems
     *
     * @return array
     */
    private function paginate(Query $query, $page = 1, $maxItems = 10)
    {
        /** @var Paginator $paginator */
        $paginator = new Paginator($query);
        $paginator
            ->getQuery()
            ->setFirstResult(($page - 1) * $maxItems)
            ->setMaxResults($maxItems);

        $count = $paginator->count();
        $pages = ceil($count/$maxItems);

        return [
            'currentPage' => (int)$page,
            'pagesCount' => (int)$pages,
            'count' => $count,
            'results' => $paginator->getQuery()->getResult()
        ];
    }
}
