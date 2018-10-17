#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )" #script directory

php "$DIR/Core/Database/deploy.php" #create the table at database