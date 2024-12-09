

##
##
##

#export DOCKER_ENABLE_DEPRECATED_PULL_SCHEMA_1_IMAGE 1
#DOCKER_ENABLE_DEPRECATED_PULL_SCHEMA_1_IMAGE=1

-include .env_default
-include .env
export


help:	## Show this help
	@sed -ne '/@sed/!s/## //p' $(MAKEFILE_LIST)


config:
	cat nginx/default-template.conf \
		| envsubst '$$IPACS_DOMAIN_NAME $$COMS_DOMAIN_NAME $$CAP_DOMAIN_NAME $$LIB_DOMAIN_NAME $$CONF_DOMAIN_NAME $$ALBUM_DOMAIN_NAME' \
		> nginx/default.conf

	cp .env ./php/.env
	cd php && make config && cd ..

	mkdir -p app/coms/papers
	chmod 777 app/coms/papers
	mkdir -p data/papers
	chmod 777 data/papers
	mkdir -p data/pg
	mkdir -p pg/init/01_create

run: up

stop: down

backup:
	cd pg && make backup && cd ..


rm_volumes:
	docker volume rm ph_coms_$(ENV_NAME)_pg-data



build:
	docker compose build
	#docker compose build postgres

debug:	## Start docker compose with debug output
	docker compose up

up:	## Start docker compose detouched
	docker compose up -d

down:	## Stop docker compose
	docker compose down

ps:	## List docker compose containers
	docker compose ps

c:
	docker compose config



