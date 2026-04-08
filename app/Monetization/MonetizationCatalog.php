<?php

namespace App\Monetization;

use InvalidArgumentException;

class MonetizationCatalog
{
    /**
     * Canonical monetization definitions
     * These are HUMAN-CURATED.
     */
    protected static array $catalog = [

        'elasticsearch.audit' => [
            'type'        => 'consulting',
            'title'       => 'Elastic Cluster Health Audit',
            'cta'         => 'Prevent shard allocation failures before they happen',
            'provider'    => 'CLSystems',
            'url'         => 'https://clsystems.nl/en/services/elasticsearch-audit',
            'placement'   => 'after_remediation',
            'disclosure'  => 'Sponsored recommendation',
        ],

        'elasticsearch.hosting' => [
            'type'        => 'hosting',
            'title'       => 'Managed Elasticsearch Hosting',
            'cta'         => 'Run Elasticsearch without operational risk',
            'provider'    => 'Partner',
            'url'         => 'https://liquidweb.i3f2.net/e1367X',
            'placement'   => 'after_prevention',
            'disclosure'  => 'Affiliate link',
        ],
    ];

    /**
     * Retrieve a monetization block by key.
     */
    public static function for(string $key): ?array
    {
        return static::$catalog[$key] ?? null;
    }
}
