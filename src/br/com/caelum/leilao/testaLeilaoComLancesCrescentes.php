<?php

namespace src\br\com\caelum\leilao;

require_once 'vendor/autoload.php';

use src\br\com\caelum\leilao\servico\Avaliador;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;

$joao = new Usuario("Joao");
$pedro = new Usuario("Pedro");

$leilao = new Leilao("Playstation 3 Novo");

$leilao->propoe(new Lance($joao, 300.0));
$leilao->propoe(new Lance($pedro, 400.0));
$leilao->propoe(new Lance($joao, 500.0));
$leilao->propoe(new Lance($pedro, 600.0));
$leilao->propoe(new Lance($joao, 700.0));

$leiloeiro = new Avaliador();
$leiloeiro->avalia($leilao);

echo $leiloeiro->getMaiorLance() . "\n";
echo $leiloeiro->getMenorLance();