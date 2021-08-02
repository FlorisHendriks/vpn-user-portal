<?php

declare(strict_types=1);

/*
 * eduVPN - End-user friendly VPN.
 *
 * Copyright: 2016-2021, The Commons Conservancy eduVPN Programme
 * SPDX-License-Identifier: AGPL-3.0+
 */

namespace LC\Portal\Tests\Federation;

use Exception;
use LC\Portal\Federation\ForeignKeyListFetcher;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ForeignKeyListFetcherTest extends TestCase
{
    public function testFetch(): void
    {
        $tmpDir = sprintf('%s/%s', sys_get_temp_dir(), bin2hex(random_bytes(10)));
        mkdir($tmpDir);
        $foreignKeyListFetcher = new ForeignKeyListFetcher($tmpDir);
        $foreignKeyListFetcher->update(
            new TestHttpClient(),
            'https://disco.eduvpn.org/v2/server_list.json',
            [
                'RWTzeZBS1e59OQtxV7UBpG/NfCpuAeOxQQqvqLqp1zVq4rGT5Fyq2gGN',
            ]
        );
        static::assertSame(
            [
                '07wQOlf0uFqs5PL7zkcnMY73HpH0_uP09l68pK1YgBI' => [
                    'public_key' => 'bRTz33KIuYo_w_-AbzNtdmLDqIm11_eGiHXQniojxY4',
                    'base_uri' => 'https://eduvpn.deic.dk/',
                ],
                'xGAxo6xS9R3CHXc_fYhzeYACoB1dTHCen1mXEd-vmhE' => [
                    'public_key' => 'O53DTgB956magGaWpVCKtdKIMYqywS3FMAC5fHXdFNg',
                    'base_uri' => 'https://nl.eduvpn.org/',
                ],
            ],
            $foreignKeyListFetcher->extract()
        );
    }

    public function testFetchRollback(): void
    {
        try {
            $tmpDir = sprintf('%s/%s', sys_get_temp_dir(), bin2hex(random_bytes(16)));
            mkdir($tmpDir);
            // copy the v=5 file to the tmpDir
            copy(__DIR__.'/data/server_list.json', $tmpDir.'/server_list.json');
            $foreignKeyListFetcher = new ForeignKeyListFetcher($tmpDir);
            $foreignKeyListFetcher->update(
                new TestHttpClient(),
                'https://disco.eduvpn.org/v2/server_list_rollback.json',
                [
                    'RWTzeZBS1e59OQtxV7UBpG/NfCpuAeOxQQqvqLqp1zVq4rGT5Fyq2gGN',
                ]
            );
            static::fail();
        } catch (Exception $e) {
            static::assertSame('rollback to older version of file not allowed, we have "5", we got "4"', $e->getMessage());
        }
    }
}
