<?php

namespace Foobar\DnsResolv\Resolver;

/**
 * Dig output parser
 *
 * Based on PEAR Net_Dig.
 */
class DigParser
{
    /**
     * Parse the output from dig.
     */
    public function parse($raw)
    {
        $regex = '/' .
            '^;(.*?)' .
            ';; QUESTION SECTION\:(.*?)' .
            '(;; ANSWER SECTION\:(.*?))?' .
            '(;; AUTHORITY SECTION\:(.*?))?' .
            '(;; ADDITIONAL SECTION\:(.*?))?' .
            '(;;.*)' .
            '/ims';

        if (preg_match($regex, $raw, $matches)) {

            $result = new Result();

            /* Start parsing the data */

            /* query section */

            $line = trim(preg_replace('/^(;*)/', '', trim($matches[2])));
            list($host, $class, $type) = preg_split('/[\s]+/', $line, 3);
            $result->query[] = new Resource($host, null, $class, $type, null);


            /* answer section */

            $temp = trim($matches[4]);
            if ($temp) {
                $temp = explode("\n", $temp);
                if (count($temp)) {
                    foreach($temp as $line) {
                        $result->answer[] = $this->parseDigResource($line);
                    }
                }
            }


            /* authority section */

            $temp = trim($matches[6]);
            if ($temp) {
                $temp = explode("\n", $temp);
                if (count($temp)) {
                    foreach($temp as $line) {
                        $result->authority[] = $this->parseDigResource($line);
                    }
                }
            }


            /* additional section */

            $temp = trim($matches[8]);
            if ($temp) {
                $temp = explode("\n", $temp);
                if (count($temp)) {
                    foreach($temp as $line) {
                        $result->additional[] = $this->parseDigResource($line);
                    }
                }
            }

            /* footer */

            $temp = explode("\n", trim($matches[9]));
            if (preg_match('/query time: (.*?)$/i', $temp[0], $m)) {
                $result->queryTime = trim($m[1]);
            }

            /* done */

            return $result;

        }

        throw new \Exception("Can't parse raw data");
    }

    /**
     * Parses a resource record line
     *
     * @param string           $line    The line to parse
     *
     * @return obj Net_Dig_resource  $return   A Net_Dig_resource object
     *
     * @access private
     * @author Colin Viebrock <colin@easyDNS.com>
     * @since  PHP 4.0.5
     */
    private function parseDigResource($line)
    {
        /* trim and remove leading ;, if present */     

        $line = trim(preg_replace('/^(;*)/', '', trim($line)));

        if ($line) {
            list($host, $ttl, $class, $type, $data) = preg_split('/[\s]+/', $line, 5);
            return new Resource($host, $ttl, $class, $type, $data);
        }

        return null;
    }
}

