<?php

namespace Petiar\Booksearch;

class Parser
{
  private $result;

  private const SORT_BY = [
      'name' => SORT_ASC,
      'price' => SORT_ASC,
    ];

  public function parse(BookshopApiInterface $bookShop, string $jsonResponse) {
    $map = $bookShop->getMap();
    $response = json_decode($jsonResponse, true);
    // tu si zistíme, či je iterable array ukryté ešte pod nejakým kľúčom, alebo tú reponse
    // môžeme iterovať rovno
    $response = isset($map['iterable']) ? $response[$map['iterable']] : $response;
    // tuto ideme cez reponse array
    foreach ($response as $record) {
      // a pre každý záznam si prejdeme mapping config a vytvoríme pole $book,
      // teda záznam o 1 knihe
      $book = [];
      foreach ($map['map'] as $key => $mapValue) {
        // ak v zázname z response existuje hodnota s kľúčom z mappingu, ideme ďalej
        if (isset($record[$mapValue['value']])) {
          // v prípade, že pre danú hodnotu máme v configu process callable,
          // tak cez ňu prežeňme hodnotu z response
          if (isset($mapValue['process']) && is_callable([
              $bookShop,
              $mapValue['process']
            ])) {
            $book[$key] = $bookShop->{$mapValue['process']}($record[$mapValue['value']]);
          }
          else {
            $book[$key] = $record[$mapValue['value']];
          }
        }
        // ak ten kľúč v response nebol, tak tam dajme len prázdny string
        else {
          $book[$key] = '';
        }
      }
      $book['bookshop'] = $bookShop->name;
      $this->result[] = $book;
    }
  }

  /**
   * Také variabilné sortovanie - dajú sa určite stĺpce aj smer sortovania.
   * @return void
   */
  public function sort() {
      $arguments = [];
      $rows = $this->result;
      foreach (self::SORT_BY as $column => $order) {
        $arguments[] = array_column($this->result, $column);
        $arguments[] = $order;
      }
      $arguments[] = &$rows;
      call_user_func_array('array_multisort', $arguments);
      $this->result = $rows;
  }

  public function getResult() {
    return $this->result;
  }
}