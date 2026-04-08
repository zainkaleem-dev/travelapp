<?php

namespace App\Services;

use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class PaginationService
{
    /**
     * Paginate a collection or query builder result
     *
     * @param mixed $items Collection or Query Builder instance
     * @param int $perPage Items per page (default: 15)
     * @param int $page Current page number (default: 1)
     * @param array $options Additional pagination options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($items, int $perPage = 15, int $page = null, array $options = [])
    {
        $page = $page ?? Paginator::resolveCurrentPage();

        // Get total count
        $total = $items instanceof Collection
            ? $items->count()
            : $items->count();

        // Calculate offset
        $offset = ($page - 1) * $perPage;

        // Get paginated items
        if ($items instanceof Collection) {
            $paginatedItems = $items->slice($offset, $perPage)->values();
        } else {
            // For Query Builder
            $paginatedItems = $items->offset($offset)->limit($perPage)->get();
        }

        // Default options
        $defaultOptions = [
            'path' => $options['path'] ?? url()->current(),
            'query' => $options['query'] ?? request()->query(),
            'fragment' => $options['fragment'] ?? null,
        ];

        // Create paginator instance
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $total,
            $perPage,
            $page,
            $defaultOptions
        );
    }

    /**
     * Get pagination meta data
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginator
     * @return array
     */
    public function getPaginationMeta($paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'has_more' => $paginator->hasMorePages(),
        ];
    }

    /**
     * Simple array paginator
     *
     * @param array $items Array of items to paginate
     * @param int $perPage Items per page
     * @param int $page Current page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginateArray(array $items, int $perPage = 15, int $page = null, array $options = [])
    {
        return $this->paginate(collect($items), $perPage, $page, $options);
    }
}
