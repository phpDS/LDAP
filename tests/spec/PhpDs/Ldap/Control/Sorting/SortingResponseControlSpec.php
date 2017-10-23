<?php
/**
 * This file is part of the phpDS package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\PhpDs\Ldap\Control\Sorting;

use PhpDs\Ldap\Asn1\Asn1;
use PhpDs\Ldap\Asn1\Encoder\BerEncoder;
use PhpDs\Ldap\Control\Control;
use PhpDs\Ldap\Control\Sorting\SortingResponseControl;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SortingResponseControlSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(0, 'cn');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SortingResponseControl::class);
    }

    function it_should_get_the_result()
    {
        $this->getResult()->shouldBeEqualTo(0);
    }

    function it_should_get_the_attribute()
    {
        $this->getAttribute()->shouldBeEqualTo('cn');
    }

    function it_should_be_constructed_from_asn1()
    {
        $encoder = new BerEncoder();

        $this::fromAsn1(Asn1::sequence(
            Asn1::ldapOid(Control::OID_SORTING_RESPONSE),
            Asn1::boolean(false),
            Asn1::octetString($encoder->encode(Asn1::sequence(
                Asn1::enumerated(0),
                Asn1::octetString('cn')
            )))
        ))->setValue(null)->shouldBeLike(new SortingResponseControl(0, 'cn'));
    }
}