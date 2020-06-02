<?php

namespace JJeanniard\InfoServer;

class JJeanniardInfoServer
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