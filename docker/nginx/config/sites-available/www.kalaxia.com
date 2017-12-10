server {
  listen 80;
  listen [::]:80;

  server_name www.kalaxia.com;

  return 302 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;

    ssl_certificate /etc/ssl/live/www.kalaxia.com/fullchain.pem;
    ssl_certificate_key /etc/ssl/live/www.kalaxia.com/privkey.pem;

    server_name www.kalaxia.com;

    access_log /var/log/nginx/https.access.log;
    error_log /var/log/nginx/portal.error.log;

    merge_slashes on;

    root /srv/app/web;

    location / {
        # try to serve file directly, fallback to app.php
        try_files $uri /app_dev.php$is_args$args;
    }

    location ~ ^/(app_dev|config)\.php(/|$) {
        fastcgi_pass portal_phpfpm:9000;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
    }

    location ~ ^/app\.php(/|$) {
        fastcgi_pass portal_phpfpm:9000;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    # return 404 for all other php files not matching the front controller
    # this prevents access to other php files you don't want to be accessible.
    location ~ \.php$ {
        return 404;
    }
}
