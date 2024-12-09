

##
##
##

# -include .env_default
# -include .env
# export


help:	## Show this help
	@sed -ne '/@sed/!s/## //p' $(MAKEFILE_LIST)


.PHONY: backup
backup:
	mkdir -p ./backup
	rm -f ./backup/*
	docker exec -t ph_coms_pg_development pg_dumpall --exclude-database=root -U postgres > ./backup/dump_`date +%Y-%m-%d"_"%H_%M_%S`.sql




