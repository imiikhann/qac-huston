#!/bin/bash
# Starts the Next.js production server on 127.0.0.1:3000.
# Safe to run from cron every minute: it does nothing if the server is already running.
cd /home/qaccpadm/qac/frontend || exit 1
source /home/qaccpadm/nodevenv/qac/frontend/20/bin/activate
export NODE_ENV=production
export PORT=3000

PIDFILE=/home/qaccpadm/qac/frontend/.server.pid
if [ -f "$PIDFILE" ] && kill -0 "$(cat "$PIDFILE")" 2>/dev/null; then
  exit 0
fi

nohup node server.js >> /home/qaccpadm/qac/frontend/server-prod.log 2>&1 &
echo $! > "$PIDFILE"
