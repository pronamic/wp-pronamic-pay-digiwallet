# Hooks

- [Actions](#actions)
- [Filters](#filters)

## Actions

*This project does not contain any WordPress actions.*

## Filters

### `pronamic_pay_digiwallet_report_url`

*Filters the DigiWallet report URL.*

If you want to debug the DigiWallet report URL you can use this filter
to override the report URL. You could for example use a service like
https://webhook.site/ to inspect the report requests from DigiWallet.

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$report_url` | `string` | DigiWallet report URL.

Source: [src/Gateway.php](../src/Gateway.php), [line 114](../src/Gateway.php#L114-L123)


<p align="center"><a href="https://github.com/pronamic/wp-documentor"><img src="https://cdn.jsdelivr.net/gh/pronamic/wp-documentor@main/logos/pronamic-wp-documentor.svgo-min.svg" alt="Pronamic WordPress Documentor" width="32" height="32"></a><br><em>Generated by <a href="https://github.com/pronamic/wp-documentor">Pronamic WordPress Documentor</a> <code>1.1.0</code></em><p>
