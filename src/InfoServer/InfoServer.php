<?php

namespace Jjeanniard;

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
class InfoServer
{
    private $ssh;
    private $values = [];

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
        $this->syteme = [
            'hostname' => trim($this->ssh->exec("hostname")),
            'os' => trim($this->ssh->exec("uname -o")),
            'date' => trim($this->ssh->exec("date")),
            'kernel' => trim($this->ssh->exec("uname -r")),
            'arch' => trim($this->ssh->exec("uname -m"))
        ];
        return $this->values += $this->syteme;
    }

    public function getCpu(){
        $this->user = trim($this->ssh->exec("id -un"));
        $this->usageCpu = intval(trim($this->ssh->exec("ps -A -u ". $this->user ." -o pcpu | tail -n +2 | awk '{ usage += $1 } END { print usage }'")));
        $this->nbrCores = intval(trim($this->ssh->exec("nproc")));
        $this->cpu = [
            'model' => trim($this->ssh->exec("cat /proc/cpuinfo | grep 'model name' | awk -F ':' '{print $2}' | head -n 1")),
            'cores' => $this->nbrCores,
            'usage' => round(($this->usageCpu/$this->nbrCores),2)
        ];
        return $this->values += $this->cpu;
    }

    /**
     * @return array
     */
    public function getRam(): array
    {
        return $this->values;
    }
}