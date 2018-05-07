server {
    listen 80;
    server_name local-bot.portal.com;

    location  / {
        access_log off;
        proxy_pass         http://discord_bot:80;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header Host $host;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    }

    error_log /var/log/nginx/http_error.log;
    access_log /var/log/nginx/http_access.log;
}
