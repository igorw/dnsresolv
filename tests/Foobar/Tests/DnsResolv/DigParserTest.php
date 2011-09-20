<?php

namespace Foobar\Tests\DnsResolv;

use Foobar\DnsResolv\Resolver\DigParser;
use Foobar\DnsResolv\Resolver\Result;
use Foobar\DnsResolv\Resolver\Resource;

class DigParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideParse
     */
    public function testParse($input, $expected)
    {
        $parser = new DigParser();
        $this->assertEquals($expected, $parser->parse($input));
    }

    public function provideParse()
    {
        return array(
            array(
'
; <<>> DiG 9.7.3 <<>> @rdns01.nexcess.net igorweso.me A
; (1 server found)
;; global options: +cmd
;; Got answer:
;; ->>HEADER<<- opcode: QUERY, status: NOERROR, id: 38513
;; flags: qr rd ra; QUERY: 1, ANSWER: 1, AUTHORITY: 0, ADDITIONAL: 0

;; QUESTION SECTION:
;igorweso.me.           IN  A

;; ANSWER SECTION:
igorweso.me.        3600    IN  A   178.79.169.131

;; Query time: 532 msec
;; SERVER: 208.69.120.201#53(208.69.120.201)
;; WHEN: Sun Sep 18 02:27:43 2011
;; MSG SIZE  rcvd: 45
',
                new Result(
                    array(
                        new Resource('igorweso.me.', null, 'IN', 'A')
                    ),
                    array(
                        new Resource('igorweso.me.', 3600, 'IN', 'A', '178.79.169.131')
                    ),
                    null,
                    null,
                    '532 msec'
                ),
            ),
            array(
'
; <<>> DiG 9.7.3 <<>> @rdns02.nexcess.net wiedler.ch MX
; (1 server found)
;; global options: +cmd
;; Got answer:
;; ->>HEADER<<- opcode: QUERY, status: NOERROR, id: 24046
;; flags: qr rd ra; QUERY: 1, ANSWER: 2, AUTHORITY: 0, ADDITIONAL: 0

;; QUESTION SECTION:
;wiedler.ch.            IN  MX

;; ANSWER SECTION:
wiedler.ch.     300 IN  MX  10 mx2.mail.hostpoint.ch.
wiedler.ch.     300 IN  MX  10 mx1.mail.hostpoint.ch.

;; Query time: 617 msec
;; SERVER: 207.32.191.59#53(207.32.191.59)
;; WHEN: Sat Sep 17 19:47:02 2011
;; MSG SIZE  rcvd: 83
',
                new Result(
                    array(
                        new Resource('wiedler.ch.', null, 'IN', 'MX')
                    ),
                    array(
                        new Resource('wiedler.ch.', 300, 'IN', 'MX', '10 mx2.mail.hostpoint.ch.'),
                        new Resource('wiedler.ch.', 300, 'IN', 'MX', '10 mx1.mail.hostpoint.ch.'),
                    ),
                    null,
                    null,
                    '617 msec'
                ),
            ),
        );
    }
}
