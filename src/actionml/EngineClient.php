<?php

/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *    http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace actionml;

/**
 * Client for connecting to an Engine Instance
 *
 */
class EngineClient extends BaseClient
{
    private $engineId;

    /**
     * @param string $engineId Engine id
     * @param string $baseUrl Base URL to the Engine Instance. Default is localhost:8000.
     * @param int $timeout Timeout of the request in seconds. Use 0 to wait indefinitely
     *              Default is 0.
     * @param int $connectTimeout Number of seconds to wait while trying to connect to a server.
     *              Default is 5.
     */
    public function __construct(
        $engineId,
        $baseUrl = "http://localhost:9090",
        $timeout = 0,
        $connectTimeout = 5
    )
    {
        parent::__construct($baseUrl, $timeout, $connectTimeout);
        $this->engineId = $engineId;
    }


    /**
     * Get Reports Engine status
     *
     * @return array status report
     */
    public function getEngineStatus()
    {
        return \GuzzleHttp\json_decode($this->client->get('/engines/' . $this->engineId)->getBody(), true);
    }

    /**
     * Send prediction query to an Engine Instance
     *
     * @param array Query
     *
     * @return array JSON response
     *
     * @throws ActionMLAPIError Request error
     */
    public function sendQuery(array $query)
    {
        return $this->sendRequest("POST", "/engines/$this->engineId/queries", json_encode($query));
    }

    /**
     * Send user query to an Engine Instance
     *
     * @param string $uid User id
     * @param array $biznesRules Biznes rules ex:
     * [
     * [
     * 'name' => 'category',
     * 'values' => ['phone','tablets'],
     * 'bias' => 1.02,
     * ],
     * [
     * 'name' => 'brand',
     * 'values' => ['apple'],
     * 'bias' => -1,
     * ],
     * ]
     *
     * @return array JSON response
     *
     * @throws ActionMLAPIError Request error
     */

    public function queryUser($uid, array $biznesRules = null)
    {
        $query = [
            'user' => strval($uid),
        ];

        if (!empty($biznesRules)) {
            $query = array_merge($query, ['rules' => $biznesRules]);
        }

        return $this->sendQuery($query);
    }

    /**
     * Send item query to an Engine Instance
     *
     * @param string $iid Item id
     * @param array $biznesRules Biznes rules ex:
     * [
     * [
     * 'name' => 'category',
     * 'values' => ['phone','tablets'],
     * 'bias' => 1.02,
     * ],
     * [
     * 'name' => 'brand',
     * 'values' => ['apple'],
     * 'bias' => -1,
     * ],
     * ]
     *
     * @return array JSON response
     *
     * @throws ActionMLAPIError Request error
     */

    public function queryItem($iid, array $biznesRules = null)
    {
        $query = [
            'item' => strval($iid),
        ];

        if (!empty($biznesRules)) {
            $query = array_merge($query, ['rules' => $biznesRules]);
        }

        return $this->sendQuery($query);
    }


    /**
     * Send user query to an Engine Instance
     *
     * @param array $iids User id
     * @param array $biznesRules Biznes rules ex:
     * [
     * [
     * 'name' => 'category',
     * 'values' => ['phone','tablets'],
     * 'bias' => 1.02,
     * ],
     * [
     * 'name' => 'brand',
     * 'values' => ['apple'],
     * 'bias' => -1,
     * ],
     * ]
     *
     * @return array JSON response
     *
     * @throws ActionMLAPIError Request error
     */

    public function queryItemSet(array $iids, array $biznesRules = null)
    {
        $query = [
            'itemSet' => array_map('strval', $iids),
        ];

        if (!empty($biznesRules)) {
            $query = array_merge($query, ['rules' => $biznesRules]);
        }

        return $this->sendQuery($query);
    }
}
