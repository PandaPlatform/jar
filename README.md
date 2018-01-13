# Panda Json API Responses (JAR) Package

This is the Panda Json API Responses Package.

[![StyleCI](https://styleci.io/repos/61446268/shield)](https://styleci.io/repos/61446268)
[![Latest Stable Version](https://poser.pugx.org/panda/jar/v/stable?format=flat-square)](https://packagist.org/packages/panda/jar)
[![Total Downloads](https://poser.pugx.org/panda/jar/downloads?format=flat-square)](https://packagist.org/packages/panda/jar)
[![License](https://poser.pugx.org/panda/jar/license?format=flat-square)](https://packagist.org/packages/panda/jar)

- [Introduction](#introduction)
- [Installation](#installation)
  - [Through the composer](#through-the-composer)
- [Target Audience](#target-audience)
- [Content Models](#content-models)
  - [JsonContent](#jsoncontent)
  - [EventContent](#eventcontent)
  - [XmlContent](#xmlcontent)
  - [HtmlContent](#htmlcontent)
- [Distinguishing response content](#distinguishing-response-content)

## Introduction

The base response object consists of a list of headers and a list of content objects.
The AsyncResponse base object offers an interface for adding headers and content. 

However, it does not generate the response output.
The output can be json (which is also the purpose of this component) but can also be something else, like xml.

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

### EventContent

EventContent is a special type of JsonContent that represents an event that should be triggered to the frontend client.
We can use EventContents to trigger a specific event like a redirect or any javascript action.

EventContent has two attributes:
- A name, which will be the event name
- A payload, in json format, which will be the event payload

Example:
```php
use \Panda\Jar\Http\Response;
use \Panda\Jar\Model\Content\EventContent;

// Create a new response
$response = new Response();

// Add an page reload event
$content = (new EventContent())->setName('window.reload');
$response->addResponseContent($content);
```

The above output would be something like this:
```json
{
  "headers": {},
  "content": {
    "0": {
      "type": "event",
      "payload": {
        "name": "window.reload",
        "value": ""
      }
    }
  }
}
```

You can catch the above event using `jQuery` like this:
```javascript
$(document).on('window.reload', function(ev) {
    location.reload();
});
```

Or you can use an EventContent with a value and use the value as attribute to the event:
```php
use \Panda\Jar\Http\Response;
use \Panda\Jar\Model\Content\EventContent;

// Create a new response
$response = new Response();

// Add a redirect event with target url
$content = (new EventContent())
    ->setName('window.redirect')
    ->setValue('https://pandaphp.org');
$response->addResponseContent($content);
```

The above output would be something like this:
```json
{
  "headers": {},
  "content": {
    "0": {
      "type": "event",
      "payload": {
        "name": "window.redirect",
        "value": "https://pandaphp.org"
      }
    }
  }
}
```

You can catch the above event using `jQuery` like this:
```javascript
$(document).on('window.redirect', function(ev, value) {
    window.location = value;
});
```

### XmlContent

XmlContent is a special type of content which supports parsing DOMElements.
The use of the XmlContent model is to transfer xml through json so that the client can handle it accordingly.

### HtmlContent

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
    ->setHolder('.holder_class')
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
      "holder": ".holder_class",
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
