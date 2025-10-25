#!/usr/bin/env bash

set -eu

BACKUP_DIR="data-backups"

. backend/laravel/.env
if [[ -z "${DB_DATABASE+x}" ]]; then
  echo "env variables (DB_DATABASE etc.) are missing. make sure you're in the root dir and that backend/laravel/.env is set up"
  exit 1
fi

DOCKER_COMPOSE_COMMAND="docker compose -f compose.local.yaml --env-file backend/laravel/.env"

$DOCKER_COMPOSE_COMMAND down

SQL_FILE="$(ls "${BACKUP_DIR}"/*.sql -r | head -n 1)"
if [[ -f "${SQL_FILE}" ]]; then
  $DOCKER_COMPOSE_COMMAND up -d mysql

  echo "waiting for mysql container to be ready.."
  sleep 15;

  $DOCKER_COMPOSE_COMMAND exec -T mysql mysql -u"${DB_USERNAME}" -p"${DB_PASSWORD}" "${DB_DATABASE}" <"${SQL_FILE}"
fi

$DOCKER_COMPOSE_COMMAND up -d
$DOCKER_COMPOSE_COMMAND logs -f
