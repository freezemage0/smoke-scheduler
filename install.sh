#!/usr/bin/env bash

mkdir -p /etc/systemd/system/smoke-scheduler.d/
mv build/smoke-scheduler /usr/local/bin/
mkdir -p /usr/local/lib/smoke-scheduler/
mv build/main.phar /usr/local/lib/smoke-scheduler/
mkdir -p /etc/smoke-scheduler.d/
mv build/config.json /etc/smoke-scheduler.d/
mkdir -p /var/lib/smoke-scheduler/

chmod 0755 -R /var/lib/smoke-scheduler/
chmod +x /usr/local/bin/smoke-scheduler

mv build/smoke-scheduler.service /etc/systemd/system/
systemctl enable smoke-scheduler.service
