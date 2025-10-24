#!/usr/bin/env bash

set -eu

BACKUP_DIR="data-backups"

if [[ ! -f compose.server.yaml ]]; then
  echo "compose.server.yaml not found. run this script from the root directory."
  exit 1
fi

. .env
. backend/laravel/.env

if [[ -z "${DOCKER_CONTEXT+x}" ]]; then
  echo "env variables (DOCKER_CONTEXT etc.) are missing. Please set up the .env file based on .env.example"
  exit 1
fi

# get the lexicographically latest sql file
SQL_FILE="$(ls "${BACKUP_DIR}"/*.sql -r | head -n 1)"
if [[ ! -f "${SQL_FILE}" ]]; then
  exit 1
fi

read -e -p "This will completely reset the remote database and restore it from ${SQL_FILE}. Do you want to continue? [y/N] " choice
if [[ "${choice}" != [Yy]* ]]; then
  echo "aborting process.."
  exit 1
fi

DOCKER_COMPOSE_COMMAND="docker --context "${DOCKER_CONTEXT}" compose -f compose.server.yaml --env-file .env --env-file backend/laravel/.env"
echo "resetting database.."
$DOCKER_COMPOSE_COMMAND exec mysql mysql -uroot -p"${DB_PASSWORD}" -e "drop database ${DB_DATABASE}; create database ${DB_DATABASE};"
echo "restoring backup.."
$DOCKER_COMPOSE_COMMAND exec -T mysql mysql -uroot -p"${DB_PASSWORD}" "${DB_DATABASE}" <"${SQL_FILE}"

rsync -avi backend/img/{thumbs,photos} "${SCP_SERVER_PATH}"/img/
