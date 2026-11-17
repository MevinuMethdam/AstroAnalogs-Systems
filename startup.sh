#!/bin/bash
rm -f /home/site/wwwroot/hostingstart.html
mkdir -p /home/site/wwwroot/storage/framework/{sessions,views,cache/data} /home/site/wwwroot/storage/logs
chmod -R 775 /home/site/wwwroot/storage /home/site/wwwroot/bootstrap/cache

# Windows CRLF thibunoth Linux LF walata harawanawa
sed -i 's/\r$//' /home/site/wwwroot/default

# Nginx config eka copy karala reload karanawa
cp /home/site/wwwroot/default /etc/nginx/sites-available/default
service nginx reload