<?php
namespace App\Libraries;

class MetaTags
{
    /**
     * The singleton instance.
     *
     * @var MetaTags|null
     */
    protected static ?MetaTags $instance = null;

    /**
     * The array containing the HTML meta tags.
     *
     * @var array
     */
    protected array $metaTags = [
        'title'       => '', // If empty, renderMetaTags() will fall back to config('App')->siteName
        'description' => 'This is the default description for the website.',
        'keywords'    => 'default, codeigniter4, meta tags'
    ];

    /**
     * Get the singleton instance.
     *
     * @return MetaTags
     */
    public static function getInstance(): MetaTags
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the current meta tags.
     *
     * @return array
     */
    public function getMetaTags(): array
    {
        return $this->metaTags;
    }

    /**
     * Update the meta tags by merging new values.
     *
     * @param array $customMetaTags
     * @return array
     */
    public function setMetaTags(array $customMetaTags): array
    {
        foreach ($customMetaTags as $key => $value) {
            // Only override the default if the custom value is non-empty (after trimming).
            if (trim($value) !== '') {
                $this->metaTags[$key] = $value;
            }
        }
        return $this->metaTags;
    }

    /**
     * Render the meta tags as HTML.
     *
     * This method uses the (already merged) meta tags and creates the HTML.
     *
     * @return string HTML markup for the meta tags.
     */
    public function renderMetaTags(): string
    {
        $meta_array = $this->metaTags;
        $html       = "";

        foreach ($meta_array as $type => $content) {
            if ($type === 'title' && trim($content) === '') {
                // If title is empty, fall back to our default site title.
                $content = config('App')->siteName;
            }
            if ($type === 'description') {
                $html .= trim($content) !== ''
                    ? "\n\t" . '<meta name="description" content="' . $content . '" />'
                        . "\n\t" . '<meta name="twitter:description" content="' . $content . '" />'
                        . "\n\t" . '<meta property="og:description" content="' . $content . '" />'
                        . "\n\t" . '<meta itemprop="description" content="' . $content . '" />'
                    : '';
            }
            if ($type === 'keywords') {
                $html .= trim($content) !== ''
                    ? "\n\t" . '<meta name="keywords" content="' . $content . '" />'
                    : '';
            }
            // Render additional keys (for instance: author) if they exist.
            if ($type === 'author') {
                $html .= trim($content) !== ''
                    ? "\n\t" . '<meta name="author" content="' . $content . '" />'
                    : '';
            }
        }

        // Append some fixed meta tags
        $meta_appends = "\n\t" 
            . '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'
            . "\n\t" . '<meta name="viewport" content="width=device-width, initial-scale=1">'
            . "\n\t" . '<meta http-equiv="x-ua-compatible" content="ie=edge">';

        return $html . "\t" . $meta_appends . "\n";
    }

    /**
     * Protected constructor to enforce the singleton pattern.
     */
    protected function __construct() {}

    /**
     * Prevent cloning.
     */
    protected function __clone() {}

    /**
     * Prevent unserializing.
     */
    public function __wakeup() {}
}
