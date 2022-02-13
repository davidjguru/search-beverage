<?php

namespace Drupal\search_beverage;

/**
 * Defines pagination interface.
 */
interface PaginationInterface {

  /**
   * Set items for pagination.
   *
   * @param array $items
   *   List of items.
   * @param int $per_page
   *   Items per page.
   * @param bool $create_pager
   *   Create pager. Default: TRUE.
   *
   * @return \Drupal\search_beverage\Pagination
   *   Self.
   */
  public function init(array $items, int $per_page, bool $create_pager = TRUE): Pagination;

  /**
   * Get page items for current page.
   *
   * @param int $current_page
   *   Current page number in the pagination.
   *
   * @return array
   *   List of paged items.
   */
  public function currentPageItems(int $current_page): array;

}
