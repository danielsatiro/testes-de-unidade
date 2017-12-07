<?php
namespace src\br\com\caelum\leilao\servico;

use src\br\com\caelum\leilao\interfaces\RepositorioDeLeiloes;
use src\br\com\caelum\leilao\interfaces\RepositorioDePagamentos;
use src\br\com\caelum\leilao\dominio\Pagamento;
use src\br\com\caelum\leilao\interfaces\Relogio;
use phpDocumentor\Reflection\Types\Null_;

class GeradorDePagamento
{
    private $pagamentos;
    private $leiloes;
    private $avaliador;
    private $relogio;
    
    public function __construct(RepositorioDeLeiloes $leiloes, RepositorioDePagamentos $pagamentos, Avaliador $avaliador, Relogio $relogio = null)
    {
        $this->leiloes = $leiloes;
        $this->pagamentos = $pagamentos;
        $this->avaliador = $avaliador;
        $this->relogio= $relogio;
    }
    
    public function gera()
    {
        $leiloesEncerrados = $this->leiloes->encerrados();
        $novosPagamentos = array();
        
        foreach ($leiloesEncerrados as $leilao) {
            $this->avaliador->avalia($leilao);
            $novoPagamento = new Pagamento($this->avaliador->getMaiorLance(), $this->primeiroDiaUltil());
            $novosPagamentos[] = $novoPagamento;
        }
        $this->pagamentos->salvaTodos($novosPagamentos);
        
        return $novosPagamentos;
    }
    
    private function primeiroDiaUltil()
    {
        $data = $this->relogio->hoje();
        $diaDaSemana = $data->format('w');
        
        if ($diaDaSemana == 6) {
            $data->add(new \DateInterval('P2D'));
        } elseif ($diaDaSemana == 0) {
            $data->add(new \DateInterval('P1D'));
        }
        
        return $data;
    }
    
    /**
     * @return \src\br\com\caelum\leilao\interfaces\RepositorioDePagamentos
     */
    public function getPagamentos()
    {
        return $this->pagamentos;
    }

}

