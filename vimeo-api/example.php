<?php

$vimeo_id    = '6565555';
$duration    = getVimeoDuration($vimeo_id);
$format_time = isset($dur['duration']) ? fromSeconds($duration['duration']) : '00:00';

echo $format_time;
