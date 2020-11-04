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

use actionml\EngineClient;

$client = new EngineClient('test_ur');
$response = $client->getStatus();
echo 'Status: ' . $response . PHP_EOL;

print_r($client->getEngineStatus());
//print_r($client->getEnginesStatus());

// Rank item 1 to 5 for each user
for ($i = 1; $i <= 10; $i++) {
    $response = $client->queryUser($i);
    print_r($response);
}

// Rank item with biznes rules
$response = $client->queryUser($i, [
    [
        'name' => 'category',
        'values' => ['phone', 'tablets'],
        'bias' => 1.02,
    ],
    [
        'name' => 'brand',
        'values' => ['apple'],
        'bias' => -1,
    ],
]);
print_r($response);

// Rank item set
$response = $client->queryUserItem(1, 1);
print_r($response);

// Rank item set
$response = $client->queryItem(1);
print_r($response);

// Rank item set
$response = $client->queryItemSet([1, 4, 5]);
print_r($response);

