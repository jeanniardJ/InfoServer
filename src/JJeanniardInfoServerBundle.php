<?php

namespace Jjeanniard\InfoServer;

use phpseclib\Net\SSH2;
/**
 * Permet de récupérer des informations sur un serveur linux (Ubuntu 16.04 & 20.04),
 * à partir d'une connexion Ssh. Information retourné :
 * le hostname, l'os, la date du jour, kernel, l'arch,
 * des informations réseaux comme rx & tx, le type d'interface,
 * les infos de la ram "used, free, toto, usage", le swap, le load average,
 * les infos cpu "used, nombres de cores, le model" ....
 * Et tout ce si est retourné au maximum dans un tableau pour une utilisation de votre choix.
 * @package JJeanniard\InfoServer
 * @author JJeanniard
 * @version 0.0.0-alpha
 * @license MIT
 */
class JjeanniardInfoServer
{
    private $ssh;

    public function __construct(SSH2 $_ssh)
    {
        $this->ssh = $_ssh;
    }

    /**
     * Retourne les informations système
     *
     *
     */
    public function getSystem()
    {
        $values = [];
        $values[] = [
            ['hostname'] => trim($this->ssh("hostname")),
            ['os'] => trim($this->ssh("uname -o")),
            ['date'] => trim($this->ssh("date")),
            ['kernel'] => trim($this->ssh("uname -r")),
            ['arch'] => trim($this->ssh("uname -m"))
        ];
        return $values;
    }
}