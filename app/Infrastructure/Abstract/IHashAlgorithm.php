<?php

interface IHashAlgorithm
{
    function getHash(string $str);
}