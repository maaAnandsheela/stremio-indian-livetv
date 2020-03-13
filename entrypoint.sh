#!/bin/sh

ln -sf /dev/stdout /var/log/apache2/access.log && \
ln -sf /dev/stderr /var/log/apache2/error.log

mkdir -p /run/apache2 && \
  /usr/sbin/httpd -D FOREGROUND

exit "$@"
