

##
##
##

#export DOCKER_ENABLE_DEPRECATED_PULL_SCHEMA_1_IMAGE 1
#DOCKER_ENABLE_DEPRECATED_PULL_SCHEMA_1_IMAGE=1

-include .env_default
-include .env
export


# export BACKUP_EXPORT_DIR=$(pwd)


help:	## Show this help
	@sed -ne '/@sed/!s/## //p' $(MAKEFILE_LIST)


config:

	echo "BACKUP_EXPORT_DIR=$(BACKUP_EXPORT_DIR)"

	cp .env ./nginx/.env
	cd nginx && make config && cd ..

	cp .env ./php/.env
	cd php && make config && cd ..

	# cp .env ./pg/.env
	mkdir -p pg/init/01_create

	# cp .env ./postfix/.env


run: up

stop: down

backup:
	cd pg && make backup && cd ..
	cd php && make backup && cd ..

backup_export:
	docker run -it \
		-v ph_coms_$(ENV_NAME)_backup:/data/backup \
		-v $(BACKUP_EXPORT_DIR):/data/export \
		alpine \
		/bin/sh -c 'if [ `ls /data/backup/* | wc -l` -gt 0 ]; then mv -n /data/backup/* /data/export/; fi'


# rm_volumes:
# 	docker volume rm ph_coms_$(ENV_NAME)_pg-data


build:
	docker compose build

debug:	## Start docker compose with debug output
	docker compose up

up:	## Start docker compose detouched
	docker compose up -d

down:	## Stop docker compose
	docker compose down

ps:	## List docker compose containers
	docker compose ps

c: ## Show compose file after environment variables interpolated
	docker compose config



