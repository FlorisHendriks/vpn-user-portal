<?php

/*
 * eduVPN - End-user friendly VPN.
 *
 * Copyright: 2016-2019, The Commons Conservancy eduVPN Programme
 * SPDX-License-Identifier: AGPL-3.0+
 */

namespace LC\Portal;

use LC\Common\Http\HtmlResponse;
use LC\Common\Http\RedirectResponse;
use LC\Common\Http\Request;
use LC\Common\Http\Service;
use LC\Common\Http\ServiceModuleInterface;
use LC\Common\HttpClient\HttpClientInterface;
use LC\Common\Json;
use LC\Common\TplInterface;

class WgModule implements ServiceModuleInterface
{
    /** @var \LC\Common\TplInterface */
    private $tpl;

    /** @var \LC\Common\HttpClient\HttpClientInterface */
    private $httpClient;

    public function __construct(TplInterface $tpl, HttpClientInterface $httpClient)
    {
        $this->tpl = $tpl;
        $this->httpClient = $httpClient;
    }

    /**
     * @return void
     */
    public function init(Service $service)
    {
        $service->get(
            '/wg',
            /**
             * @return \LC\Common\Http\Response
             */
            function (Request $request, array $hookData) {
                $httpResponse = $this->httpClient->get('http://localhost:8080/list', [], []);

                return new HtmlResponse(
                    $this->tpl->render(
                        'vpnPortalWgPeers',
                        [
                            'peerList' => Json::decode($httpResponse->getBody()),
                        ]
                    )
                );
            }
        );

        $service->post(
            '/wg_add_peer',
            /**
             * @return \LC\Common\Http\Response
             */
            function (Request $request, array $hookData) {
                $publicKey = $request->requirePostParameter('PublicKey');
                $ipFour = $request->requirePostParameter('IPv4').'/32';
                $ipSix = $request->requirePostParameter('IPv6').'/128';

                $rawPostData = implode('&', ['PublicKey='.urlencode($publicKey), 'AllowedIPs='.urlencode($ipFour), 'AllowedIPs='.urlencode($ipSix)]);

                // parse the form fields
                // XXX make sure content-type is correct
                $httpResponse = $this->httpClient->postRaw(
                    'http://localhost:8080/register',
                    [],
                    $rawPostData
                );

                var_dump($httpResponse);
                exit();

                return new RedirectResponse($request->getRootUri().'wg');
            }
        );
    }
}
