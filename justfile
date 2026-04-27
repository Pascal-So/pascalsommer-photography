set dotenv-load

admin-docker-image-name := "php-admin"
admin-docker-image-filename := f"{{ admin-docker-image-name }}.image"

admin-scp-server := env("ADMIN_SCP_SERVER")
admin-data-root := env("ADMIN_DATA_ROOT")

admin-scp-path := f"{{ admin-scp-server }}:{{ admin-data-root }}"

# Assembling the command for using docker compose in the context
# of the admin backend server.
admin-docker-context := env("ADMIN_DOCKER_CONTEXT")
admin-docker-cmd := f"docker --context {{ admin-docker-context }}"
admin-docker-compose-cmd := f"{{ admin-docker-cmd }} compose -f compose.server.yaml --env-file .env --env-file backend/laravel/.env"
local-admin-docker-compose-cmd := f"docker compose -f compose.local.yaml --env-file backend/laravel/.env"

backup-dir := "data-backups"
sql-backup-file := `ls data-backups/photography-admin-*.sql -r | head -n 1`

# Fetch the latest content from the admin server to a local backup
content-fetch:
    source backend/laravel/.env && {{ admin-docker-compose-cmd }} exec -T mysql /usr/bin/mysqldump -uroot -p"${DB_PASSWORD}" "${DB_DATABASE}" >"{{ backup-dir }}/photography-admin-$(date +%Y-%m-%d).sql"
    rsync -avi "{{ admin-scp-path }}"/img/{thumbs,photos} backend/img/

run-admin-locally-from-backup:
    #!/usr/bin/env bash
    set -eux

    {{ local-admin-docker-compose-cmd }} down

    if [[ -f "{{ sql-backup-file }}" ]]; then
        {{ local-admin-docker-compose-cmd }} up -d mysql

        echo "waiting for mysql container to be ready.."
        sleep 15;

        source backend/laravel/.env && {{ local-admin-docker-compose-cmd }} exec -T mysql mysql -u"${DB_USERNAME}" -p"${DB_PASSWORD}" "${DB_DATABASE}" <"{{ sql-backup-file }}"
    fi

    {{ local-admin-docker-compose-cmd }} up -d
    {{ local-admin-docker-compose-cmd }} logs -f

    
[working-directory: 'frontend']
content-build:
    rsync -avi ../backend/img/photos/ src/data/real-data/img/
    npm install
    npm run build
    echo "build size: $(du -sh dist/ | cut -f 1)"

[working-directory: 'frontend']
content-publish-to-staging:
    #!/usr/bin/env bash
    set -eux

    source .env && rsync -avzi --delete dist/ "$DEPLOY_SSH_PATH_STAGING"

[working-directory: 'frontend']
content-publish-to-prod:
    #!/usr/bin/env bash
    set -eux

    source .env && rsync -avzi --delete dist/ "$DEPLOY_SSH_PATH_PROD"


deploy-admin:
    rsync -avzi ./backend/laravel/.env.example "{{ admin-scp-path }}/"

    docker --context "${DOCKER_CONTEXT}" image load -i "${IMAGE_FILE_NAME}"

    {{ admin-docker-compose-cmd }} stop
    {{ admin-docker-compose-cmd }} rm backend # the backend should be completely ephemeral other than some caching
    {{ admin-docker-compose-cmd }} up -d
    {{ admin-docker-compose-cmd }} logs -f

build-admin:
    # todo: move this to a separate Dockerfile stage
    cd backend/laravel
    yarn install --frozen-lockfile
    npm run prod
    cd ../..

    docker compose -f compose.local.yaml build backend
    docker image save -o php-backend.image php-backend
