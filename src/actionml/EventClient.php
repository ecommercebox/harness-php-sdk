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

use DateTimeZone;
use GuzzleHttp\Client;
use \DateTime;
use function GuzzleHttp\Psr7\str;

/**
 * Client for connecting to an Event Server
 *
 */
class EventClient extends BaseClient
{
    const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s.u\Z';
    private $engineId;
    private $eventUrl;

    /**
     * @param string Access Key
     * @param string Base URL to the Event Server. Default is localhost:9090.
     * @param float Timeout of the request in seconds. Use 0 to wait indefinitely
     *              Default is 0.
     * @param float Number of seconds to wait while trying to connect to a server.
     *              Default is 5.
     */
    public function __construct(
        $engineId,
        $baseUrl = 'http://localhost:9090',
        $timeout = 0,
        $connectTimeout = 5
    )
    {
        parent::__construct($baseUrl, $timeout, $connectTimeout);
        $this->engineId = $engineId;
        $this->eventUrl = "/engines/$this->engineId/events";
    }

    private function getEventTime($eventTime)
    {
        $result = $eventTime;
        if (!isset($eventTime)) {
//            $dt = new DateTime($eventTime);
//            $dt->setTimezone(new DateTimeZone('UTC'));
//            return $dt->format('Y-m-d\TH:i:s.u\Z');
            $result = (new DateTime('NOW'))->format(self::DATE_TIME_FORMAT);
        }

        return $result;
    }

    /**
     * Set a user entity
     *
     * @param int|string User Id
     * @param array Properties of the user entity to set
     * @param string Time of the event in ISO 8601 format
     *               (e.g. 2014-09-09T16:17:42.937-08:00).
     *               Default is the current time.
     *
     * @return string JSON response
     *
     * @throws ActionMLAPIError Request error
     */
    public function setUser($uid, array $properties = array(), $eventTime = null)
    {
        throw new ActionMLAPIError('Not implemented in ActionML');
    }

    /**
     * Unset a user entity
     *
     * @param int|string User Id
     * @param array Properties of the user entity to unset
     * @param string Time of the event in ISO 8601 format
     *               (e.g. 2014-09-09T16:17:42.937-08:00).
     *               Default is the current time.
     *
     * @return string JSON response
     *
     * @throws ActionMLAPIError Request error
     */
    public function unsetUser($uid, array $properties, $eventTime = null)
    {
        throw new ActionMLAPIError('Not implemented in ActionML');
    }

    /**
     * Delete a user entity
     *
     * @param int|string User Id
     * @param string Time of the event in ISO 8601 format
     *               (e.g. 2014-09-09T16:17:42.937-08:00).
     *               Default is the current time.
     *
     * @return string JSON response
     *
     * @throws ActionMLAPIError Request error
     */
    public function deleteUser($uid, $eventTime = null)
    {
        throw new ActionMLAPIError('Not implemented in ActionML');
    }

    /**
     * Set an item entity
     *
     * @param int|string Item Id
     * @param array Properties of the item entity to set
     * @param string Time of the event in ISO 8601 format
     *               (e.g. 2014-09-09T16:17:42.937-08:00).
     *               Default is the current time.
     *
     * @return string JSON response
     *
     * @throws ActionMLAPIError Request error
     */
    public function setItem($iid, array $properties = array(), $eventTime = null)
    {
        $eventTime = $this->getEventTime($eventTime);

        $json = json_encode([
            'event' => '$set',
            'entityType' => 'item',
            'entityId' => strval($iid),
            'properties' => $this->convertProperties($properties),
            'eventTime' => $eventTime,
        ]);

        return $this->sendRequest('POST', $this->eventUrl, $json);
    }

    /**
     * Unset an item entity
     *
     * @param int|string Item Id
     * @param array Properties of the item entity to unset
     * @param string Time of the event in ISO 8601 format
     *               (e.g. 2014-09-09T16:17:42.937-08:00).
     *               Default is the current time.
     *
     * @return string JSON response
     *
     * @throws ActionMLAPIError Request error
     */
    public function unsetItem($iid, array $properties, $eventTime = null)
    {
        throw new ActionMLAPIError('Not implemented in ActionML');
    }

    /**
     * Delete an item entity
     *
     * @param int|string Item Id
     * @param string Time of the event in ISO 8601 format
     *               (e.g. 2014-09-09T16:17:42.937-08:00).
     *               Default is the current time.
     *
     * @return string JSON response
     *
     * @throws ActionMLAPIError Request error
     */
    public function deleteItem($iid, $eventTime = null)
    {
        $eventTime = $this->getEventTime($eventTime);

        $json = json_encode([
            'event' => '$delete',
            'entityType' => 'item',
            'entityId' => strval($iid),
            'eventTime' => $eventTime,
        ]);

        return $this->sendRequest('POST', $this->eventUrl, $json);
    }

    /**
     * Record a user action on an item
     *
     * @param string Event name
     * @param int|string User Id
     * @param int|string Item Id
     * @param array Properties of the event
     * @param string Time of the event in ISO 8601 format
     *               (e.g. 2014-09-09T16:17:42.937-08:00).
     *               Default is the current time.
     *
     * @return string JSON response
     *
     * @throws ActionMLAPIError Request error
     */
    public function recordUserActionOnItem(
        $event,
        $uid,
        $iid,
        array $properties = array(),
        $eventTime = null
    )
    {
        $eventTime = $this->getEventTime($eventTime);

        $json = json_encode([
            'event' => $event,
            'entityType' => 'user',
            'entityId' => strval($uid),
            'targetEntityType' => 'item',
            'targetEntityId' => strval($iid),
            'properties' => $this->convertProperties($properties),
            'eventTime' => $eventTime,
        ]);

        return $this->sendRequest('POST', $this->eventUrl, $json);
    }

    /**
     * Create an event
     *
     * @param array An array describing the event
     *
     * @return string JSON response
     *
     * @throws ActionMLAPIError Request error
     */
    public function createEvent(array $data)
    {
        $json = json_encode($data);

        return $this->sendRequest('POST', $this->eventUrl, $json);
    }

    /**
     * Retrieve an event
     *
     * @param string Event ID
     *
     * @return string JSON response
     *
     * @throws ActionMLAPIError Request error
     */
    public function getEvent($eventId)
    {
        throw new ActionMLAPIError('Not implemented in ActionML');
    }


}
