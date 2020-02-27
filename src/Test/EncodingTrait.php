<?php

namespace Ang3\Bundle\TestBundle\Test;

/**
 * @author Joanis ROUANET
 */
trait EncodingTrait
{
    /**
     * @return mixed
     */
    public function decodeJsonString(string $json, bool $asArray = false)
    {
        $data = json_decode($json, $asArray);

        $this->assertEquals(0, json_last_error(), sprintf('Defailed to decode JSON data - Error code: %d (message: %s)', json_last_error(), json_last_error_msg()));

        return $data;
    }
}
