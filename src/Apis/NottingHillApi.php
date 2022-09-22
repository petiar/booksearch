<?php

namespace Petiar\Booksearch\Apis;

use Petiar\Booksearch\BookshopApiInterface;

class NottingHillApi implements BookshopApiInterface {

  public $name = 'Notting Hill by William Thacker';
  /**
   * @inheritDoc
   */
  public function getEndpoint(): string {
    return "https://rufusthethief.com?name=";
  }

  /**
   * @inheritDoc
   */
  public function getMap(): array {
    return [
      'map' => [
        'name' => [
          'value' => 'title',
        ],
        'price' => [
          'value' => 'price',
        ],
        'currency' => [
          'value' => 'currency',
        ],
        'language' => [
          'value' => 'lang'
        ],
      ],
    ];
  }
}