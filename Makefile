

##
##
##

-include .env_default
-include .env
export



help:	## Show this help
	@sed -ne '/@sed/!s/## //p' $(MAKEFILE_LIST)


config:

	# echo "DATA_EXPORT_DIR=$(DATA_EXPORT_DIR)"

	cp .env ./nginx/.env
	cd nginx && make config && cd ..

	cp .env ./php/.env
	cd php && make config && cd ..

	# cp .env ./pg/.env
	cd pg && make config && cd ..

	# cp .env ./postfix/.env


run: up

stop: down

backup:
	cd pg && make backup && cd ..
	cd php && make backup && cd ..

#	docker exec ph_coms_pg_stage bash -c 'pg_dumpall --exclude-database=root -U postgres | cat' > ./data/export/pg_dump_stage_`date +%Y-%m-%d"_"%H_%M_%S`.sql


backup_export:
	docker run -it \
		-v ph_coms_$(ENV_NAME)_backup:/data/backup \
		-v $(DATA_EXPORT_DIR):/data/export \
		alpine \
		/bin/sh -c 'if [ `ls /data/backup/* | wc -l` -gt 0 ]; then mv -n /data/backup/* /data/export/; fi'

import_papers:
	docker cp $(DATA_IMPORT_DIR)/papers/. ph_coms_php_$(ENV_NAME):/data/papers/


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



