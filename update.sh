#!/bin/bash
git pull
sudo php bin/console cache:clear
sudo chown -R www-data:www-data /var/cache/prod/twig
sudo chown -R www-data:www-data /var/www/bcd-backend/var/cache/prod