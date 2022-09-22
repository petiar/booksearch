<?php

namespace Petiar\Booksearch;

use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Booksearch {

  private $twig;
  private $parser;
  /**
   * Zoznam configov pre jednotlivé kníhkupectvá.
   */
  private const BOOKSHOPS = [
    'Petiar\Booksearch\Apis\BlackBooksApi',
    'Petiar\Booksearch\Apis\NottingHillApi',
  ];

  /**
   * Inicializácia Twig-u.
   */
  public function __construct() {
    $loader = new FilesystemLoader('templates');
    $this->twig = new Environment($loader);
  }

  /**
   * Metóda pre prehľadávanie kníhkupectiev. Pre každé spraví GET request, získa response,
   * vezme config a pošle to do parsera, ktorý to podľa configov rozparsuje.
   */
  public function search($title): array {
    $this->parser = new Parser();

    // Prejdeme si zoznam class a skontrolujeme, či máme configy pre všetky.
    foreach ($this::BOOKSHOPS as $configClass) {
      if (class_exists($configClass)) {
        $bookShopConfig = new $configClass;

        // prehľadajme eshop
        $endpoint = $bookShopConfig->getEndpoint();
        $client = $this->mockRequest($endpoint);
        $response = $client->request('GET', $endpoint . $title)->getContent();

        // rozparsujme to
        $this->parser->parse($bookShopConfig, $response);

        // keď máme všetky výsledky, tak ich zotriedime
        $this->parser->sort();
      }
    }
    return $this->parser->getResult();
  }

  /**
   * Veľmi jednoduché zobrazenie výsledkov cez Twig template
   *
   * @return void
   */
  public function displayResults(): void {
    $template = $this->twig->load('result.html.twig');
    echo $template->render(['results' => $this->parser->getResult()]);
  }

  /**
   * Toto simuluje requesty a response a podľa URL vráti príslušný response.
   * @param $url
   *
   * @return \Symfony\Component\HttpClient\MockHttpClient
   */
  private function mockRequest($url): MockHttpClient {
    $responses = [
      'https://bernardblack.com?name=' => new MockResponse('{"data":[{"nazov":"Harry Potter a kamen mudrcov","cena":"10€"},{"nazov":"Harry Potter a tajomna komnata","cena":"11€"},{"nazov":"Harry Potter a vazen z Azkabanu","cena":"7€"}]}'),
      'https://rufusthethief.com?name=' => new MockResponse('[{"title":"Harry Potter a kamen mudrcov","price":"7","currency":"€","lang":"SK"},{"title":"Harry Potter a tajomna komnata","price":"15","currency":"€","lang":"SK"},{"title":"Harry Potter a vazen z Azkabanu","price":"18","currency":"€","lang":"SK"}]'),
    ];
    return new MockHttpClient($responses[$url]);
  }
}