

##
##
##

#export DOCKER_ENABLE_DEPRECATED_PULL_SCHEMA_1_IMAGE 1
#DOCKER_ENABLE_DEPRECATED_PULL_SCHEMA_1_IMAGE=1

# -include .env_default
-include .env
export



help:	## Show this help
	@sed -ne '/@sed/!s/## //p' $(MAKEFILE_LIST)


.PHONY: config
config:
	cat ./config/msmtprc-template \
		| envsubst '$$ENV_NAME $$IPACS_DOMAIN_NAME' \
		> ./config/msmtprc




