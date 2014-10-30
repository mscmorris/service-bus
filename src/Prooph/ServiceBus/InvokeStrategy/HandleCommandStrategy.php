<?php
/*
 * This file is part of the prooph/php-service-bus.
 * (c) Alexander Miertsch <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 09.03.14 - 21:41
 */

namespace Prooph\ServiceBus\InvokeStrategy;

use Prooph\ServiceBus\Command;
use Prooph\ServiceBus\Message\MessageNameProvider;

/**
 * Class HandleCommandStrategy
 *
 * @package Prooph\ServiceBus\InvokeStrategy
 * @author Alexander Miertsch <contact@prooph.de>
 */
class HandleCommandStrategy extends AbstractInvokeStrategy
{

    /**
     * @param mixed $aHandler
     * @param mixed $aCommandOrEvent
     * @return bool
     */
    public function canInvoke($aHandler, $aCommandOrEvent)
    {
        if (! $aCommandOrEvent instanceof Command) {
            return false;
        }

        $handleMethod = 'handle' . $this->determineCommandName($aCommandOrEvent);

        return method_exists($aHandler, $handleMethod);
    }

    /**
     * @param mixed $aHandler
     * @param mixed $aCommandOrEvent
     */
    public function invoke($aHandler, $aCommandOrEvent)
    {
        $handleMethod = 'handle' . $this->determineCommandName($aCommandOrEvent);

        $aHandler->{$handleMethod}($aCommandOrEvent);
    }

    /**
     * @param mixed $aCommand
     * @return string
     */
    protected function determineCommandName($aCommand)
    {
        $commandName = ($aCommand instanceof MessageNameProvider)? $aCommand->getMessageName() : get_class($aCommand);
        return join('', array_slice(explode('\\', $commandName), -1));
    }
}
 