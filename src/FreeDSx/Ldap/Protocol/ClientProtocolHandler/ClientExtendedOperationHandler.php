<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Protocol\ClientProtocolHandler;

use FreeDSx\Ldap\Operation\Request\ExtendedRequest;
use FreeDSx\Ldap\Protocol\Factory\ExtendedResponseFactory;
use FreeDSx\Ldap\Protocol\LdapMessageRequest;
use FreeDSx\Ldap\Protocol\LdapMessageResponse;
use FreeDSx\Ldap\Protocol\LdapQueue;

/**
 * Logic for handling extended operations.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ClientExtendedOperationHandler extends ClientBasicHandler
{
    /**
     * @var ExtendedResponseFactory
     */
    protected $extendedResponseFactory;

    public function __construct(ExtendedResponseFactory $extendedResponseFactory = null)
    {
        $this->extendedResponseFactory = $extendedResponseFactory ?? new ExtendedResponseFactory();
    }

    /**
     * {@inheritDoc}
     */
    public function handleRequest(LdapMessageRequest $message, LdapQueue $queue, array $options): ?LdapMessageResponse
    {
        $messageFrom = parent::handleRequest($message, $queue, $options);

        /** @var ExtendedRequest $request */
        $request = $message->getRequest();
        if (!$this->extendedResponseFactory->has($request->getName())) {
            return $messageFrom;
        }

        $response = $this->extendedResponseFactory->get(
            $messageFrom->getResponse()->toAsn1(),
            $request->getName()
        );
        $prop = (new \ReflectionClass(LdapMessageResponse::class))->getProperty('response');
        $prop->setAccessible(true);
        $prop->setValue($messageFrom, $response);

        return $messageFrom;
    }
}
