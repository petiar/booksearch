<?php

use Petiar\Booksearch\Booksearch;

require ('vendor/autoload.php');

$search = new Booksearch();
$search->search('title');
$search->displayResults();
