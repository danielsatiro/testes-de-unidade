<?php
namespace src\br\com\caelum\leilao\interfaces;

use src\br\com\caelum\leilao\dominio\Leilao;

interface EnviadorDeEmail
{
    public function envia(Leilao $leilao);
}

