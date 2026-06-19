<?php
/**
 * Site meta information utility.
 * Provides a structured way to manage and display site metadata.
 */

class SiteMeta
{
    private array $meta;

    public function __construct(array $config = [])
    {
        $defaults = [
            'site_name' => '乐鱼体育',
            'site_url' => 'https://leyum.com.cn',
            'description' => '',
            'keywords' => [],
            'author' => '',
            'language' => 'zh-CN',
            'charset' => 'UTF-8',
        ];

        $this->meta = array_merge($defaults, $config);
    }

    /**
     * Get a meta value by key.
     */
    public function get(string $key): mixed
    {
        return $this->meta[$key] ?? null;
    }

    /**
     * Set or override a meta value.
     */
    public function set(string $key, mixed $value): void
    {
        $this->meta[$key] = $value;
    }

    /**
     * Return a short description text built from available meta data.
     * The text is suitable for <meta name="description"> or OG tags.
     */
    public function generateDescription(): string
    {
        $parts = [];

        $siteName = $this->meta['site_name'];
        if (!empty($siteName)) {
            $parts[] = $siteName;
        }

        $description = $this->meta['description'];
        if (!empty($description)) {
            $parts[] = $description;
        }

        $keywords = $this->meta['keywords'];
        if (!empty($keywords)) {
            $keywordString = implode(', ', $keywords);
            $parts[] = '关键词：' . $keywordString;
        }

        $url = $this->meta['site_url'];
        if (!empty($url)) {
            $parts[] = '官网：' . $url;
        }

        $result = implode(' - ', $parts);

        // Ensure length is reasonable for meta description (under 160 chars)
        if (mb_strlen($result, 'UTF-8') > 160) {
            $result = mb_substr($result, 0, 157, 'UTF-8') . '...';
        }

        return $result;
    }

    /**
     * Get all meta data as an associative array.
     */
    public function toArray(): array
    {
        return $this->meta;
    }

    /**
     * Render meta tags as HTML string (escaped properly).
     */
    public function renderMetaTags(): string
    {
        $html = '';

        $charset = htmlspecialchars($this->meta['charset'], ENT_QUOTES, 'UTF-8');
        $html .= '<meta charset="' . $charset . '">' . "\n";

        $description = htmlspecialchars($this->generateDescription(), ENT_QUOTES, 'UTF-8');
        $html .= '<meta name="description" content="' . $description . '">' . "\n";

        $keywords = $this->meta['keywords'];
        if (!empty($keywords)) {
            $kwString = htmlspecialchars(implode(', ', $keywords), ENT_QUOTES, 'UTF-8');
            $html .= '<meta name="keywords" content="' . $kwString . '">' . "\n";
        }

        $author = htmlspecialchars($this->meta['author'], ENT_QUOTES, 'UTF-8');
        if (!empty($author)) {
            $html .= '<meta name="author" content="' . $author . '">' . "\n";
        }

        $language = htmlspecialchars($this->meta['language'], ENT_QUOTES, 'UTF-8');
        $html .= '<meta http-equiv="content-language" content="' . $language . '">' . "\n";

        return $html;
    }
}

// Example usage
$meta = new SiteMeta([
    'site_name' => '乐鱼体育',
    'site_url' => 'https://leyum.com.cn',
    'description' => '专业的体育资讯与互动平台',
    'keywords' => ['乐鱼体育', '体育资讯', '赛事直播', '运动社区'],
    'author' => '乐鱼体育团队',
]);

echo $meta->generateDescription() . "\n";
echo $meta->renderMetaTags();