# MoneroWP
A WooCommerce extension for accepting Monero

## Dependancies
This plugin is rather simple but there are a few things that need to be set up before hand.

* A web server! Ideally with the most recent versions of PHP and mysql

* The Monero wallet-cli and Monero wallet-rpc tools found [here](https://getmonero.org/downloads/)

* [WordPress](https://wordpress.org)
Wordpress is the backend tool that is needed to use WooCommerce and this Monero plugin

* [WooCommerce](https://woocommerce.com)
This Monero plugin is an extension of WooCommerce, which works with WordPress

## Step 1: Activating the plugin
* Downloading: First of all, you will need to download the plugin. You can download the latest release as a .zip file from https://github.com/monero-integrations/monerowp/releases If you wish, you can also download the latest source code from GitHub. This can be done with the command `git clone https://github.com/monero-integrations/monerowp.git` or can be downloaded as a zip file from the GitHub web page.

* Unzip the file monerowp_release.zip if you downloaded the zip from the releases page [here](https://github.com/monero-integrations/monerowp/releases).

* Put the plugin in the correct directory: You will need to put the folder named `monero` from this repo/unzipped release into the wordpress plugins directory. This can be found at `path/to/wordpress/folder/wp-content/plugins`

* Activate the plugin from the WordPress admin panel: Once you login to the admin panel in WordPress, click on "Installed Plugins" under "Plugins". Then simply click "Activate" where it says "Monero - WooCommerce Gateway"

## Step 2: Get a monero daemon to connect to

### Option 1: Running a full node yourself

To do this: start the monero daemon on your server and leave it running in the background. This can be accomplished by running `./monerod` inside your monero downloads folder. The first time that you start your node, the monero daemon will download and sync the entire monero blockchain. This can take several hours and is best done on a machine with at least 4GB of ram, an SSD hard drive (with at least 40GB of free space), and a high speed internet connection.

### Option 2: Connecting to a remote node
The easiest way to find a remote node to connect to is to visit [moneroworld.com](https://moneroworld.com/#nodes) and use one of the nodes offered. It is probably easiest to use node.moneroworld.com:18089 which will automatically connect you to a random node.

## Step 3: Setup your  monero wallet-rpc

* Setup a monero wallet using the monero-wallet-cli tool. If you do not know how to do this you can learn about it at [getmonero.org](https://getmonero.org/resources/user-guides/monero-wallet-cli.html)

* [Create a view-only wallet from that wallet for security.](https://monero.stackexchange.com/questions/3178/how-to-create-a-view-only-wallet-for-the-gui/4582#4582)

* Start the Wallet RPC and leave it running in the background. This can be accomplished by running `./monero-wallet-rpc --rpc-bind-port 18082 --disable-rpc-login --log-level 2 --wallet-file /path/viewOnlyWalletFile` where "/path/viewOnlyWalletFile" is the wallet file for your view-only wallet. If you wish to use a remote node you can add the `--daemon-address` flag followed by the address of the node. `--daemon-address node.moneroworld.com:18089` for example.

## Step 4: Setup Monero Gateway in WooCommerce

* Navigate to the "settings" panel in the WooCommerce widget in the WordPress admin panel.

* Click on "Checkout"

* Select "Monero GateWay"

* Check the box labeled "Enable this payment gateway"

* Enter your monero wallet address in the box labled "Monero Address". If you do not know your address, you can run the `address` commmand in your monero wallet

* Enter the IP address of your server in the box labeled "Monero wallet rpc Host/IP"

* Enter the port number of the Wallet RPC in the box labeled "Monero wallet rpc port" (will be `18082` if you used the above example).

* Enter the username and password that you want to use in their respective feilds

* Click on "Save changes"

## Donating to the Devs :)
XMR Address : `44krVcL6TPkANjpFwS2GWvg1kJhTrN7y9heVeQiDJ3rP8iGbCd5GeA4f3c2NKYHC1R4mCgnW7dsUUUae2m9GiNBGT4T8s2X`
