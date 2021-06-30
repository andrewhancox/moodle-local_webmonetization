<?php


require_once(__DIR__ . '/../../config.php');

redirect($CFG->wwwroot, get_string('nonmonetizedbrowser', 'local_webmonetization'), 0, \core\output\notification::NOTIFY_ERROR);
