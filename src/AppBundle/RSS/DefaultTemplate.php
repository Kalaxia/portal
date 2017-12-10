<?php

    namespace AppBundle\RSS;

    /**
      * DefaultTemplate class (inherit from Template)
      * Common RSS template used by website
      */
    class DefaultTemplate extends Template {
        static $ITEM_TAG = "item";
        static $LINK_TAG = "link";
        static $TITLE_TAG = "title";
        static $DESCRIPTION_TAG = "description";
        static $DATE_TAG = "pubDate";
        static $LANGUAGE_TAG = "language";
    }

?>
