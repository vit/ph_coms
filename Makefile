
COMS_DOMAIN_NAME=coms.physcon.my

##
##
##

help:	## Show this help
	@sed -ne '/@sed/!s/## //p' $(MAKEFILE_LIST)


debug:	## Start docker compose with debug output
	docker compose up

up:	## Start docker compose detouched
	docker compose up -d

down:	## Stop docker compose
	docker compose down

ps:	## List docker compose containers
	docker compose ps




build_web:
	cat web-coms/default-template.conf \
		| envsubst '$$COMS_DOMAIN_NAME' \
		> web-coms/default.conf


