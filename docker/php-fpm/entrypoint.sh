#!/bin/bash

set -e

if  ! [ -e "/app/.env" ] ; then
    echo "[ ****************** ] Crie o seu .env"
fi

set -- php-fpm

exec "$@"
