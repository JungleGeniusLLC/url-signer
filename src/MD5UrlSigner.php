<?php

namespace Spatie\UrlSigner;

class MD5UrlSigner extends BaseUrlSigner
{
    /**
     * Generate a token to identify the secure action.
     *
     * @param \League\Url\UrlImmutable|string $url
     * @param string                          $expiration
     *
     * @return string
     */
    protected function createSignature($url, $expiration, $useKeyValue = false, $usePartialUrl = false)
    {
        $full_url = (string) $url;
        $parsed_url = parse_url($full_url);
        $url_path_and_query = '';
        $url_path_and_query .= isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $url_path_and_query .= isset($parsed_url['query']) ? '?'.$parsed_url['query'] : '';
        $query_separator = isset($parsed_url['query']) ? '&' : '?';
        $url_anchor = isset($parsed_url['fragment']) ? '#'.$parsed_url['fragment'] : '';
        if ($useKeyValue) {
            if ($usePartialUrl) {
                return md5("{$url_path_and_query}{$query_separator}{$this->expiresParameter}={$expiration}&{$this->signatureParameter}={$this->signatureKey}{$url_anchor}");
            } else {
                return md5("{$full_url}{$query_separator}{$this->expiresParameter}={$expiration}&{$this->signatureParameter}={$this->signatureKey}");
            }
        } else {
            if ($usePartialUrl) {
                return md5("{$url_path_and_query}{$url_anchor}::{$expiration}::{$this->signatureKey}");
            } else {
                return md5("{$full_url}::{$expiration}::{$this->signatureKey}");
            }
        }
    }
}
