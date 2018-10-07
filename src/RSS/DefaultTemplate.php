<?php

namespace App\RSS;

class DefaultTemplate extends Template
{
    static $ITEM_TAG = "item";
    static $LINK_TAG = "link";
    static $TITLE_TAG = "title";
    static $DESCRIPTION_TAG = "description";
    static $DATE_TAG = "pubDate";
    static $LANGUAGE_TAG = "language";
}