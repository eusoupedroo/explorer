<?php 

class DomDocumentParser {

    private $doc;
    public function __construct($url) {

        $options = array(
            'http'=>array('method'=>"GET", 'header'=>"User-Agent: doodleBot/0.1\n")
        );

        // The http key is used to define an array of options for the HTTP request. Here, the options include two key-value pairs: method and header.
        // The method key specifies the HTTP method to use for the request, which is GET in this case. GET is used when the client wants to retrieve information from the server, like a web page or a resource.
        // The header key specifies the request headers that are sent to the server along with the request. In this case, the header specifies the user agent for the request, which is doodleBot/0.1. The user agent is a string that identifies the client making the request. Many websites use the user agent to determine the type of device or software making the request, and may serve different content depending on the user agent.
        // The \n character at the end of the header string is a newline character, which is used to indicate the end of the header.
        
        $context = stream_context_create($options);

        $this->doc = new DomDocument();
        @$this->doc->loadHTML(file_get_contents($url, false, $context));
    }

    public function getLinks(){
        return $this->doc->getElementsByTagName("a");
    }

    public function getTitleTags(){
        return $this->doc->getElementsByTagName("title");
    }

    public function getMetaTags(){
        return $this->doc->getElementsByTagName("meta");
    }

    public function getImages(){
        return $this->doc->getElementsByTagName("img");
    }


}
?> 