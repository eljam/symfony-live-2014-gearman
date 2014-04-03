<?php

# create the gearman client
$gmc= new GearmanClient();

# add the default server (localhost)
$gmc->addServer();

# register some callbacks
$gmc->setCreatedCallback("reverse_created");
$gmc->setDataCallback("reverse_data");
$gmc->setStatusCallback("reverse_status");
$gmc->setCompleteCallback("reverse_complete");
$gmc->setFailCallback("reverse_fail");

# set some arbitrary application data
$data['foo'] = 'bar';

# add two tasks
# Background task don't send complete callback
$task= $gmc->addTaskBackground("reverse", "foo", $data);
$task2= $gmc->addTaskLow("reverse", "bar", null);

# run the tasks in parallel (assuming multiple workers)
if (! $gmc->runTasks()) {
    echo "ERROR " . $gmc->error() . "\n";
    exit;
}

echo "DONE\n";

function reverse_created($task)
{
    echo "CREATED: " . $task->jobHandle() . "\n";
}

function reverse_status($task)
{
    echo "STATUS: " . $task->jobHandle() . " - " . $task->taskNumerator() .
         "/" . $task->taskDenominator() . "\n";
}

function reverse_complete($task)
{
    echo "COMPLETE: " . $task->jobHandle() . ", " . $task->data() . "\n";
}

function reverse_fail($task)
{
    echo "FAILED: " . $task->jobHandle() . "\n";
}

function reverse_data($task)
{
    echo "DATA: " . $task->data() . "\n";
}
