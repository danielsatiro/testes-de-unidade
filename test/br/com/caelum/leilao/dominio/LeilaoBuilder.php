<?php
namespace test\br\com\caelum\leilao\dominio;

use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\dominio\Leilao;
use phpDocumentor\Reflection\Types\Boolean;

class LeilaoBuilder
{
    private $leilao;
    
    public function comLance(Lance $lance) {
        $this->leilao->propoe($lance);
        return $this;
    }
    
    public function comDescricao($descricao) {
        $this->leilao = new Leilao($descricao);
        return $this;
    }
    
    public function comNome($descricao) {
        $this->leilao = new Leilao($descricao);
        return $this;
    }
    
    public function naData(\DateTime $data) {
        $this->leilao->setDataAbertura($data);
        return $this;
    }
    
    public function comDataAbertura(\DateTime $data) {
        $this->leilao->setDataAbertura($data);
        return $this;
    }
    
    public function comValorInicial(float $valor) {
        $this->leilao->setValorInicial($valor);
        return $this;
    }
    
    public function comDono(Usuario $dono) {
        $this->leilao->setDono($dono);
        return $this;
    }
    
    public function usado(bool $usado) {
        $this->leilao->setUsado($usado);
        return $this;
    }
    
    public function cria() {
        return $this->leilao;
    }
}

