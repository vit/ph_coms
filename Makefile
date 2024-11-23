

##
##
##

-include .env_default
-include .env
export


help:	## Show this help
	@sed -ne '/@sed/!s/## //p' $(MAKEFILE_LIST)


config: build_web

run: up

stop: down




build:
	docker compose build postgres

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



build_web:
	cat web-coms/default-template.conf \
		| envsubst '$$IPACS_DOMAIN_NAME $$COMS_DOMAIN_NAME $$CAP_DOMAIN_NAME $$LIB_DOMAIN_NAME $$CONF_DOMAIN_NAME $$ALBUM_DOMAIN_NAME' \
		> web-coms/default.conf
	mkdir -p app/papers
	chmod 777 app/papers
