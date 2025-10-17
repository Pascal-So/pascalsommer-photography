#!/usr/bin/env bash

set -eu

if [[ ! -d frontend ]]; then
  echo "run this script from the root directory"
  exit 1
fi

cd frontend

. .env

if [[ -z "$DEPLOY_SSH_PATH" ]]; then
  echo "the deploy path is not set"
  exit 1
fi

npm run build
echo "build size: $(du -sh dist/ | cut -f 1)"

rsync -avzi --delete dist/ "$DEPLOY_SSH_PATH"

