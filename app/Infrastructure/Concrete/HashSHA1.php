<?php

class HashSHA1 implements IHashAlgorithm
{
    function getHash(string $str)
    {
        return sha1($str);
    }
}