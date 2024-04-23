<p align="center">
	<a href="https://www.wp-pay.org/">
		<img src="https://www.wp-pay.org/assets/pronamic-pay.svgo-min.svg" alt="WordPress Pay » Gateway » DigiWallet" width="72" height="72">
	</a>
</p>

> [!WARNING]  
> On February 29, 2024, CEO Paul H.J. van Rooij announced that the payment services TargetMedia-TargetPay-DigiWallet will be terminated as of April 29, 2024.

<h1 align="center">WordPress Pay » Gateway » DigiWallet</h3>

<p align="center">
	DigiWallet (formerly TargetPay) driver for the WordPress payment processing library.
</p>

## Table of contents

- [WordPress Filters](#wordpress-filters)
- [Simulate Requests](#simulate-requests)
- [Links](#links)

## WordPress Filters

View [docs/hooks.md](docs/hooks.md) for the available WordPres filters in this library.

## Simulate Requests

### Report

```
curl --request POST "https://example.com/wp-json/pronamic-pay/digiwallet/v1/report" \
	--user-agent "GuzzleHttp/6.3.3 curl/7.56.1 PHP/7.2.1" \
	--data-raw "trxid=187314412&idealtrxid=0012345678901234&amount=54&status=000000+OK&rtlo=156099&cname=Digiwallet+Test&cbank=NL91ABNA0516372602&cbic=ABNANL2A"
```

## Links

- https://github.com/DigiWallet/transaction-sdk
- https://webhook.site/
- https://hookbin.com/
- https://requestbin.net/
