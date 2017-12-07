<?php
namespace test\br\com\caelum\leilao\dominio;

use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\dominio\FiltroDeLances;
use src\br\com\caelum\leilao\dominio\Lance;
use PHPUnit\Framework\TestCase;

class FiltroDeLancesTest extends TestCase
{
    public function testDeveSelecionarLancesEntre1000e3000()
    {
        $joao = new Usuario("Joao");
        $filtro = new FiltroDeLances();
        
        $resultado = $filtro->filtra(array(
            new Lance($joao, 2000),
            new Lance($joao, 1000),
            new Lance($joao, 3000),
            new Lance($joao, 800))
        );
        
        $this->assertEquals(1, count($resultado));
        $this->assertEquals(2000, $resultado[0]->getValor(), 0.0001);
    }
    
    public function testDeveSelecionarLancesEntre500e700()
    {
        $joao = new Usuario("Joao");
        $filtro = new FiltroDeLances();
        
        $resultado = $filtro->filtra(array(
            new Lance($joao, 600),
            new Lance($joao, 500),
            new Lance($joao, 700),
            new Lance($joao, 800))
            );
        
        $this->assertEquals(1, count($resultado));
        $this->assertEquals(600, $resultado[0]->getValor(), 0.0001);
    }
}

