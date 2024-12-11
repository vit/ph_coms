

##
##
##

# -include .env_default
-include .env
export


help:	## Show this help
	@sed -ne '/@sed/!s/## //p' $(MAKEFILE_LIST)


.PHONY: backup
backup:
	docker exec -t ph_coms_pg_$(ENV_NAME) bash /backup-pg.sh




