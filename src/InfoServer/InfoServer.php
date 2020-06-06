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
 * @package JJeanniard
 * @author JJeanniard
 * @version 1.0.0-alpha
 * @license MIT
 */
class InfoServer
{
    private $ssh;

    public function __construct(SSH2 $_ssh)
    {
        $this->ssh = $_ssh;
    }

    /**
     * Retourne les informations de base
     *  Le hostname
     *  L'os
     *  La date
     *  Le kernel
     *  L'arch
     * @return array
     */
    public function getSystem(): array
    {
        $this->syteme = [
            'hostname' => trim($this->ssh->exec("hostname")),
            'os' => trim($this->ssh->exec("uname -o")),
            'date' => trim($this->ssh->exec("date")),
            'kernel' => trim($this->ssh->exec("uname -r")),
            'arch' => trim($this->ssh->exec("uname -m"))
        ];
        return $this->syteme;
    }

    /**
     * return news cpu
     *  Model
     *  Cores
     *  Usage
     * @return array
     */
    public function getCpu(): array
    {
        $user = trim($this->ssh->exec("id -un"));
        $usageCpu = intval(trim($this->ssh->exec("ps -A -u ". $user ." -o pcpu | tail -n +2 | awk '{ usage += $1 } END { print usage }'")));
        $nbrCores = intval(trim($this->ssh->exec("nproc")));

        $this->cpu = [
            'model' => trim($this->ssh->exec("cat /proc/cpuinfo | grep 'model name' | awk -F ':' '{print $2}' | head -n 1")),
            'cores' => $nbrCores,
            'usage' => round(($usageCpu/$nbrCores),2)
        ];
        return $this->cpu;
    }

    /**
     * return news ram
     *  Used
     *  Free
     *  Total
     *  Usage
     * @return array
     */
    public function getRam(): array
    {
        $ramUsed = intval(trim($this->ssh->exec("free -b | grep 'buffers/cache' | awk -F ':' '{print $2}' | awk '{print $1}'")));
        $ramFree = intval(trim($this->ssh->exec("free -b | grep 'buffers/cache' | awk -F ':' '{print $2}' | awk '{print $2}'")));
        $ramTotal = $ramUsed + $ramFree;

        $this->ram = [
            'used' => $ramUsed,
            'free' => $ramFree,
            'total' => $ramTotal,
            'usage' => $ramUsage = round((($ramUsed / $ramTotal) * 100), 2),
        ];

        return $this->ram;
    }

    /**
     * return array swap
     *  Used
     *  Free
     *  Total
     *  Usage
     * @return array
     */
    public function getSwap(): array
    {
        $swapUsage = 0;
        $swapUsed = intval(trim($this->ssh->exec("free -b | grep 'Swap' | awk -F ':' '{print $2}' | awk '{print $2}'")));
        $swapFree = intval(trim($this->ssh->exec("free -b | grep 'Swap' | awk -F ':' '{print $2}' | awk '{print $3}'")));
        $swapTotal = $swapUsed + $swapFree;

        $this->swap = [
          'used' => $swapUsed,
          'free' => $swapFree,
          'total' => $swapTotal,
          'usage' => $swapTotal!=0?round((($swapUsed / $swapTotal) * 100), 2) : $swapUsage
        ];
        return $this->swap;
    }

    /**
     * return Load average
     * @return array
     */
    public function getLoadAverage(): array
    {
        $loadavg = trim($this->ssh->exec("top -b -n 1 | grep 'load average' | awk -F ',' '{print $5}'"));
        return $this->loadaverage = ['load average' => $loadavg];
    }

    /**
     * return rx & tx
     * @return array
     */
    public function getReseau(): array
    {
        $interface[] = trim($this->ssh->exec("ip link | grep 'state UP' | awk -F ':' '{ print $2 }' | awk '{ print $1 }'"));
        for($i = 0; $i < count($interface); $i++){
            $this->reseau = [ $interface[$i] => [
                'upload' => intval(trim($this->ssh->exec('cat /sys/class/net/'.$interface[$i].'/statistics/tx_bytes'))),
                'download' => intval(trim($this->ssh->exec('cat /sys/class/net/'.$interface[$i].'/statistics/rx_bytes')))
            ]];
        }
        return $this->reseau;
    }

    /**
     * return array disk
     *  Used
     *  Free
     *  Total
     *  Usage
     * @return array
     */
    public function getDisk(): array
    {
        $this->disk = [
            'used' => (intval(trim($this->ssh->exec("df -P / | tail -n +2 | head -n 1 | awk '{print $3}'"))) * 1024),
            'free' => (intval(trim($this->ssh->exec("df -P / | tail -n +2 | head -n 1 | awk '{print $4}'"))) * 1024),
            'total' => (intval(trim($this->ssh->exec("df -P / | tail -n +2 | head -n 1 | awk '{print $2}'"))) * 1024),
            'usage' => intval(substr(trim($this->ssh->exec("df -P / | tail -n +2 | head -n 1 | awk '{print $5}'")), 0, -1))
        ];
        return $this->disk;
    }

    /**
     * return uptime seconde
     * @return array
     */
    function getUptime()
    {
        return $this->uptime = intval(trim($this->ssh->exec("cat /proc/uptime | awk '{print $1}'")));
    }
}