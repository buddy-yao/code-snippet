<?php
 /**
  * @param string $pidfile
  * @return bool
  */
function pidfile(string $pidfile = '')
{
    if (!$full_file_path) {
        $pidfile = sys_get_temp_dir() . '/' . basename(__FILE__) . '.pid';
    }

    if (file_exists($pidfile)) {
        $pid = file_get_contents($pidfile);
        $running = posix_kill($pid, 0);
        if ($running) {
            return false;
        } else {
            unlink($pidfile);
        }
    }

    $handle = fopen($pidfile, 'x');
    if (!$handle) {
        return false;
    }
    $pid = getmypid();
    fwrite($handle, $pid);
    fclose($handle);
    register_shutdown_function(function () use ($pidfile) {
        if (file_exists($pidfile)) {
            unlink($pidfile);
        }
    });
    return true;
}
