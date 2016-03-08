# Pipes #

[![travis build badge](https://travis-ci.org/datkinson/pipes.svg?branch=master)](https://travis-ci.org/datkinson/pipes)
[![Code Climate](https://codeclimate.com/github/datkinson/pipes/badges/gpa.svg)](https://codeclimate.com/github/datkinson/pipes)
[![Build Status](https://drone.continuous-deployment.software/api/badges/continuous-deployment/pipes/status.svg)](https://drone.continuous-deployment.software/continuous-deployment/pipes)

### Installation via docker ###

Docker Compose is set up for this repository

docker-compose will need to be installed on your system https://docs.docker.com/compose/install

```bash
docker-compose up
```

This will download and link all required containers and host your application on port 9000

It will auto name the containers with a prefix of the folder name and suffixed with a number starting from 1.

e.g. the folder is called pipes the containers created will be called:
- pipes_php_1
- pipes_mysql_1


To run commands inside the container you can open up a shell:
```bash
docker exec -it pipes_php_1 bash
```

or you ca run the command you want directly:
```bash
docker exec -it pipes_php_1 php composer.phar install
```

## Multiple Runners ##

If you want to run more than a single runner you can do so with the scale command:

```bash
docker-compose scale runner=15
```

This will scale the group of containers up to 15 runners.

The reverse setting the scale back down to 1 will remove the newer containers.

You may scale up and down as much as you like.
