<?php

namespace MPScholten\GithubApi\Api\Search;

use MPScholten\GithubApi\Api\AbstractApi;
use MPScholten\GithubApi\Api\PaginationIterator;
use MPScholten\GithubApi\Api\Repository\Repository;

class Search extends AbstractApi
{
    const ORDER_DESC = 'desc';
    const ORDER_ASC = 'asc';

    const SORT_BY_STARS = 'stars';
    const SORT_BY_FORKS = 'forks';
    const SORT_BY_LAST_UPDATED = 'updated';
    const SORT_BY_BEST_MATCH = null;

    /**
     * @param $keywords
     * @param null $sort
     * @param string $order
     * @throws \InvalidArgumentException
     * @return Repository[]
     */
    public function findRepositories($keywords, $sort = self::SORT_BY_BEST_MATCH, $order = self::ORDER_DESC)
    {
        $query = ['q' => $keywords];
        if ($sort !== self::SORT_BY_BEST_MATCH) {
            $allowed = [self::SORT_BY_STARS, self::SORT_BY_FORKS, self::SORT_BY_LAST_UPDATED];
            if (!in_array($sort, $allowed)) {
                throw new \InvalidArgumentException(sprintf(
                    'Invalid sort argument %s. Should be one of %s',
                    $sort,
                    implode(', ', $allowed)
                ));
            }

            $query['sort'] = $sort;
        }

        if ($order !== self::ORDER_DESC) {
            $query['order'] = $order;
        }

        $request = $this->client->get('search/repositories');
        $request->getQuery()->merge($query);

        return new PaginationIterator($this->client, $request, function ($response, $client) {
            $repositories = [];
            foreach ($response['items'] as $data) {
                $repository = new Repository($client);
                $repository->populate($data);

                $repositories[] = $repository;
            }

            return $repositories;
        });
    }
}
