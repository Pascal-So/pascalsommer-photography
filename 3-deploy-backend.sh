#!/usr/bin/env bash

set -eu

IMAGE_FILE_NAME="php-backend.image"

if [[ ! -f "${IMAGE_FILE_NAME}" ]]; then
  echo "${IMAGE_FILE_NAME} not found. run this script from the root directory, and make sure to build the backend first."
  exit 1
fi

. .env
if [[ -z "${SCP_SERVER_PATH+x}" ]]; then
  echo "env variables (SCP_SERVER_PATH etc.) are missing. Please set up the .env file based on .env.example"
  exit 1
fi

rsync -avzi ./backend/laravel/.env.example "${SCP_SERVER_PATH}/"

docker --context "${DOCKER_CONTEXT}" image load -i "${IMAGE_FILE_NAME}"

DOCKER_COMPOSE_COMMAND="docker --context "${DOCKER_CONTEXT}" compose -f compose.server.yaml --env-file .env --env-file backend/laravel/.env"

$DOCKER_COMPOSE_COMMAND stop
$DOCKER_COMPOSE_COMMAND rm backend # the backend should be completely ephemeral other than some caching
$DOCKER_COMPOSE_COMMAND up -d
$DOCKER_COMPOSE_COMMAND logs -f
