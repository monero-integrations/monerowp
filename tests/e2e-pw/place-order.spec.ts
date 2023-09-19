import { test, expect } from '@playwright/test';

// Annotate entire file as serial.
test.describe.configure({ mode: 'serial' });

test.describe( 'Verify we can place an order with the Monero gateway', () => {

  async function login(page, goToUrl) {
    await page.goto('/wp-login.php', {waitUntil:'domcontentloaded'});

    await page.getByLabel('Username or Email Address').fill('admin');

    // Without this, "password" was being filled in the "username" field.
    await page.waitForLoadState( 'networkidle' );

    await page.locator('.wp-pwd #user_pass').fill('password');
    await page.locator('#wp-submit').click();
    await page.waitForLoadState( 'domcontentloaded' );

    await page.goto(goToUrl, {waitUntil:'domcontentloaded'});
  }

  async function logout(page) {
    let logoutLink = await page.evaluate(async() => {
      return document.getElementById('wp-admin-bar-logout').firstChild.getAttribute("href");
    });
  }

  async function configureGateway(page) {
    await login(page, "wp-admin/admin.php?page=monero_gateway_settings");

    await page.getByLabel('Enable / Disable').check();

    await page.getByLabel('Monero Address').fill(process.env.SAMPLE_PRIMARY_ADDRESS);

    await page.getByLabel('Secret Viewkey', { exact: true }).fill(process.env.SAMPLE_SECRET_VIEW_KEY);

    await page.getByRole('button', { name: 'Save changes' }).click();
    await page.waitForLoadState( 'domcontentloaded' );
  }

  async function addAProductToCart(page) {

    await page.goto('/shop', {waitUntil:'domcontentloaded'});

    await page.getByText('Add to cart').first().click();
    await page.waitForLoadState( 'networkidle' );

    await page.getByText('1 in cart');
    await page.waitForLoadState( 'networkidle' );
  }

  test('Verify gateway is visible on checkout', async ({ page }) => {
    await configureGateway(page);

    await logout(page);

    await addAProductToCart(page);

    await page.goto('/checkout', {waitUntil:'domcontentloaded'});

    await expect(page.locator('#payment')).toContainText('Monero' );
  });


  test('Order can be placed', async ({ page }) => {
    await configureGateway(page);

    await logout(page);

    await addAProductToCart(page);

    await page.goto('/checkout', {waitUntil:'domcontentloaded'});

    await page.locator( '#billing_first_name' ).fill( 'Homer' );
    await page.locator( '#billing_last_name' ).fill( 'Simpson' );
    await page.locator( '#billing_address_1' ).fill( '123 Evergreen Terrace' );
    await page.locator( '#billing_city' ).fill( 'Springfield' );
    await page.locator( '#billing_country' ).selectOption( 'US' );
    await page.locator( '#billing_state' ).selectOption( 'OR' );
    await page.locator( '#billing_postcode' ).fill( '97403' );
    await page.locator( '#billing_phone' ).fill( '555 555-5555' );
    await page.locator( '#billing_email' ).fill( "homer.simpson@example.com" );
    await page.getByRole('button', { name: 'Place order' }).click();

    await page.waitForLoadState( 'domcontentloaded' );

    // Payment error:The price for Monero could not be retrieved. Please contact the merchant.

    await expect(page.locator('.woocommerce-thankyou-order-received').first()).toContainText('Thank you. Your order has been received.' );
  });


});