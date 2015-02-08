<?php
// Load SQL
$sql = file_get_contents(__DIR__ . '/../db/test.sql');
$app['db']->query($sql);