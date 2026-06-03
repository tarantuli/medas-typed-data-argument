# medas-typed-data-arguments

Part of the [Medas framework](https://github.com/tarantuli/medas-core).

## Description

Provides a `#[TypedData]` attribute that instructs the DI container to hydrate a plain object from an array argument rather than injecting the array directly. When `TypedDataArgumentProcessor` encounters a parameter annotated with `#[TypedData]`, it instantiates the declared class and assigns each key-value pair from the array to the corresponding property on the object.

This is useful when a route parameter resolver or body data resolver produces an associative array, but the consuming service expects a typed DTO. `#[TypedData]` bridges the gap without requiring a manual cast or a custom parameter resolver.

`TypedDataArgumentProcessor` runs at priority −50 (after most resolvers have produced their values, but before the value is used). It only activates on parameters that carry the `#[TypedData]` attribute; all others are passed through unchanged.

Note — property assignment uses direct dynamic property access (`$object->$property = $value`) rather than `ReflectionProperty::setValue()`, so strict type enforcement is not applied during hydration.

## Usage

### Package developer context

Register the package — it registers `TypedDataArgumentProcessor` automatically:

```php
use Medas\TypedDataArguments\TypedDataArgumentsPackage;

TypedDataArgumentsPackage::instance();
```

**Defining a DTO:**

```php
class CreateInvoiceData
{
    public int    $customerId;
    public string $currency = 'EUR';
    public int    $amountCents;
    public bool   $draft = false;
}
```

**Using `#[TypedData]` in a request handler:**

```php
use Medas\TypedDataArguments\TypedData;
use Medas\HttpRequestHandler\Attributes\BodyArgument;
use Medas\Core\Interfaces\HttpRequestHandler;
use Medas\HttpRequestHandler\ResponseTypes\Response;
use Medas\Core\Attributes\Service;

#[Service]
readonly class CreateInvoiceHandler implements HttpRequestHandler
{
    public function __construct(
        private InvoiceService $invoiceService,

        // The body is resolved as an array by BodyDataResolver;
        // TypedDataArgumentProcessor then hydrates it into CreateInvoiceData
        #[TypedData]
        private CreateInvoiceData $data,
    ) {}

    public function handle(string $method, string $endpoint): Response
    {
        $invoice = $this->invoiceService->create(
            customerId: $this->data->customerId,
            amountCents: $this->data->amountCents,
            currency: $this->data->currency,
            draft: $this->data->draft,
        );

        return new InvoiceCreatedResponse($invoice);
    }
}
```

The incoming JSON body:
```json
{"customerId": 42, "amountCents": 9900, "draft": true}
```
is resolved to an array by the existing body resolver and then hydrated into a `CreateInvoiceData` instance by `TypedDataArgumentProcessor`.

**Standalone usage — hydrating an object from an array manually:**

```php
use Medas\TypedDataArguments\TypedDataArgumentProcessor;
use Medas\Core\Attributes\Service;

#[Service]
readonly class DataMapper
{
    public function __construct(
        private TypedDataArgumentProcessor $processor,
    ) {}

    public function map(array $data, string $class): object
    {
        $object = new $class();

        foreach ($data as $property => $value) {
            $object->$property = $value;
        }

        return $object;
    }
}
```

The processor is most naturally used via the DI container's argument processing pipeline — the above pattern is just a manual equivalent for cases where DI is not involved.

### Backend user context

This package has no CLI commands and no configuration options. It is wired in automatically once registered.

**Type coercion** — property assignment is done via dynamic access (`$object->$property = $value`), not via reflection with type enforcement. If the incoming array contains a string `"42"` for an `int $customerId` property, PHP will coerce it silently in non-strict-types contexts, but will throw a `TypeError` in strict-types contexts. Validate or cast incoming data before relying on `#[TypedData]` hydration when strict type safety is required.

**Unknown properties** — if the array contains a key that has no matching property on the target class, PHP will create a dynamic property (or throw an error if the class uses `#[AllowDynamicProperties]` is absent on PHP 8.2+). Define only the properties you expect on the DTO class.
