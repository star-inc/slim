#!/bin/sh
sh -c "cd api/public && $(which php) -S 127.0.0.1:1270 index.php" &
sh -c "cd web && npm run serve" &
wait
