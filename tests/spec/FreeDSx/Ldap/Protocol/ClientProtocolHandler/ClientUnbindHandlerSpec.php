<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FreeDSx\Ldap\Protocol\ClientProtocolHandler;

use FreeDSx\Ldap\Operation\Request\UnbindRequest;
use FreeDSx\Ldap\Protocol\LdapMessageRequest;
use FreeDSx\Ldap\Protocol\LdapQueue;
use FreeDSx\Ldap\Protocol\ClientProtocolHandler\ClientUnbindHandler;
use FreeDSx\Ldap\Protocol\RequestHandlerInterface;
use PhpSpec\ObjectBehavior;

class ClientUnbindHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ClientUnbindHandler::class);
    }

    function it_should_implement_RequestHandlerInterface()
    {
        $this->shouldBeAnInstanceOf(RequestHandlerInterface::class);
    }

    function it_should_send_the_message_and_close_the_queue(LdapQueue $queue)
    {
        $unbind = new LdapMessageRequest(1, new UnbindRequest());
        $queue->sendMessage($unbind)->shouldBeCalledOnce();
        $queue->close()->shouldBeCalledOnce();

        $this->handleRequest($unbind, $queue, [])->shouldBeNull();
    }
}
