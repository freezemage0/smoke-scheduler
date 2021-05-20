<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Authorization;

class TokenFactory {
    public function create(int $uid): Token {
        $since = time();
        $value = md5($since + $uid);

        return new Token($uid, $value, $since);
    }
}