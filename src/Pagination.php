<?php

namespace Drupal\search_beverage;

use Drupal\Core\Pager\PagerManagerInterface;

/**
 * Defines service for pagination.
 */
class Pagination implements PaginationInterface {

  /**
   * List of items for pagination.
   *
   * @var array
   */
  protected array $items = [];

  /**
   * Items per page.
   *
   * @var int
   */
  protected $perPage;

  /**
   * The pager manager.
   *
   * @var \Drupal\Core\Pager\PagerManagerInterface
   */
  protected $pagerManager;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Pager\PagerManagerInterface $pager_manager
   *   The pager manager.
   */
  public function __construct(PagerManagerInterface $pager_manager) {
    $this->pagerManager = $pager_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function init(array $items, int $per_page, bool $create_pager = TRUE): self {
    $this->items = $items;
    $this->perPage = $per_page;

    if ($create_pager) {
      $this->pagerManager->createPager(count($this->items), $this->perPage);
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function currentPageItems(int $current_page): array {
    $items_paged = array_chunk($this->items, $this->perPage);
    return $items_paged[$current_page] ?? [];
  }

}
