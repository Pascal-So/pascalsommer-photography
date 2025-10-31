#!/usr/bin/env bash

set -eu

if [[ ! -d backend ]]; then
  echo "run this script from the root directory"
  exit 1
fi

# todo: move this to a separate Dockerfile stage
cd backend/laravel
yarn install --frozen-lockfile
npm run prod
cd ../..

docker compose -f compose.local.yaml build backend
docker image save -o php-backend.image php-backend

