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

// check Event Server status
$client = new EventClient("test_ur");
$response = $client->getStatus();
echo($response);


// set item - generate 50 items
for ($i = 1; $i <= 50; $i++) {
    $response = $client->setItem($i, array('itypes' => array('1')));
    print_r($response);
}

// record event - each user randomly views 10 items
for ($u = 1; $u <= 10; $u++) {
    for ($count = 0; $count < 10; $count++) {
        $i = rand(1, 50);
        $response = $client->recordUserActionOnItem('purchase', $u, $i);
        $response = $client->recordUserActionOnItem('view', $u, $i);
        print_r($response);
    }
}
