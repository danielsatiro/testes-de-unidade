<?php 

namespace src\br\com\caelum\leilao\servico;

use src\br\com\caelum\leilao\dominio\Leilao;

class Avaliador {
	private $menorValor = INF;
	private $maiorValor = -INF;
	private $valorMedio;
	private $maiores;
	
	public function avalia(Leilao $leilao)
	{
	    $lances = $leilao->getLances();
	    if(empty($lances)) {
	        throw new \RuntimeException("Nao e possivel avaliar um leilao sem lances");
	    }
    	    
	    foreach ($lances as $lance) {
		    if ($lance->getValor() > $this->maiorValor) {
		        $this->maiorValor = $lance->getValor();
		    }		    
		    if ($lance->getValor() < $this->menorValor) {
		        $this->menorValor = $lance->getValor();
		    }
		    
		    $this->valorMedio += $lance->getValor();
		}
		$this->valorMedio = $this->valorMedio/count($lances);
		$this->pegaOsMaioresNo($leilao);
	}
	
	private function pegaOsMaioresNo(Leilao $leilao)
	{
	    $this->maiores = $leilao->getLances();
	    
	    usort($this->maiores, function($o1, $o2){
	        if ($o1->getValor() < $o2->getValor()) return 1;
	        if ($o1->getValor() > $o2->getValor()) return -1;
	        return 0;
	    });
	    
        $this->maiores = array_slice($this->maiores, 0, 3);
	}
	
    /**
     * @return string
     */
    public function getMenorLance()
    {
        return $this->menorValor;
    }

    /**
     * @return number
     */
    public function getMaiorLance()
    {
        return $this->maiorValor;
    }
    
    /**
     * @return number
     */
    public function getValorMedio()
    {
        return $this->valorMedio;
    }
    
    /**
     * @return array
     */
    public function getMaiores()
    {
        return $this->maiores;
    }
	
}