# Playwright E2E tests

## Prepare

`composer install`
`npm install`
`cp .env.secret.dist .env.secret` and add a Monero address and view key

## Run

Start the local WordPress environment:

```
npx wp-env start
```

Run the tests:

```
npx playwright test
```

Delete the local environment

```
npx wp-env destroy
```

## Commands

```
# View the site for development work – i.e. updates as the plugin files are changed
open http://localhost:8888

# View the site used for automated tests – uses a build of the plugin which is installed during wp-env start
open http://localhost:8889

# Destroy the environment and restart
echo Y | npx wp-env destroy; npx wp-env start

# Start the playwright test runner UI and return to the Terminal (otherwise Terminal is unavailable until the application is exited).
npx playwright test --ui &;

# Start browser and record Playwright steps
npx playwright codegen -o tests/e2e-pw/example.spec.ts

# Run WP CLI commands on the development instance
npx wp-env run cli wp cron event run --all

# Run WP CLI commands on the tests instance
npx wp-env run tests-cli wp option get rewrite_rules
```

## Examples

The full WooCommerce and WordPress repositories are installed via Composer to provide examples of tests.
`vendor/woocommerce/woocommerce/plugins/woocommerce/tests/e2e-pw/`
`vendor/wordpress/wordpress/tests/e2e`

## Configuration

Scripts invoked by `.wp-env.yml` `lifecycleScripts`/`afterStart`.

`initialize-external.sh` 
1. Builds the plugin zip file
2. Runs each of the other `.sh` files inside the containers

`initialize-internal.sh` is run on both containers
1. Activates all plugins
2. Configures pretty permalinks
3. Sets up WooCommerce

`initialize-internal-dev.sh`
Nothing currently

`initialize-internal-tests.sh`
1. Installs the latest plugin file

https://bhwp.ie/?p=215
