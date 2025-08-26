#!/bin/sh

rm /etc/supervisor/conf.d/supervisord.conf -f
mv /etc/supervisor/conf.d/supervisord-worker.conf /etc/supervisor/conf.d/supervisord.conf -f
