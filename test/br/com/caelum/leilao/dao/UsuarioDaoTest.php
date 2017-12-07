<?php
namespace test\br\com\caelum\leilao\dao;

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dao\UsuarioDao;
use src\br\com\caelum\leilao\Factory\ConnectionFactory;
use src\br\com\caelum\leilao\dominio\Usuario;

class UsuarioDaoTest extends TestCase
{
    private $dao;
    private $con;
    private $usuario;
    
    public function setUp() {
        $this->con = ConnectionFactory::getConnection();
        $this->con->beginTransaction();
        $this->dao = new UsuarioDao($this->con);
    }
    
    public function antes()
    {
        $this->usuario = new Usuario('João da Silva', 'joao@dasilva.com');
        
        $this->dao->salvar($this->usuario);
    }
    
    public function testDeveRetornarUsuarioPorNomoEEmail()
    {
        $this->antes();
        
        $usuarioDoBanco = $this->dao->porNomeEEmail($this->usuario->getNome(), $this->usuario->getEmail());
        
        $this->assertEquals($this->usuario->getNome(), $usuarioDoBanco->getNome());
    }
    
    public function testDeveRetornarFalseParaUsuarioNaoExistente()
    {
        $usuario = new Usuario('João da Silva', 'joao@dasilva.com');
        
        $usuarioDoBanco = $this->dao->porNomeEEmail($usuario->getNome(), $usuario->getEmail());
        
        $this->assertFalse($usuarioDoBanco);
    }
    
    public function testDeveAtualizarUsuario()
    {
        $this->antes();
        #18343457 n de reserva
        #0015925040405 bilhete eletronico
        
        $usuarioAntigo = clone $this->usuario;
        
        $this->usuario->setNome('Jose da Silva');
        $this->usuario->setEmail('jose@dasilva.com');
        
        $this->dao->atualizar($this->usuario);
        
        $usuarioDoBancoAntigo = $this->dao->porNomeEEmail($usuarioAntigo->getNome(), $usuarioAntigo->getEmail());
        $usuarioDoBanco = $this->dao->porNomeEEmail($this->usuario->getNome(), $this->usuario->getEmail());
        
        $this->assertFalse($usuarioDoBancoAntigo);
        $this->assertEquals($this->usuario->getNome(), $usuarioDoBanco->getNome());
    }
    
    public function testDeveDeletarUsuario()
    {
        $this->antes();
        
        $this->dao->deletar($this->usuario);
    }
}

