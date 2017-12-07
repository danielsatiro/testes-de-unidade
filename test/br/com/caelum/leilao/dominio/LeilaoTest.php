<?php
namespace test\br\com\caelum\leilao\dominio;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Usuario;

class LeilaoTest extends TestCase
{
    public function testComUmLance()
    {
        $leilao = new Leilao("Macbook Pro 15");
        $this->assertEquals(0, count($leilao->getLances()));
        
        $leilao->propoe(new Lance(new Usuario("Steve Jobs"), 2000));
        
        $this->assertEquals(1, count($leilao->getLances()));
        $this->assertEquals(2000, $leilao->getLances()[0]->getValor(), 0.0001);
    }
    
    public function testNaoDeveAceitarMaisDe5LancesPorUsuario()
    {
        $steveJobs = new Usuario("Steve Jobs");
        $billGates = new Usuario("Bill Gates");
        
        try {            
            $leilaoBuilder = new LeilaoBuilder();
            $leilao = $leilaoBuilder->comDescricao("Macboock Pro 15")
            ->comLance($steveJobs, 2000)
            ->comLance($billGates, 3000)
            ->comLance($steveJobs, 4000)
            ->comLance($billGates, 5000)
            ->comLance($steveJobs, 6000)
            ->comLance($billGates, 7000)
            ->comLance($steveJobs, 8000)
            ->comLance($billGates, 9000)
            ->comLance($steveJobs, 10000)
            ->comLance($billGates, 11000)
            ->comLance($steveJobs, 12000)
            ->cria();
            
            $this->fail('falhou');
        } catch (\RuntimeException $e) {
            $this->assertTrue(true);
        }
        
        /* $this->assertEquals(10, count($leilao->getLances()));
        $ultimo = count($leilao->getLances()) - 1;
        $ultimoLance = $leilao->getLances()[$ultimo];
        $this->assertEquals(11000, $ultimoLance->getValor(), 0.0001); */
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testNaoDeveAceitarDoisLancesSeguidosDoMesmoUsuario()
    {
        $steveJobs = new Usuario("Steve Jobs");
        $billGates = new Usuario("Bill Gates");
        
        $leilaoBuilder = new LeilaoBuilder();
        $leilao = $leilaoBuilder->comDescricao("Macboock Pro 15")
        ->comLance($steveJobs, 2000)
        ->comLance($billGates, 1000)
        ->cria();
        
        $this->assertEquals(1, count($leilao->getLances()));
        $this->assertEquals(2000, $leilao->getLances()[0]->getValor(), 0.0001);
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function testNaoDeveAceitarLancesMenorQueAnterior()
    {
        $steveJobs = new Usuario("Steve Jobs");
        $billGates = new Usuario("Bill Gates");
        
        $leilaoBuilder = new LeilaoBuilder();
        $leilao = $leilaoBuilder->comDescricao("Macboock Pro 15")
        ->comLance($steveJobs, 2000)
        ->comLance($billGates, 1000)
        ->cria();
        
        $this->assertEquals(1, count($leilao->getLances()));
        $this->assertEquals(2000, $leilao->getLances()[0]->getValor(), 0.0001);
    }
}

