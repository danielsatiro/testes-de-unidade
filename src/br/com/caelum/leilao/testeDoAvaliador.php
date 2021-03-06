<?php

require_once __DIR__ . '/dominio/Lance.php';
require_once __DIR__ . '/dominio/Leilao.php';
require_once __DIR__ . '/dominio/Usuario.php';
require_once __DIR__ . '/servico/Avaliador.php';

use src\br\com\caelum\leilao\servico\Avaliador;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;

$joao = new Usuario("Joao");
$jose = new Usuario("Jose");
$maria = new Usuario("Maria");

$leilao = new Leilao("Playstation 3 Novo");

$leilao->propoe(new Lance($joao, 300.0));
$leilao->propoe(new Lance($jose, 400.0));
$leilao->propoe(new Lance($maria, 250.0));

$leiloeiro = new Avaliador();
$leiloeiro->avalia($leilao);

echo $leiloeiro->getMaiorLance() . "\n";
echo $leiloeiro->getMenorLance();