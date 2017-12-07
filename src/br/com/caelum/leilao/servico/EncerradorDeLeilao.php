<?php
namespace src\br\com\caelum\leilao\servico;

use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dao\LeilaoDao;
use src\br\com\caelum\leilao\interfaces\RepositorioDeLeiloes;
use src\br\com\caelum\leilao\interfaces\EnviadorDeEmail;

class EncerradorDeLeilao
{
    private $total = 0;
    private $dao;
    private $carteiro;
    
    public function __construct(RepositorioDeLeiloes $dao, EnviadorDeEmail $carteiro)
    {
        $this->dao = $dao;
        $this->carteiro = $carteiro;
    }
    
    public function encerra()
    {
        $todosLeiloesCorrentes = $this->dao->correntes();
        
        foreach ($todosLeiloesCorrentes as $leilao) {
            try {
                if ($this->comecouSemanaPassada($leilao)) {
                    $leilao->encerra();
                    $this->total++;
                    $this->dao->atualiza($leilao);
                    $this->carteiro->envia($leilao);
                }
            } catch (\PDOException $e) {
                // logge
            }            
        }
    }
    
    private function comecouSemanaPassada(Leilao $leilao) 
    {
        return $this->diasEntre($leilao->getData(), new \DateTime(date('y-m-d'))) >= 7;
    }
    
    private function diasEntre(\DateTime $inicio, \DateTime $hoje) 
    {
        $dataDoLeilao = clone $inicio;
        $diasNoIntervalo = 0;
        
        while ($dataDoLeilao < $hoje) {
            $dataDoLeilao->add(new \DateInterval('P1D'));
            $diasNoIntervalo++;
        }
        
        return $diasNoIntervalo;
    }
    
    public function getTotalEncerrados() {
        return $this->total;
    }
}

