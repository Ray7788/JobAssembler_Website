<?php
#https://stackoverflow.com/a/409738 - Neat way to prevent direct access to certain PHP scripts
if(count(get_included_files()) <= 2) {
    http_response_code(404);
    die(0);
}