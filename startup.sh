#!/bin/bash
rm -f /home/site/wwwroot/hostingstart.html
mkdir -p /home/site/wwwroot/storage/framework/{sessions,views,cache/data} /home/site/wwwroot/storage/logs
chmod -R 775 /home/site/wwwroot/storage /home/site/wwwroot/bootstrap/cache

# Windows CRLF line endings thibunoth Linux LF walata harawanawa
sed -i 's/\r$//' /home/site/wwwroot/default

# Azure startup script eka iwara unata passe Nginx config eka apply karanawa
(sleep 10 && cp /home/site/wwwroot/default /etc/nginx/sites-available/default && nginx -s reload) &
