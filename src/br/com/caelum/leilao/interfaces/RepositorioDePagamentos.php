<?php
namespace src\br\com\caelum\leilao\interfaces;

use src\br\com\caelum\leilao\dominio\Pagamento;

interface RepositorioDePagamentos
{
    public function salva(Pagamento $pagamento);
    
    public function salvaTodos(array $pagamentos);
}

