<?php
namespace Opencart\Catalog\Model\Extension\PsSeoPack\Module;
/**
 * Class PsSeoPack
 *
 * @package Opencart\Catalog\Model\Extension\PsSeoPack\Module
 */
class PsSeoPack extends \Opencart\System\Engine\Model
{
    /**
     * @param array $args
     *
     * @return array
     */
    public function replaceHeaderViews(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<body>',
            'replace' => <<<HTML
<body>
<script type="application/ld+json">{{ ps_seo_pack_ld_json }}</script>
HTML
        ];

        $views[] = [
            'search' => '<html',
            'replace' => '<html{% if ps_seo_pack_html_prefix %} prefix="{{ ps_seo_pack_html_prefix }}"{% endif %}'
        ];

        $views[] = [
            'search' => '</title>',
            'replace' => <<<HTML
</title>
{% for twitter in ps_seo_pack_twitters %}
<meta name="{{ twitter.name }}" content="{{ twitter.content }}">
{% endfor %}
HTML
        ];

        $views[] = [
            'search' => '</title>',
            'replace' => <<<HTML
</title>
{% for opengraph in ps_seo_pack_opengraphs %}
<meta property="{{ opengraph.property }}" content="{{ opengraph.content }}">
{% endfor %}
HTML
        ];

        $views[] = [
            'search' => '</title>',
            'replace' => <<<HTML
</title>
{% for name, content in ps_seo_pack_dublincores %}
<meta name="{{ name }}" content="{{ content }}">
{% endfor %}
HTML
        ];

        $views[] = [
            'search' => '</title>',
            'replace' => <<<HTML
</title>
{% for rel, href in ps_seo_pack_links %}
<link rel="{{ rel }}" href="{{ href }}">
{% endfor %}
HTML
        ];

        $views[] = [
            'search' => '</title>',
            'replace' => <<<HTML
</title>
{% for language_link in ps_seo_pack_language_links %}
<link rel="alternate" hreflang="{{ language_link.hreflang }}" href="{{ language_link.href }}">
{% endfor %}
HTML
        ];

        return $views;
    }

    /**
     * @param int $length_class_id
     *
     * @return array
     */
    public function getLengthClass(int $length_class_id): array
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "length_class` lc LEFT JOIN `" . DB_PREFIX . "length_class_description` lcd ON (lc.`length_class_id` = lcd.`length_class_id`) WHERE lc.`length_class_id` = '" . (int) $length_class_id . "' AND lcd.`language_id` = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row;
    }

    /**
     * @param int $weight_class_id
     *
     * @return array
     */
    public function getWeightClass(int $weight_class_id): array
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "weight_class` wc LEFT JOIN `" . DB_PREFIX . "weight_class_description` wcd ON (wc.`weight_class_id` = wcd.`weight_class_id`) WHERE wc.`weight_class_id` = '" . (int) $weight_class_id . "' AND wcd.`language_id` = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row;
    }

    /**
     * @param int $product_id
     *
     * @return mixed
     */
    public function getSpecialPriceDatesByProductId(int $product_id): mixed
    {
        if (version_compare(VERSION, '4.1.0.0', '>=')) {
            $query = $this->db->query("SELECT `date_end` FROM `" . DB_PREFIX . "product_discount` WHERE `product_id` = '" . (int) $product_id . "' AND `customer_group_id` = '" . (int) $this->config->get('config_customer_group_id') . "' AND `quantity` = '1' AND `special` = '1' AND ((`date_start` = '0000-00-00' OR `date_start` < NOW()) AND (`date_end` = '0000-00-00' OR `date_end` > NOW())) ORDER BY `priority` ASC, `price` ASC LIMIT 1");
        } else {
            $query = $this->db->query("SELECT `date_end` FROM `" . DB_PREFIX . "product_special` WHERE `product_id` = '" . (int) $product_id . "' AND `customer_group_id` = '" . (int) $this->config->get('config_customer_group_id') . "' AND ((`date_start` = '0000-00-00' OR `date_start` < NOW()) AND (`date_end` = '0000-00-00' OR `date_end` > NOW()))");
        }


        if ($query->num_rows) {
            return $query->row['date_end'];
        }

        return false;
    }
}
