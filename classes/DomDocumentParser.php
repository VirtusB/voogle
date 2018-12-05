<?php

class DomDocumentParser {
    private $_doc;

    /**
     * DomDocumentParser constructor.
     * @param string $url
     */
    public function __construct(string $url) {
        $header= "User-Agent: voogleBot/1.0\r\n";
        $header.= "Accept-Language: en-GB,en;q=0.5\r\n";

        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => $header
            )
        );

        $context = stream_context_create($options);

        $this->_doc = new DOMDocument();
        @$this->_doc->loadHTML(file_get_contents($url, false, $context));
    }

    /**
     * @return DOMNodeList
     */
    public function getLinks(): \DOMNodeList {
        return $this->_doc->getElementsByTagName('a');
    }

    /**
     * @return DOMNodeList
     */
    public function getTitleTags(): \DOMNodeList {
        return $this->_doc->getElementsByTagName('title');
    }

    /**
     * @return DOMNodeList
     */
    public function getMetaTags(): \DOMNodeList {
        return $this->_doc->getElementsByTagName('meta');
    }

    /**
     * @return DOMNodeList
     */
    public function getImages(): \DOMNodeList {
        return $this->_doc->getElementsByTagName('img');
    }
}