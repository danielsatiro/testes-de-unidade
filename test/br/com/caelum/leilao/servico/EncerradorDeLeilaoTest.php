<?php
namespace test\br\com\caelum\leilao\servico;

use PHPUnit\Framework\TestCase;
use test\br\com\caelum\leilao\dominio\LeilaoBuilder;
use src\br\com\caelum\leilao\servico\EncerradorDeLeilao;
use src\br\com\caelum\leilao\dao\LeilaoDao;
use src\br\com\caelum\leilao\servico\Carteiro;
use src\br\com\caelum\leilao\interfaces\EnviadorDeEmail;

class EncerradorDeLeilaoTest extends TestCase
{
    public function testDeveEncerrarLeiloesQueComeramUmaSemanaAtrasOuAntes() {
        $antiga = new \DateTime('1999-01-20');
        
        $leilao1 = new LeilaoBuilder();
        $leilao1 = $leilao1->comDescricao("TV de plasma")
        ->naData($antiga)
        ->cria();
        
        $leilao2 = new LeilaoBuilder();
        $leilao2 = $leilao2->comDescricao("Geladeira")
        ->naData($antiga)
        ->cria();
        
        $daoFalso = $this->createMock(LeilaoDao::class);
        $daoFalso->method('correntes')->will($this->returnValue(array($leilao1, $leilao2)));
        $daoFalso->expects($this->atLeast(2))->method('atualiza')
        ->will($this->throwException(new \PDOException()));
        
        $carteiro = $this->createMock(EnviadorDeEmail::class);
        $carteiro->expects($this->never())->method('envia');
        
        $encerrador = new EncerradorDeLeilao($daoFalso, $carteiro);
        $encerrador->encerra();
        
        $encerrados = $daoFalso->encerrados();
        
        $this->assertTrue($leilao1->isEncerrado());
        $this->assertTrue($leilao2->isEncerrado());
    }
    
    public function testDeveNaoDeveEncerrarLeiloesQueComecaramMenosDeUmaSemana() {
        $novo = (new \DateTime(date('y-m-d')))->sub(new \DateInterval('P1D'));
        
        $leilao1 = new LeilaoBuilder();
        $leilao1 = $leilao1->comDescricao("TV de plasma")
        ->naData($novo)
        ->cria();
        
        $leilao2 = new LeilaoBuilder();
        $leilao2 = $leilao2->comDescricao("Geladeira")
        ->naData($novo)
        ->cria();
        
        $carteiro = new Carteiro();
        
        $daoFalso = $this->createMock(LeilaoDao::class);
        $daoFalso->method('correntes')->will($this->returnValue(array($leilao1, $leilao2)));
        $daoFalso->expects($this->never())->method('atualiza');
        
        $encerrador = new EncerradorDeLeilao($daoFalso, $carteiro);
        $encerrador->encerra();
        
        $encerrados = $daoFalso->encerrados();
        
        $this->assertFalse($leilao1->isEncerrado());
        $this->assertFalse($leilao2->isEncerrado());
    }
    
    public function testEncerradorNaoDeveFazerNada() {
        $novo = (new \DateTime(date('y-m-d')))->sub(new \DateInterval('P1D'));
        
        $carteiro = new Carteiro();
        
        $daoFalso = $this->createMock(LeilaoDao::class);
        $daoFalso->method('correntes')->will($this->returnValue(array()));
        
        $encerrador = new EncerradorDeLeilao($daoFalso, $carteiro);
        $encerrador->encerra();
        
        $this->assertEquals(array(), $daoFalso->correntes());
    }
}

