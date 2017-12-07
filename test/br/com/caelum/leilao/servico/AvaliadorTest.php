<?php

namespace teste\br\com\caelum\leilao\servico;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\servico\Avaliador;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;
use test\br\com\caelum\leilao\dominio\LeilaoBuilder;

class AvaliadorTest extends TestCase
{
    private $avaliador;
    
    public function setUp() {
        echo "inicia\n";
        $this->avaliador = new Avaliador();
    }
    
    public function testDeveEntenderLanceEmOrdemCrescente()
    {
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
        
        $maiorEsperado = 700;
        $menorEsperado= 300;
        
        $this->assertEquals($maiorEsperado, $leiloeiro->getMaiorLance(), 0.0001);
        $this->assertEquals($menorEsperado, $leiloeiro->getMenorLance(), 0.0001);
    }
    
    public function testDeveEntenderLeilaoComApenasUmLance()
    {
        $joao = new Usuario("Joao");
        
        $leilao = new Leilao("Playstation 3 Novo");
        
        $leilao->propoe(new Lance($joao, 1000.0));
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $this->assertEquals(1000.0, $leiloeiro->getMaiorLance(), 0.0001);
        $this->assertEquals(1000.0, $leiloeiro->getMenorLance(), 0.0001);
    }
    
    public function testDeveEncontrarOsTresMaioresLances()
    {
        $joao = new Usuario("Joao");
        $pedro = new Usuario("Pedro");
        
        $leilaoBuilder = new LeilaoBuilder();
        $leilao = $leilaoBuilder->comDescricao("Playstation 3 Novo")
        ->comLance($joao, 100.0)
        ->comLance($pedro, 200.0)
        ->comLance($joao, 300.0)
        ->comLance($pedro, 400.0)
        ->cria();
        
        $this->avaliador->avalia($leilao);
        
        $maiores = $this->avaliador->getMaiores();
        
        $this->assertEquals(3, count($maiores));
        $this->assertEquals(400, $maiores[0]->getValor(), 0.0001);
        $this->assertEquals(300, $maiores[1]->getValor(), 0.0001);
        $this->assertEquals(200, $maiores[2]->getValor(), 0.0001);
    }
    
    /**
     * @beforeClass
     */
    public function antesClasse()
    {
        echo "executando metodo antes da classe \n";
    }
    
    /**
     * @before
     */
    public function antes()
    {
        echo "executando metodo antes \n";
    }
    
    /**
     * @after
     */
    public function depois()
    {
        echo "executando metodo depois \n";
    }
    
    /**
     * @afterClass
     */
    public function depoisClasse()
    {
        echo "executando metodo depois da classe \n";
    }
    
}

