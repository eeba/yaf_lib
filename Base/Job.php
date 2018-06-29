<?php
namespace Base\Jobs;

abstract class Job {
    abstract public function action($argv=array());
}