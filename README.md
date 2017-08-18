# BitcoinTransactionChecker
This is a PHP Bitcoin Transaction Checker. It is very useful for checking transaction in real time and see if they are valid and safe or not. It uses BlockCypher LIVE Block Explorer (https://live.blockcypher.com/) to get transaction details and show you in realtime mode, advanced data and more.

# How does it works?
First, the user gives to the software the TxID via Get, for example my transaction ID is 62f1085c06aa841bcca8c3ade28c64429c83204a56bf072cd3ed76e22c1495be and my software is at http://ramondettidavide.com/software_test/bitcoin_transaction_checker.php, so the link to see that transaction is http://ramondettidavide.com/software_test/bitcoin_transaction_checker.php?TxID=62f1085c06aa841bcca8c3ade28c64429c83204a56bf072cd3ed76e22c1495be .

If you open that link you receive ALL the informations about that transaction!

# Requirements
- A Linux or OS X server
- Allowed file_get_contents() function
