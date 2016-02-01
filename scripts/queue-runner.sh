#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
while :;
do
  php $DIR/../artisan queue:listen --queue=$1 >> $DIR/queue-$1.log 2>&1
  sleep 10
done

