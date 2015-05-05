# HQ-PHP Round1

## Setup

```bash
$ git clone https://github.com/purplepixels/Round1-Test.git .
```

Create MySQL Database and table.
* Database name is optional, please set in /config/database.settings.php

```sql
CREATE DATABASE IF NOT EXISTS `HQTest` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `HQTest`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `orderData` LONGTEXT NULL,
  `transactionResult` LONGTEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;
```
## Testing
##### PHPUnit tests can be ran as follows

(Example is from Root Folder)
```bash
$ cd tests
$ phpunit --bootstrap bootstrap.php PayPal/Test/Api/CreditCardTest.php
$ phpunit --bootstrap bootstrap.php braintree/unit/CreditCardTest.php
```
## Viewing saved Data

There is a file called listRecords.php, which accepts either an id number (for single transactions) or not arguments (for a full lists) and renders out all the database records

listRecords.php (or) listRecords.php?id=5 (where 5 is the id of the record).


## Notes
* Paypal sometimes has an issue with the same card number (the sample cards) being used by a high number of developers/sandbox accounts. The code traps the error to alert you if this occurs.

## Bonus question

* How would you handle security for saving credit cards?

The Payment Processors themselves would be responsibilty for the PCI DSS requirements around security, so I would be storing those details that need to be retrieved periodically at the Gateways, using the vault mechanism that both Gateways provide.

All traffic to/from a payment portal should be via SSL, and any items that are saved at the portal level (ie, An app needs to save the last four digits to pass to the gateway for recurring subscriptions and verification) then I would encrypt this data, and save it as a salted hash into the database. 

For extra security the hashes could be generated based on known data (a secret key) and pseudorandom data (a base64 string, a random unix date time).

The database these details are store in, should be locked down, and not accessible from outside the appserver(s).
