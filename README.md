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

## Target Audience

This library was created to facilitate the development of web applications where the ui is generated on the backend.
This way we can freely build the ui using any library we need and simply push it to the frontend through the `jar` library.

The library provides the flexibility to allow the Javascript client to be dummy and have a standard response handler for all the cases.
This way, we are free to determine how the content will be handled from the backend without writing any Javascript code.

The only way where we need to write some Javascript will be to handle backend-generated events towards the frontend.
In this case, we have to build the frontend client to listen for specific events and include a callback. 

## Content Models

The jar package support a set of models that can be either `JsonContent` or `XmlContent`, which supports specific xml parsers.

Based on the needs of each application, you can use a content model that suits best for your occasion.

### JsonContent

JsonContent should be used when we want to deliver simple json string to the client.
The client should know how to parse the content accordingly.

Example:
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

### EventContent (extends JsonContent)

EventContent is a special type of JsonContent that represents an event that should be triggered to the frontend client.
We can use EventContents to trigger a specific event like a redirect or any javascript action.

EventContent has two attributes:
- A name, which will be the event name
- A payload, in json format, which will be the event payload

### XmlContent

XmlContent is a special type of content which supports parsing DOMElements.
The use of the XmlContent model is to transfer xml through json so that the client can handle it accordingly.

### HtmlContent (extends XmlContent)

HtmlContent is a special type of XmlContent which transfers html.
It uses the same parser as the XmlContent to convert html to string and to transfer it to the client.

The HtmlContent has some extra parameters that can be set to facilitate html handling on the client side:
- A **holder**, which is a CSS selector to point out where this html is going to be placed
- A **method**, which will describe how the html will be placed into the holder
  - **Append**: it will append the html in the end of the placeholder contents
  - **Prepend**: it will prepend the html in the beginning of the placeholder contents
  - **Replace**: it will replace the placeholder's html with the given html

Example:
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

You can use any ui library to generate the HtmlContent. If you have access to DOMElement, it will be much easier
Otherwise, you can simply provide the html as string using the `setPayload()` function.

## Distinguishing response content

The client side should be able to distinguish the content in the response from the `type` attribute.
Each content should have a different type value to separate the behavior of the client's javascript code.
