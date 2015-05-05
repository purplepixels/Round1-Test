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
```bahs
$ phpunit --bootstrap bootstrap.php
$ cd tests
$ phpunit --bootstrap bootstrap.php PayPal/Test/Api/CreditCardTest.php
$ phpunit --bootstrap bootstrap.php braintree/unit/CreditCardTest.php
```


## Notes
* Paypal sometimes has an issue with the same card number (the sample cards) being used by a high number of developers/sandbox accounts. The code traps the error to alert you if this occurs.
