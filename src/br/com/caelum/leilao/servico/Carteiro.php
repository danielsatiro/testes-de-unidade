<?php
namespace src\br\com\caelum\leilao\servico;

use src\br\com\caelum\leilao\interfaces\EnviadorDeEmail;
use src\br\com\caelum\leilao\dominio\Leilao;

class Carteiro implements EnviadorDeEmail
{

    public function envia(Leilao $leilao)
    {
        /* Envia email*/
    }
}

