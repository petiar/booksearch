<?php

namespace Petiar\Tests;

use Petiar\Booksearch\Booksearch;
use Petiar\Booksearch\Parser;
use PHPUnit\Framework\TestCase;

class BasicTests extends TestCase
{
  public function testDefaultResponse() {
    $search = new Booksearch();
    $result = $search->search('something');
    $this->assertEquals(count($result), 6);
  }

  public function testSimulatedResponse() {
    $shop = new ShopConfigFixture();
    $parser = new Parser();
    $parser->parse($shop, $this->correctJsonResponse());
    $this->assertEquals(count($parser->getResult()), 3);
  }

  private function correctJsonResponse() {
    return '{"data":[{"nazov":"Harry Potter a kamen mudrcov","cena":"10€"},{"nazov":"Harry Potter a tajomna komnata","cena":"11€"},{"nazov":"Harry Potter a vazen z Azkabanu","cena":"7€"}]}';
  }
}