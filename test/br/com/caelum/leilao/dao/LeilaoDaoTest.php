<?php
namespace test\br\com\caelum\leilao\dao;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\Factory\ConnectionFactory;
use src\br\com\caelum\leilao\dao\LeilaoDao;
use src\br\com\caelum\leilao\dao\UsuarioDao;
use test\br\com\caelum\leilao\dominio\LeilaoBuilder;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;

class LeilaoDaoTest extends TestCase
{
    private $dao;
    private $con;
    private $usuarioDao;
    
    public function setUp() {
        $this->con = ConnectionFactory::getConnection();
        $this->dao = new LeilaoDao($this->con);
        $this->con->beginTransaction();
        $this->usuarioDao = new UsuarioDao($this->con);
    }
    
    public function antes() {
        $dono = new Usuario('Satiro');
        
        $leilao = new LeilaoBuilder();
        $leilao = $leilao->comDescricao('iPhone')
        ->comValorInicial(150.0)
        ->comDataAbertura((new \DateTime(date('y-m-d')))->sub(new \DateInterval('P5D')))
        ->comDono($dono)
        ->usado(false)
        ->cria();
        
        $this->usuarioDao->salvar($dono);
        
        $this->dao->salvar($leilao);
        
        $leilao = new LeilaoBuilder();
        $leilao = $leilao->comDescricao('iPhone')
        ->comValorInicial(150.0)
        ->comDataAbertura(new \DateTime())
        ->comDono($dono)
        ->usado(true)
        ->cria();
        
        $leilao->setEncerrado(true);
        
        $this->dao->salvar($leilao);
    }
    
    public function testDeveGravarLeilao() {
        $dono = new Usuario('Satiro');
        $usuario = new Usuario('Andre');
        
        $leilao = new LeilaoBuilder();
        $leilao = $leilao->comDescricao('iPhone')
        ->comValorInicial(150.0)
        ->comDataAbertura(new \DateTime())
        ->comDono($dono)
        ->usado(true)
        ->comLance(new Lance($usuario, 160.0, new Leilao()))
        ->cria();
        
        $this->usuarioDao->salvar($dono);
        $this->usuarioDao->salvar($usuario);
        
        $deuCerto = $this->dao->salvar($leilao);
        
        $this->assertTrue($deuCerto);
    }
    
    public function testDeveRetornarTotalDeUmNaoEncerrado()
    {
        $this->antes();
        $this->assertEquals(1, $this->dao->total());
    }
    
    public function testDeveRetornarTotalDeZeroNaoEncerrado() {
        $dono = new Usuario('Satiro');
        
        $leilao = new LeilaoBuilder();
        $leilao = $leilao->comDescricao('iPhone')
        ->comValorInicial(150.0)
        ->comDataAbertura(new \DateTime())
        ->comDono($dono)
        ->usado(true)
        ->cria();
        
        $leilao->setEncerrado(true);
        
        $this->usuarioDao->salvar($dono);
        
        $this->dao->salvar($leilao);
        
        $leilao = new LeilaoBuilder();
        $leilao = $leilao->comDescricao('iPhone')
        ->comValorInicial(150.0)
        ->comDataAbertura(new \DateTime())
        ->comDono($dono)
        ->usado(true)
        ->cria();
        
        $leilao->setEncerrado(true);
        
        $this->dao->salvar($leilao);
        
        $this->assertEquals(0, $this->dao->total());
    }
    
    public function testDeveRetornarTotalDeUmNovo()
    {
        $this->antes();
        $this->assertEquals(1, count($this->dao->novos()));
    }
    
    public function testDeveRetornarTotalDeUmAntigo() 
    {
        $this->antes();
        $dono = new Usuario('Satiro');
        
        $leilao = new LeilaoBuilder();
        $leilao = $leilao->comDescricao('iPhone')
        ->comValorInicial(150.0)
        ->comDataAbertura((new \DateTime(date('y-m-d')))->sub(new \DateInterval('P8D')))
        ->comDono($dono)
        ->usado(true)
        ->cria();
        
        $leilao->setEncerrado(true);
        
        $this->usuarioDao->salvar($dono);
        
        $this->dao->salvar($leilao);
        
        $this->assertEquals(1, count($this->dao->antigos()));
    }
    
    public function testDeveRetornarTotalDeUmNAntigo()
    {
        $this->antes();
        $dono = new Usuario('Satiro');
        
        $leilao = new LeilaoBuilder();
        $leilao = $leilao->comDescricao('iPhone')
        ->comValorInicial(150.0)
        ->comDataAbertura((new \DateTime(date('y-m-d')))->sub(new \DateInterval('P7D')))
        ->comDono($dono)
        ->usado(true)
        ->cria();
        
        $leilao->setEncerrado(true);
        
        $this->usuarioDao->salvar($dono);
        
        $this->dao->salvar($leilao);
        
        $this->assertEquals(1, count($this->dao->antigos()));
    }
    
    public function testDeveAparecerNaoEncerradoComDataNoIntervalo()
    {
        $this->antes();
        $dataInicio = (new \DateTime(date('y-m-d')))->sub(new \DateInterval('P6D'));
        $dataFim = (new \DateTime(date('y-m-d')))->sub(new \DateInterval('P2D'));
        $total = count($this->dao->porPeriodo($dataInicio, $dataFim));
        $this->assertEquals(1, $total);
    }
    
    public function testNaoDeveAparecerEncerradosDentroDoIntervalo() {
        $dono = new Usuario('Satiro');
        
        $leilao = new LeilaoBuilder();
        $leilao = $leilao->comDescricao('iPhone')
        ->comValorInicial(150.0)
        ->comDataAbertura((new \DateTime(date('y-m-d')))->sub(new \DateInterval('P5D')))
        ->comDono($dono)
        ->usado(true)
        ->cria();
        
        $leilao->setEncerrado(true);
        
        $this->usuarioDao->salvar($dono);
        
        $this->dao->salvar($leilao);
        
        $dataInicio = (new \DateTime(date('y-m-d')))->sub(new \DateInterval('P6D'));
        $dataFim = (new \DateTime(date('y-m-d')))->sub(new \DateInterval('P2D'));
        $total = count($this->dao->porPeriodo($dataInicio, $dataFim));
        $this->assertEquals(0, $total);
    }
    
    public function tearDown() {
        $this->con->rollBack();
    }
}

