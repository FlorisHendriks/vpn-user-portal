<?php

declare(strict_types=1);

/*
 * eduVPN - End-user friendly VPN.
 *
 * Copyright: 2016-2021, The Commons Conservancy eduVPN Programme
 * SPDX-License-Identifier: AGPL-3.0+
 */

namespace LC\Portal\Http;

use LC\Portal\Http\Exception\HttpException;

/**
 * This hook is used to check if a user is allowed to access the portal/API.
 */
class AccessHook extends AbstractHook implements BeforeHookInterface
{
    /** @var array<string> */
    private array $accessPermissionList;

    /**
     * @param array<string> $accessPermissionList
     */
    public function __construct(array $accessPermissionList)
    {
        $this->accessPermissionList = $accessPermissionList;
    }

    public function afterAuth(UserInfoInterface $userInfo, Request $request): ?Response
    {
        if (!$this->hasPermissions($userInfo->getPermissionList())) {
            throw new HttpException('account is not allowed to access this service', 403);
        }

        return null;
    }

    /**
     * @param array<string> $userPermissionList
     */
    private function hasPermissions(array $userPermissionList): bool
    {
        foreach ($userPermissionList as $userPermission) {
            if (\in_array($userPermission, $this->accessPermissionList, true)) {
                return true;
            }
        }

        return false;
    }
}