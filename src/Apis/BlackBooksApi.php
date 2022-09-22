<?php

namespace Petiar\Booksearch\Apis;

use Petiar\Booksearch\BookshopApiInterface;

class BlackBooksApi implements BookshopApiInterface {

  public $name = 'Black Books by Bernard Black';
  /**
   * @inheritDoc
   */
  public function getEndpoint(): string {
    return "https://bernardblack.com?name=";
  }

  /**
   * @inheritDoc
   */
  public function getMap(): array {
    return [
      'iterable' => 'data',
      'map' => [
        'name' => [
          'value' => 'nazov',
        ],
        'price' => [
          'value' => 'cena',
          'process' => 'getPrice',
        ],
        'currency' => [
          'value' => 'cena',
          'process' => 'getCurrency',
        ],
        'language' => [
          'value' => 'lang',
        ],
      ]
    ];
  }

  /**
   * Pohľadáme v reťazci číslo a vrátime jeho hodnotu.
   *
   * @param $value
   *
   * @return mixed
   * @throws \ErrorException
   */
  public function getPrice($value): string {
    preg_match('/\d+\.?\d*/', $value, $matches);
    if (count($matches) != 1) {
      throw new \ErrorException('Illegal format of price field in ' . self::class);
    }
    return $matches[0];
  }

  /**
   * Pohľadáme v reťazci string a vrátime jeho hodnotu.
   * Neriešim currency, že či to bude symbol, alebo ISO skratka, ale dalo by sa aj to.
   *
   * @throws \ErrorException
   */
  public function getCurrency($value): string {
    preg_match('/[^0-9.]+/', $value, $matches);
    if (count($matches) != 1) {
      throw new \ErrorException('Illegal format of price field in ' . self::class);
    }
    return $matches[0];
  }
}