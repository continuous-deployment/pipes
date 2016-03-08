#!/bin/bash
# Get the directory the script is located in
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
# Move one directory up to the project root
cd $DIR/../

# Copy docker build specific environment file
cp .env.docker.build .env

# Make sure the database exists
touch database/database.sqlite

# Install php dependancies
docker run --rm -it -v `pwd`:/var/www hourd/php php composer.phar install --prefer-source --no-interaction
# Set up the database
docker run --rm -it -v `pwd`:/var/www hourd/php php artisan migrate

# Build the docker container
docker build -t continuousdeployment/pipes .
