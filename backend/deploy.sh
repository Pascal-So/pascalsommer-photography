#!/usr/bin/env bash
set -eu

cd laravel

yarn install --frozen-lockfile
npm run prod

composer install
rsync -a -vvi -zz --progress --delete vendor/ pascal:/var/www/photography/laravel/vendor

cd ..

git ftp push
