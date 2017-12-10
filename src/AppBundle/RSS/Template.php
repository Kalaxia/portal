<?php

    namespace AppBundle\RSS;

    /**
      * Template class
      * Base class for RSS template, need to be used with RSSParser
      * @package RSS
      */
    class Template {
        /**
          * @static var $ITEM_TAG: default item tag
          */
        static $ITEM_TAG = '';

        /**
          * @static var $LINK_TAG: default link tag
          */
        static $LINK_TAG = '';

        /**
          * @static var $TITLE: default tittle tag
          */
        static $TITLE_TAG = '';

        /**
          * @static var $DESCRIPTION_TAG: default description tag
          */
        static $DESCRIPTION_TAG = '';

        /**
          * @static var $DATE_TAG: default date tag
          */
        static $DATE_TAG = '';

        /**
          * @static var $LANGUAGE_TAG: default language tag
          */
        static $LANGUAGE_TAG = '';
    }

    /**
      * DefaultTemplate class (inherit from Template)
      * Common RSS template used by website
      */
    class DTemplate extends Template {
        static $ITEM_TAG = "item";
        static $LINK_TAG = "link";
        static $TITLE_TAG = "title";
        static $DESCRIPTION_TAG = "description";
        static $DATE_TAG = "pubDate";
        static $LANGUAGE_TAG = "language";
    }

?>
