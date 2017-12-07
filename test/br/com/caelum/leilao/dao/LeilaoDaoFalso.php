<?php
namespace test\br\com\caelum\leilao\dao;

use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\interfaces\RepositorioDeLeiloes;

class LeilaoDaoFalso implements RepositorioDeLeiloes
{
    private static $leiloes = array();
    
    public function salva(Leilao $leilao)
    {
        static::$leiloes[] = $leilao;
    }
    
    public function encerrados()
    {
        $filtrados = array();
        foreach (static::$leiloes as $leilao) {
            if ($leilao->isEncerrado()) {
                $filtrados[] = $leilao;
            }
        }
        return $filtrados;
    }
    
    public function correntes()
    {
        $filtrados = array();
        foreach (static::$leiloes as $leilao) {
            if (!$leilao->isEncerrado()) {
                $filtrados[] = $leilao;
            }
        }
        return $filtrados;
    }
    
    public function atualiza(Leilao $leilao)
    {
        /* nao faz nada! */
    }
}

