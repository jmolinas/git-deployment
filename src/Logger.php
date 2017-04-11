<?php

namespace JMolinas\GitDeployment;

class Logger
{
    protected $location;
    protected $log;
    const ERROR = " ERROR: ";
    const NOTIFY = " NOTIFY: ";

    public function __construct($logLocation)
    {
        $this->location = $logLocation;
        $this->log = date('m/d/Y h:i:s a');
    }

    public function error()
    {
        $this->log .= self::ERROR;
        return $this;
    }

    public function notify()
    {
        $this->log .= self::NOTIFY;
        return $this;
    }


    public function logger($message)
    {
        $this->log .= "$message \n";
        $this->savetoFile();
    }

    private function savetoFile()
    {
        file_put_contents($this->location . 'deploy.log', $this->log, FILE_APPEND);
    }
}
