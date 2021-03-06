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

require_once("vendor/autoload.php");

use actionml\EventClient;
use actionml\ActionMLAPIError;

try {
    // check Event Server status
    $client = new EventClient(
        "test_ur"
    );
    $response = $client->getStatus();
    echo($response);

    // set item with event time
    $response = $client->setItem(
        3,
        array('itypes' => array('1')),
        '2013-12-20T05:15:25.350+08:00'
    );
    print_r($response);

    // set item
    $response = $client->setItem(2, array('itypes' => array('1')));
    print_r($response);


    // delete item
    $response = $client->deleteItem(3, '2000-01-01T01:01:01.001+01:00');
    print_r($response);

    // record user action on item
    $response = $client->recordUserActionOnItem('view', 8, 2);
    print_r($response);

    // create event
    $response = $client->createEvent(array(
        'event' => 'my_event',
        'entityType' => 'user',
        'entityId' => '8',
        'properties' => array('prop1' => 1, 'prop2' => 2),
    ));
    print_r($response);

} catch (ActionMLAPIError $e) {
    echo $e->getMessage();
}
