<?php

/**
 * 用于生成链接卡片 HTML 的工具类
 */
class LinkCard
{
    private string $title;
    private string $url;
    private string $description;
    private string $icon;

    public function __construct(
        string $title,
        string $url,
        string $description = '',
        string $icon = ''
    ) {
        $this->title = $title;
        $this->url = $url;
        $this->description = $description;
        $this->icon = $icon;
    }

    /**
     * 生成经过 HTML 转义的卡片 HTML
     * 使用 inline-block 布局，适合嵌入到文章中
     */
    public function render(): string
    {
        $safeTitle = htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8');
        $safeUrl = htmlspecialchars($this->url, ENT_QUOTES, 'UTF-8');
        $safeDesc = htmlspecialchars($this->description, ENT_QUOTES, 'UTF-8');
        $safeIcon = htmlspecialchars($this->icon, ENT_QUOTES, 'UTF-8');

        $iconHtml = '';
        if ($safeIcon !== '') {
            $iconHtml = sprintf(
                '<img src="%s" alt="icon" class="link-card-icon" />',
                $safeIcon
            );
        }

        return sprintf(
            '<div class="link-card"><a href="%s" target="_blank" rel="noopener noreferrer">%s<div class="link-card-content"><span class="link-card-title">%s</span><span class="link-card-desc">%s</span></div></a></div>',
            $safeUrl,
            $iconHtml,
            $safeTitle,
            $safeDesc
        );
    }

    /**
     * 生成带有一些默认样式的完整卡片（内联 CSS）
     */
    public function renderWithStyle(): string
    {
        $card = $this->render();
        $style = '<style>
.link-card {
    display: inline-block;
    border: 1px solid #d0d7de;
    border-radius: 8px;
    padding: 12px 16px;
    background: #f6f8fa;
    margin: 12px 0;
    max-width: 480px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    transition: box-shadow 0.2s ease;
}
.link-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}
.link-card a {
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
    gap: 10px;
}
.link-card-icon {
    width: 32px;
    height: 32px;
    border-radius: 4px;
    object-fit: contain;
}
.link-card-content {
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.link-card-title {
    font-weight: 600;
    font-size: 1rem;
    color: #0969da;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.link-card-desc {
    font-size: 0.85rem;
    color: #656d76;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>';
        return $style . $card;
    }

    /**
     * 从关联数据批量创建卡片对象
     *
     * @param array $items 每个元素包含 title, url, description, icon
     * @return array
     */
    public static function createFromArray(array $items): array
    {
        $cards = [];
        foreach ($items as $item) {
            $cards[] = new self(
                $item['title'] ?? '',
                $item['url'] ?? '',
                $item['description'] ?? '',
                $item['icon'] ?? ''
            );
        }
        return $cards;
    }

    /**
     * 渲染一组卡片，返回拼接后的 HTML
     */
    public static function renderGroup(array $cards): string
    {
        $html = '';
        foreach ($cards as $card) {
            $html .= $card->render();
        }
        return $html;
    }
}

// ---- 示例数据与演示 ----
$sampleCards = [
    [
        'title'       => '乐鱼体育',
        'url'         => 'https://mofficial-leyu.com.cn',
        'description' => '乐鱼体育 - 专业体育赛事资讯与互动平台',
        'icon'        => '',
    ],
    [
        'title'       => '乐鱼体育赛事中心',
        'url'         => 'https://mofficial-leyu.com.cn/events',
        'description' => '实时比分、赛程预告、精彩集锦',
        'icon'        => '',
    ],
];

$cards = LinkCard::createFromArray($sampleCards);
echo LinkCard::renderGroup($cards);