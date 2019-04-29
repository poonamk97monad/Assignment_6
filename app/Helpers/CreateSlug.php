<?php

namespace App\Helpers;

class CreateSlug
{
    /**
     * To get slug from title
     * @param $strTitle
     * @param $strDelimiter
     * @return string $strSlug
    */
    function get($strTitle, $strDelimiter = '-') {
        $strSlug = strtolower(trim(preg_replace('/[\s-]+/', $strDelimiter , preg_replace('/[^A-Za-z0-9-]+/', $strDelimiter , preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $strTitle))))), $strDelimiter ));
        return $strSlug;
    }
}
