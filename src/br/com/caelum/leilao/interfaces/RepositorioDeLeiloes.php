<?php
namespace src\br\com\caelum\leilao\interfaces;

use src\br\com\caelum\leilao\dominio\Leilao;

interface RepositorioDeLeiloes
{
    public function salva(Leilao $leilao);
    
    public function encerrados();
    
    public function correntes();
    
    public function atualiza(Leilao $leilao);
}

