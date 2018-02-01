<?php

namespace Test\PageObject;

use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;

class ArticleForm
{
    private $titleId = 'frm-articleForm-title';
    private $metaId = 'frm-articleForm-meta_title';
    private $metaDescriptionId = 'frm-articleForm-meta_description';
    private $metaKeywordsId = 'frm-articleForm-meta_keywords';
    private $urlId = 'frm-articleForm-url';

    private $categoryId = 'frm-articleForm-category_id';
    private $tagId = 'frm-articleForm-tag_id';
    private $pictureId = 'frm-articleForm-picture_id';
    private $pictureBlogId = 'frm-articleForm-picture_blog_id';

    private $descriptionId = 'frm-articleForm-description';
    private $contentId = 'frm-articleForm-content';
    private $mostReadedId = 'frm-articleForm-most_readed';
    private $publishedId = 'frm-articleForm-published';

    private $notPublishDetail = 'frm-articleForm-dont_publish_detail';
    private $submitId = 'submit';

    /** @var WebDriver */
    private $driver;

    public function __construct(WebDriver $driver)
    {
        $this->driver = $driver;
    }

    public function fillForm(
        $title,
        $meta,
        $metaDescription,
        $metaKeywords,
        $url,
        $categoryId,
        $tagIds,
        $picture,
        $pictureBlog,
        $description,
        $content,
        $mostReaded,
        $published,
        $notPublished,
        $submit = false)
    {
        $this->driver->findElement(WebDriverBy::id($this->titleId))->sendKeys($title);
        $this->driver->findElement(WebDriverBy::id($this->metaId))->sendKeys($meta);
        $this->driver->findElement(WebDriverBy::id($this->metaDescriptionId))->sendKeys($metaDescription);
        $this->driver->findElement(WebDriverBy::id($this->metaKeywordsId))->sendKeys($metaKeywords);
        $this->driver->findElement(WebDriverBy::id($this->urlId))->sendKeys($url);

        $this->driver->findElement(WebDriverBy::id($this->categoryId))->sendKeys($categoryId);
        $this->driver->findElement(WebDriverBy::id($this->tagId))->sendKeys($tagIds);
        $this->driver->findElement(WebDriverBy::id($this->pictureId))->sendKeys($picture);
        $this->driver->findElement(WebDriverBy::id($this->pictureBlogId))->sendKeys($pictureBlog);
        $this->driver->findElement(WebDriverBy::id($this->descriptionId))->sendKeys($description);

        $this->driver->findElement(WebDriverBy::id($this->contentId))->sendKeys($content);
        $this->driver->findElement(WebDriverBy::id($this->mostReadedId))->sendKeys($mostReaded);
        $this->driver->findElement(WebDriverBy::id($this->publishedId))->sendKeys($published);
        $this->driver->findElement(WebDriverBy::id($this->notPublishDetail))->sendKeys($notPublished);

        if($submit){
            $this->driver->findElement(WebDriverBy::xpath($this->submitId))->click();
        }
    }

}