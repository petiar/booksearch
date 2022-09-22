<?php

namespace Petiar\Booksearch;

interface BookshopApiInterface {

  /**
   * Endpoint pre konkrétne kníhkupectvo
   *
   * @return string
   */
  function getEndpoint(): string;

  /**
   * Konfigurácia mapovania hodnôt pre konkrétne kníhkupectvo.
   * iterable - ktorý kľúč z JSON response sa dá iterovať
   * map - jednotlivé hodnoty
   *   value - kľúč z JSON response
   *   process - callable, ak treba hodnotu spracovať
   *   required - default je true, ale ak hodnotu netreba, tak false
   *
   * [
   *   'iterable' => '',
   *   'map' => [
   *     'value' => '',
   *     'process' => '',
   *   ],
   * ]
   *
   * @return array
   */
  function getMap(): array;
}