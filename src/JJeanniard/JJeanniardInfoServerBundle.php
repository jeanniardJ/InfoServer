<?php

namespace App\JJeanniard\InfoServerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JJeanniardInfoServerBundle extends Bundle
{
    private $ssh;

    public function __construct($_ssh)
    {
        $this->ssh = $_ssh;
    }

    /**
     * Retourne les informations sytème d'un serveur sous linux
     *
     *
     */
    public function info(): ?String
    {
        $values = $this->ssh("hostname");

        return $values;
    }
}