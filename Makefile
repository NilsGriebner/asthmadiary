# This file is licensed under the Affero General Public License version 3 or
# later. See the COPYING file.

app_name=$(notdir $(CURDIR))
project_directory=$(CURDIR)/../$(app_name)
build_tools_directory=$(CURDIR)/build/tools
source_build_directory=$(CURDIR)/build/artifacts/source
source_package_name=$(source_build_directory)/$(app_name)
appstore_build_directory=$(CURDIR)/build/artifacts/appstore
appstore_package_name=$(appstore_build_directory)/$(app_name)

# dev
dev-setup: clean clean-dev npm-init

npm-init:
	npm install

npm-update:
	npm update

#building
build-js:
	npm run dev

build-js-production:
	npm run build

#cleaning
clean:
	rm -rf js/asthmadiary.js
	rm -rf js/asthmadiary.js.map

clean-dev:
	rm -rf node_modules

#testing
test-php:
	phpunit -c phpunit.xml
	phpunit/phpunit -c phpunit.integration.xml

appstore:
	rm -rf $(appstore_build_directory)
	mkdir -p $(appstore_build_directory)
	tar cvzf $(appstore_package_name).tar.gz \
	--exclude-vcs \
	$(project_directory)/appinfo \
	$(project_directory)/css \
	$(project_directory)/img \
	$(project_directory)/l10n \
	$(project_directory)/lib \
	$(project_directory)/templates \
	$(project_directory)/js \
	$(project_directory)/COPYING \