## What is Web Monetization

A JavaScript browser API which allows the creation of a payment stream from the user agent to the website.

[Find out more](https://webmonetization.org/docs/explainer)

## Set up guide
### Set up as a content publisher
In order to use this plugin 

1. Create a digital wallet and make a note of your payment pointer, there is a guide to doing this here: [Digital Wallet and Payment Pointers](https://webmonetization.org/docs/ilp-wallets)
2. [Install Moodle](https://docs.moodle.org/310/en/Installing_Moodle)
3. [Install this plugin into your Moodle site](https://docs.moodle.org/en/Installing_plugins#Installing_a_plugin). This plugin should be placed in the local/webmonetization directory in the root of your Moodle site.
4. Navigate to a Moodle course when logged in as a user with the 'local/webmonetization:managepaymentpointers' capability, in the navigation menu there will be a link "Manage payment pointer", follow this link and enter your payment pointer.

### Set up as a content consumer
In order to send payments to a site using that has this plugin installed you will need to:
1. Have an account or subscription with a Web Monetization provider (also known as a WM sender).
2. Have a Web Monetization agent installed in their browser with the necessary authorization to initiate payments from the WM provider on the user's behalf.

Signing up with [Coil](https://coil.com/) and installing one of their desktop browser extensions or their mobile puma browser is currently the easiest way to do this.

## TODO:
- [ ] https://webmonetization.org/docs/receipt-verifier
- [ ] https://webmonetization.org/docs/start-stop
- [ ] https://webmonetization.org/docs/counter
- [ ] Block access if no receipt
- [ ] Extend to system and course module contexts

Author
------

The module has been written and is currently maintained by Andrew Hancox on behalf of [Open Source Learning](https://opensourcelearning.co.uk).

The project is funded by [Grant for the Web](https://www.grantfortheweb.org) is a $100M fund to boost open, fair, and inclusive standards and innovation in Web Monetization.

Useful links
------------

* [Moodle](https://moodle.org/)
* [Coil](https://coil.com/)
* [Open Source Learning](https://opensourcelearning.co.uk)
* [Bug tracker](https://github.com/mudrd8mz/moodle-mod_subcourse/issues)
* [Grant for the Web](https://www.grantfortheweb.org)

License
-------

This program is free software: you can redistribute it and/or modify it under the
terms of the GNU General Public License as published by the Free Software Foundation,
either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this
program. If not, see <http://www.gnu.org/licenses/>.
