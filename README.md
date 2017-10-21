# Panda Json API Responses (JAR) Package

This is the Panda Json API Responses Package.

[![StyleCI](https://styleci.io/repos/61446268/shield)](https://styleci.io/repos/61446268)

This package is able to generate json responses in a structural way, including headers and content. 
Provides a new method of building json responses with pre-defined models that can easily be parsed on the client side using javascript.

## Installation

This package is part of the [Panda Framework](https://github.com/PandaPlatform/framework) but it's also available as a single package.

### Through the composer

Add the following line to your `composer.json` file:

```
"panda/jar": "^3.0"
```

## Usage

Using simple Json Content:
```php
use \Panda\Jar\Http\Response;
use \Panda\Jar\Model\Content\JsonContent;

// Create a new response
$response = new Response();

// Add Json Content
$content = (new JsonContent())->setPayload('json_payload');
$response->addResponseContent($content, 'response_content_key');
```

The above output would be something like this:
```json
{
  "headers": {},
  "content": {
    "response_content_key": {
      "type": "json",
      "payload": "json_payload"
    }
  }
}
```

Adding html to async responses:
```php
use \Panda\Jar\Http\Response;
use \Panda\Jar\Model\Content\HtmlContent;

// Create a new response
$response = new Response();

// Create DOMElement to add to the response
// The DOMElement must be assigned to a DOMDocument for parsing
$document = new DOMDocument();
$element = new DOMElement('div', 'value');
$document->appendChild($element);
$element->setAttribute('class', 'test');

// Set DOMElement payload
$htmlContent = (new HtmlContent())
    ->setMethod(HtmlContent::METHOD_APPEND)
    ->setHolder('html_holder')
    ->setPayload('html_payload')
    ->setDOMElementPayload($element);
    
// Add content to response
$this->response->addResponseContent($htmlContent, 'b_content');
```

The above output would be something like this:
```json
{
  "headers": {},
  "content": {
    "html_content": {
      "method": "append",
      "holder": "html_holder",
      "type": "html",
      "payload": "<div class=\"test\">value</div>"
    }
  }
}
```

#### Distinguishing response content

The client side should be able to distinguish the content in the response from the type attribute. Each content should
have a different type value to separate the behavior of the client's javascript code.
