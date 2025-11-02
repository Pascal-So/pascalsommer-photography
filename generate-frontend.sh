#!/usr/bin/env bash

set -eu

if [[ $# != 1 ]]; then
  echo "usage: $1 (prod/staging)"
  exit 2
fi

if [[ ! -d frontend ]]; then
  echo "run this script from the root directory"
  exit 1
fi

cd frontend

. .env

if [[ "${1}" = "prod" ]]; then
  echo "deploying to prod"
  SSH_PATH="${DEPLOY_SSH_PATH_PROD}"
else
  echo "deploying to staging"
  SSH_PATH="${DEPLOY_SSH_PATH_STAGING}"
fi

if [[ -z "$SSH_PATH" ]]; then
  echo "the deploy path is not set"
  exit 1
fi

npm run build
echo "build size: $(du -sh dist/ | cut -f 1)"

rsync -avzi --delete dist/ "$SSH_PATH"

