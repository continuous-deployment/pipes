#!/usr/bin/env python

import time, datetime, sys, os
location = os.path.abspath(os.path.dirname(sys.argv[0]))

print "Starting cron service"

while(True):
    now = datetime.datetime.now()

    # Run minutely commands.
    os.system("""for script in %s/cron/minute/*; do $script; done""" % (location))

    if now.minute == 30:
        # Run bihourly commands.
        os.system("""for script in %s/cron/halfhour/*; do $script; done""" % (location))
    elif now.minute == 0:
        # Run bihourly and hourly commands.
        os.system("""for script in %s/cron/halfhour/*; do $script; done""" % (location))
        os.system("""for script in %s/cron/hour/*; do $script; done""" % (location))

    time.sleep(60)
