<?php

declare(strict_types=1);

/*
 * eduVPN - End-user friendly VPN.
 *
 * Copyright: 2016-2021, The Commons Conservancy eduVPN Programme
 * SPDX-License-Identifier: AGPL-3.0+
 */

namespace LC\Portal\Tests;

use LC\Portal\Config;
use LC\Portal\Http\Request;
use LC\Portal\ShibAuthentication;
use PHPUnit\Framework\TestCase;

class ShibAuthenticationHookTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new Config(
            [
                'userIdAttribute' => 'persistent-id',
            ]
        );
        $authHook = new ShibAuthentication($config);
        $userInfo = $authHook->executeBefore(
            new Request(
                [
                    'persistent-id' => 'https://idp.example.org/saml!https://sp.example.org/saml!abcdef',
                ]
            ),
            []
        );
        $this->assertSame('https://idp.example.org/saml!https://sp.example.org/saml!abcdef', $userInfo->getUserId());
        $this->assertSame([], $userInfo->getPermissionList());
    }

    public function testPermissionList(): void
    {
        $config = new Config(
            [
                'userIdAttribute' => 'persistent-id',
                'permissionAttribute' => 'entitlement',
            ]
        );
        $authHook = new ShibAuthentication($config);
        $userInfo = $authHook->executeBefore(
            new Request(
                [
                    'persistent-id' => 'https://idp.example.org/saml!https://sp.example.org/saml!abcdef',
                    'entitlement' => 'a;b',
                ]
            ),
            []
        );
        $this->assertSame('https://idp.example.org/saml!https://sp.example.org/saml!abcdef', $userInfo->getUserId());
        $this->assertSame(['a', 'b'], $userInfo->getPermissionList());
    }
}
