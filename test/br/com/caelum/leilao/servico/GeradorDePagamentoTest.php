<?php
namespace test\br\com\caelum\leilao\servico;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\interfaces\RepositorioDePagamentos;
use src\br\com\caelum\leilao\interfaces\RepositorioDeLeiloes;
use src\br\com\caelum\leilao\servico\Avaliador;
use src\br\com\caelum\leilao\servico\GeradorDePagamento;
use test\br\com\caelum\leilao\dominio\LeilaoBuilder;
use src\br\com\caelum\leilao\interfaces\Relogio;

class GeradorDePagamentoTest extends TestCase
{
    public function testDeveGerarPagamentoEmDiaUtil() {
        $pagamentos = $this->createMock(RepositorioDePagamentos::class);
        $leiloes = $this->createMock(RepositorioDeLeiloes::class);
        $avaliador = $this->createMock(Avaliador::class);
        $relogio = $this->createMock(Relogio::class);
        
        $gerador = new GeradorDePagamento($leiloes, $pagamentos, $avaliador, $relogio);
        
        $leilao1 = (new LeilaoBuilder())->comDescricao('Playstation 3')
        ->naData(new \DateTime('2017-12-02'))
        ->cria();
        
        $leilao2 = (new LeilaoBuilder())->comDescricao('Playstation 4')
        ->naData(new \DateTime('2017-12-03'))
        ->cria();
        
        $leiloes->method('encerrados')->will($this->returnValue(array($leilao1, $leilao2)));
        $avaliador->method('getMaiorLance')->willReturn(5.0);
        $relogio->method('hoje')->willReturn(new \DateTime('2017-12-02'));
        
        $novosPagamentos = $gerador->gera();
        
        $this->assertEquals(new \DateTime('2017-12-04'), $novosPagamentos[0]->getData());
        $this->assertEquals(new \DateTime('2017-12-04'), $novosPagamentos[1]->getData());
    }
}

