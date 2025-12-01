<?php
/**
 * Helper class to extract the article content from the HTML
 * string of an article galley and generate a table of
 * contents.
 *
 * It replaces URLs for dependent files referenced in the
 * extracted HTML code. However, it ignores linked assets
 * such as CSS files, as well as URLs within CSS code,
 * such as background-image: url(...).
 *
 * This class is tailored to a sample HTML file that looks
 * for the article content in a <article> tag and the
 * references in a <aside> tag. To adapt this class to other
 * HTML structures, you can create a child class and override
 * the following methods:
 *
 * self::getArticleContent()
 * self::getReferencesContent()
 *
 * The self::getTableOfContents() method accepts arguments
 * to customize what <h*> levels are included in the table.
 * If your HTML does not use <h*> tags to define headers,
 * you will also need to override this method to get the
 * table of contents.
 */
namespace APP\plugins\themes\individualizeTheme\classes;

class FullText
{
    public function __construct(
        /**
         * HTML string
         *
         * The article content and references will be extracted from
         * this string and a table of contents will be generated
         * based on the <h*> elements.
         *
         * The article text should be within a plain <article> element
         * without attributes and the references should be in a plain
         * <aside> element without attributes.
         */
        protected string $html,

        /**
         * Full-text HTML galley
         *
         * Images referenced in the HTML file must be uploaded as
         * dependent files to this galley.
         */
        protected ArticleGalley $galley,

        /**
         * Article that this galley represents
         */
        protected Submission $submission,
    ) {
        //
    }

    /**
     * Get the <article> from the HTML string
     *
     * Replaces embedded file URLs and extracts the
     * <article> content from the HTML string.
     */
    public function getArticleContent(): string
    {
        $article = '';
        preg_match('/<article>(.*)<\/article>/is', $this->html, $matches);
        if ($matches) {
            $article = $matches[0];
        }

        $dependentFiles = $this->getDependentFiles();
        return $this->replaceDependentFileUrls($article, $dependentFiles);
    }

    /**
     * Get the <aside> from the HTML string
     */
    public function getReferencesContent(): string
    {
        preg_match('/<aside>(.*)<\/aside>/is', $this->html, $matches);
        if ($matches) {
            return $matches[0];
        }
        return '';
    }

    /**
     * Get a table of contents from the content
     *
     * The `$startLevel` and `$depth` determine what headings
     * are included in the table of contents. For example,
     * $startLevel = 3 and $depth = 2 would result in a table
     * of contents that includes <h3> and <h4> headings only.
     *
     * @param string $article The HTML content to extract <h*> from
     * @param bool $includeReferences Whether or not to include an item for references
     * @param int $startLevel What <h*> level the table should start at. Default: 3
     * @param int $depth How many levels of nested sections should be displayed. Default: 1
     * @return array List of table of contents entries
     */
    public function getTableOfContents(string $article, bool $includeReferences, int $startLevel = 3, int $depth = 4): array
    {
        preg_match_all('/<h[0-9][^>]*>([\s\S]*?)<\/h[0-9]>/is', $article, $headings);

        if (!$headings || empty($headings[0])) {
            return [];
        }

        $table = [];
        $endLevel = $startLevel + $depth - 1;

        foreach ($headings[0] as $i => $heading) {
            preg_match('/<h([0-9])[^>]*id="([^"]*)"[^>]*>/is', $heading, $matches);

            if (count($matches) < 3) {
                continue;
            }

            $level = (int) $matches[1];

            if ($level < $startLevel || $level > $endLevel) {
                continue;
            }

            /**
             * Remove links from heading content
             *
             * Headings become <a> elements in the table of contents,
             * so they can not contain links themselves.
             *
             * We expect a link in heading content to be a footnote,
             * which we don't need to display in tables of content.
             */
            $content = preg_replace('/<a[^>]*>[\s\S]*?<\/a>/is', '', $headings[1][$i]);

            $table[] = [
                'id' => $matches[2],
                'level' => $level,
                'content' => $content,
            ];
        }

        if ($includeReferences) {
            $table[] = [
                'id' => 'individualize-references',
                'level' => $startLevel,
                'content' => __('submission.citations'),
            ];
        }

        return $table;
    }

    /**
     * Get the dependent files of a submission file
     *
     * @return SubmissionFiles[]
     */
    protected function getDependentFiles(): array
    {
        return iterator_to_array(
            Services::get('submissionFile')->getMany([
                'assocTypes' => [ASSOC_TYPE_SUBMISSION_FILE],
                'assocIds' => [$this->galley->getFile()->getId()],
                'fileStages' => [SUBMISSION_FILE_DEPENDENT],
                'includeDependentFiles' => true,
            ])
        );
    }

    /**
     * Get the URL to a dependent file in a galley
     */
    protected function getDependentFileUrl(SubmissionFile $file, Submission $submission, ArticleGalley $galley): string
    {
        return Application::get()->getRequest()->url(
            null,
            'article',
            'download',
            [
                $submission->getBestId(),
                'version',
                $galley->getData('publicationId'),
                $galley->getBestGalleyId(),
                $file->getId(),
                $file->getLocalizedData('name')
            ],
        );
    }

    /**
     * Replace URLs for dependent files in the HTML content
     *
     * This replaces images and other references to dependent files
     * in the HTML content.
     *
     * Example:
     *
     * <img src="my-example-image.jpg">
     *
     * Will be replaced by a fully-resolved URL to the image, similar to this:
     *
     * <img src="https://base-url.com/journal-name/article/download/1/version/2/3/4/my-example-image.jpg">
     *
     * The regex used in this method is a duplicate of the one used by
     * the HtmlArticleGalleyPlugin.
     *
     * @param SubmissionFile[] $files
     */
    protected function replaceDependentFileUrls(string $article, array $files): string
    {
        foreach ($files as $file) {
            $url = $this->getDependentFileUrl($file, $this->submission, $this->galley);
            $pattern = preg_quote(rawurlencode($file->getLocalizedData('name')));
            $article = preg_replace(
                '/([Ss][Rr][Cc]|[Hh][Rr][Ee][Ff]|[Dd][Aa][Tt][Aa])\s*=\s*"([^"]*' . $pattern . ')"/',
                '\1="' . $url . '"',
                $article
            );
        }

        return $article;
    }
}