<?php

declare(strict_types=1);

namespace Indgy\Profiler;

/**
 * This is the timer class, it tracks timers and their start/stop times
 *
 * @package Profiler
 * @author github.com/indgy
 */
class Timer
{
    /**
     * @var Array<String,Timer> - Container for the timers indexed by name
     */
    private $timers = [];


    /**
     * Starts a timer for $name
     *
     * @param String $name The name of the timer as shown in the output header
     * @param String $description An optional description for the timer
     * @param Float $time An optional starting timestamp
     * @return Timer
     */
    public function start(String $name, String $description = "", Float $time = null) : Timer
    {
        $this->timers[$name] = [
            'start' => (is_null($time)) ? microtime(true) : $time,
            'stop' => null,
            'desc' => $description,
        ];
        return $this;
    }
    /**
     * Stops the timer identified by $name by recording the end time
     *
     * @param String $name The name of the timer to stop
     * @param Float $time An optional value representing the stop time
     * @return Timer
     */
    public function stop(String $name, Float $time = null) : Timer
    {
        $this->timers[$name]['stop'] = (is_null($time)) ? microtime(true) : $time;
        return $this;
    }
    /**
     * Returns all the registered timers
     *
     * @return Array<String,Array>
     */
    public function getTimers() : Array
    {
        return $this->timers;
    }

}
