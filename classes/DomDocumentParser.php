<?php

class DomDocumentParser {
    private $_doc;

    /**
     * DomDocumentParser constructor.
     * @param string $url
     */
    public function __construct(string $url) {
        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => "User-Agent: voogleBot/0.1\n"
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