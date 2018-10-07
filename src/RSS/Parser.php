<?php

namespace App\RSS;

class Parser
{
    /** @var Template **/
    protected $template;
    /** @var array **/
    public $items = [];

    public function __construct(DefaultTemplate $template)
    {
        $this->template = $template;
    }

    /**
      * Feed method: parse rss from link and store result in class attribute $items
      */
    public function feed(string $link)
    {
        $dom = new \DOMDocument();
        $dom->load($link);

        $items = $dom->getElementsByTagName($this->template::$ITEM_TAG);

        foreach ($items as $item) {
            $sub_dom = new \DOMDocument();
            $sub_dom->appendChild($sub_dom->importNode($item, true));

            array_push($this->items, array(
                'link' => self::getElementContent($sub_dom, $this->template::$LINK_TAG),
                'title' => self::getElementContent($sub_dom, $this->template::$TITLE_TAG),
                'description' => self::getElementContent($sub_dom, $this->template::$DESCRIPTION_TAG),
                'date' => self::getElementContent($sub_dom, $this->template::$DATE_TAG),
                'language' => self::getElementContent($sub_dom, $this->template::$LANGUAGE_TAG),
            ));
        }
    }

    /**
     * Static method getElementContent: proper way to get element content (avoid notice)
     * @param DOMDocument $dom: instance of DOM object target
     * @param string $tag: target tag
     */
    static function getElementContent(\DOMDocument $dom, string $tag)
    {
        if (!is_object($dom->getElementsByTagName($tag)->item(0))) {
            return "";
        } else {
            return $dom->getElementsByTagName($tag)->item(0)->nodeValue;
        }
    }
}