<?php

namespace Base;

abstract class Job {
    abstract public function action($argv = []);
}