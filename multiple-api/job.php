<?php

function createBackgroundJob($task, $data = array())
{
    $client = new \GearmanClient();
    $client->addServer(/* Defaults to 127.0.0.1, 4730 */);
    $handle = $client->doBackground($task, json_encode($data));

    if ($client->returnCode() != GEARMAN_SUCCESS) {
        return false;
    }

    return $handle;
}

echo createBackgroundJob('reverse', array('data' => 'lol for this dude'));
