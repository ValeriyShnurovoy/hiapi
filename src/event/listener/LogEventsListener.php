<?php

namespace hiapi\event\listener;

use League\Event\EventInterface;
use League\Event\ListenerInterface;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class LogEventsListener
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class LogEventsListener implements ListenerInterface
{
    /**
     * Handle an event.
     *
     * @param EventInterface $event
     *
     * @return void
     */
    public function handle(EventInterface $event)
    {
        file_put_contents(Yii::getAlias('@runtime/events.log'), $this->serializeEvent($event) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    /**
     * Check whether the listener is the given parameter.
     *
     * @param mixed $listener
     *
     * @return bool
     */
    public function isListener($listener)
    {
        return true;
    }

    private function serializeEvent(EventInterface $event)
    {
        if ($event instanceof \JsonSerializable) {
            return json_encode($event->jsonSerialize());
        }

        throw new InvalidConfigException('Do not know how to serialize events that does not implement \JsonSerializable interface.');
    }
}
