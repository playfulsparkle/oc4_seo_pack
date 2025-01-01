<?php
namespace Opencart\Catalog\Controller\Extension\PsSeoPack\Module;
/**
 * Class PsSeoPack
 *
 * @package Opencart\Catalog\Controller\Extension\PsSeoPack\Module
 */
class PsSeoPack extends \Opencart\System\Engine\Controller
{
    /**
     * Event handler for `catalog/view/common/header/before`.
     *
     * @param string $route
     * @param array $args
     * @param string $template
     *
     * @return void
     */
    public function eventCatalogViewCommonHeaderBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('module_ps_seo_pack_status')) {
            return;
        }

        if (isset($this->request->get['route'])) {
            $ps_seo_pack_route = $this->request->get['route'];
        } else {
            $ps_seo_pack_route = 'common/home';
        }

        $this->load->language('extension/ps_seo_pack/module/ps_seo_pack');

        $this->load->model('extension/ps_seo_pack/module/ps_seo_pack');
        $this->load->model('localisation/language');
        $this->load->model('setting/setting');
        $this->load->model('tool/image');

        $languages = $this->model_localisation_language->getLanguages();

        $config_store_id = (int) $this->config->get('config_store_id');
        $config_language_id = (int) $this->config->get('config_language_id');
        $config_language = $this->config->get('config_language');

        if ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) || $_SERVER['SERVER_PORT'] == 443) {
            $protocol = 'https://';
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        $current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $separator = version_compare(VERSION, '4.0.2.0', '>=') ? '.' : '|';

        $config = $this->model_setting_setting->getSetting('module_ps_seo_pack', $config_store_id);

        $sdm_enabled = isset($config['module_ps_seo_pack_sdm']) ? (bool) $config['module_ps_seo_pack_sdm'] : false;
        $dublin_core_enabled = isset($config['module_ps_seo_pack_dublin_core']) ? (bool) $config['module_ps_seo_pack_dublin_core'] : false;

        $open_graph_enabled = isset($config['module_ps_seo_pack_open_graph']) ? (bool) $config['module_ps_seo_pack_open_graph'] : false;
        $facebook_app_id = isset($config['module_ps_seo_pack_facebook_app_id']) ? $config['module_ps_seo_pack_facebook_app_id'] : '';

        $twitter_enabled = isset($config['module_ps_seo_pack_twitter']) ? (bool) $config['module_ps_seo_pack_twitter'] : false;
        $twitter_handle = isset($config['module_ps_seo_pack_twitter_handle']) ? $config['module_ps_seo_pack_twitter_handle'] : '';
        $twitter_card_type = isset($config['module_ps_seo_pack_twitter_card_type']) ? $config['module_ps_seo_pack_twitter_card_type'] : '';

        $sdm_stock_status_enabled = isset($config['module_ps_seo_pack_sdm_stock_status']) ? (bool) $config['module_ps_seo_pack_sdm_stock_status'] : false;
        $sdm_stock_status_assocs = isset($config['module_ps_seo_pack_sdm_stock_status_assoc']) ? (array) $config['module_ps_seo_pack_sdm_stock_status_assoc'] : [];

        $open_graph_stock_status_enabled = isset($config['module_ps_seo_pack_open_graph_stock_status']) ? (bool) $config['module_ps_seo_pack_open_graph_stock_status'] : false;
        $open_graph_stock_status_assocs = isset($config['module_ps_seo_pack_open_graph_stock_status_assoc']) ? (array) $config['module_ps_seo_pack_open_graph_stock_status_assoc'] : [];

        $return_policy_enabled = isset($config['module_ps_seo_pack_return_policy']) ? (bool) $config['module_ps_seo_pack_return_policy'] : false;
        $return_policies = isset($config['module_ps_seo_pack_return_policies']) ? (array) $config['module_ps_seo_pack_return_policies'] : [];

        $shipping_rate_enabled = isset($config['module_ps_seo_pack_shipping_rate']) ? (bool) $config['module_ps_seo_pack_shipping_rate'] : false;
        $shipping_rates = isset($config['module_ps_seo_pack_shipping_rates']) ? (array) $config['module_ps_seo_pack_shipping_rates'] : [];

        $item_condition_enabled = isset($config['module_ps_seo_pack_item_condition']) ? (bool) $config['module_ps_seo_pack_item_condition'] : false;
        $item_condition_assocs = isset($config['module_ps_seo_pack_item_condition_assoc']) ? (array) $config['module_ps_seo_pack_item_condition_assoc'] : [];

        $store_language_code = isset($config['module_ps_seo_pack_store_language_code']) ? (bool) $config['module_ps_seo_pack_store_language_code'] : false;

        $store_name = isset($config['module_ps_seo_pack_store_name']) ? (array) $config['module_ps_seo_pack_store_name'] : [];

        if (isset($store_name[$config_language_id])) {
            $store_name = $store_name[$config_language_id];
        } else {
            $store_name = '';
        }

        $alternate_store_names = isset($config['module_ps_seo_pack_alternate_store_name']) ? (array) $config['module_ps_seo_pack_alternate_store_name'] : [];

        if (isset($alternate_store_names[$config_language_id])) {
            $alternate_store_names = $alternate_store_names[$config_language_id];
        } else {
            $alternate_store_names = [];
        }

        $store_owner = isset($config['module_ps_seo_pack_store_owner']) ? (array) $config['module_ps_seo_pack_store_owner'] : [];

        if (isset($store_owner[$config_language_id])) {
            $store_owner = $store_owner[$config_language_id];
        } else {
            $store_owner = '';
        }

        $store_description = isset($config['module_ps_seo_pack_store_description']) ? (array) $config['module_ps_seo_pack_store_description'] : [];

        if (isset($store_description[$config_language_id])) {
            $store_description = $store_description[$config_language_id];
        } else {
            $store_description = '';
        }

        $store_image = isset($config['module_ps_seo_pack_image']) ? $config['module_ps_seo_pack_image'] : '';

        if ($store_image && is_file(DIR_IMAGE . $store_image)) {
            $store_image_url = $this->model_tool_image->resize($store_image, 1500, 1500);
        } else {
            $store_image_url = $this->model_tool_image->resize('no_image.png', 1500, 1500);
        }

        $same_as = isset($config['module_ps_seo_pack_same_as']) ? (array) $config['module_ps_seo_pack_same_as'] : [];

        $opening_hour = isset($config['module_ps_seo_pack_opening_hour']) ? (array) $config['module_ps_seo_pack_opening_hour'] : [];

        $postal_address = isset($config['module_ps_seo_pack_postal_address']) ? (array) $config['module_ps_seo_pack_postal_address'] : [];

        if (isset($postal_address[$config_language_id])) {
            $postal_address = $postal_address[$config_language_id];
        } else {
            $postal_address = [];
        }

        $location_address = isset($config['module_ps_seo_pack_location_address']) ? (array) $config['module_ps_seo_pack_location_address'] : [];

        if (isset($location_address[$config_language_id])) {
            $location_address = $location_address[$config_language_id];
        } else {
            $location_address = [];
        }

        $geo_coordinates = isset($config['module_ps_seo_pack_geo_coordinates']) ? (array) $config['module_ps_seo_pack_geo_coordinates'] : [];

        $price_range = isset($config['module_ps_seo_pack_price_range']) ? $config['module_ps_seo_pack_price_range'] : '';

        $contact_points = isset($config['module_ps_seo_pack_contact_point']) ? (array) $config['module_ps_seo_pack_contact_point'] : [];


        $result = [];

        if ($ps_seo_pack_route === 'common/home') {
            $result = $this->getCommonHome($languages);
        } else if ($ps_seo_pack_route === 'account/login') {
            $result = $this->getAccountLogin($languages);
        } else if ($ps_seo_pack_route === 'account/register') {
            $result = $this->getAccountRegister($languages);
        } else if ($ps_seo_pack_route === 'account/forgotten') {
            $result = $this->getAccountForgotten($languages);
        } else if ($ps_seo_pack_route === 'product/manufacturer' . $separator . 'info') {
            $result = $this->getProductManufacturerInfo($languages);
        } else if ($ps_seo_pack_route === 'product/manufacturer') {
            $result = $this->getProductManufacturer($languages);
        } else if ($ps_seo_pack_route === 'information/sitemap') {
            $result = $this->getInformationSitemap($languages);
        } else if ($ps_seo_pack_route === 'checkout/cart') {
            $result = $this->getCheckoutCart($languages);
        } else if ($ps_seo_pack_route === 'product/search') {
            $result = $this->getProductSearch($languages);
        } else if ($ps_seo_pack_route === 'product/category') {
            $result = $this->getProductCategory($languages);
        } else if ($ps_seo_pack_route === 'product/product') {
            $result = $this->getProductProduct($languages);
        } else if ($ps_seo_pack_route === 'information/information') {
            $result = $this->getInformationInformation($languages);
        } else if ($ps_seo_pack_route === 'information/contact') {
            $result = $this->getInformationContact($languages);
        }

        if ($result) {
            $html_prefix = [];

            if ($result['url']) {
                $first_url = $result['url'][$config_language];
                $current_url = $first_url['href'];
            }

            if ($this->_strlen(trim($result['meta_description'])) > 0) {
                $short_description = $this->_substr($result['meta_description'], 0, 150);
                $long_description = $this->_substr($result['meta_description'], 0, 1000);
            } else if ($this->_strlen(trim($result['description'])) > 0) {
                $short_description = $this->_substr($result['description'], 0, 150);
                $long_description = $this->_substr($result['description'], 0, 1000);
            } else {
                $short_description = '';
                $long_description = '';
            }

            #region Dublin Core
            if ($dublin_core_enabled) {
                $html_prefix[] = 'dc: http:/purl.org/dc/elements/1.1/';
                $html_prefix[] = 'dcterms: http://purl.org/dc/terms/';
                $html_prefix[] = 'dcmitype: http://purl.org/dc/dcmitype/';

                $args['ps_seo_pack_dublincores'] = [
                    'DC.Type' => 'InteractiveResource',
                    'DC.Title' => $result['title'], // Default
                    'DC.Creator' => $store_name, // User configured
                    'DC.Description' => $short_description,
                    'DC.Publisher' => $store_owner, // User configured
                    'DC.Format' => 'text/html',
                    'DC.Identifier' => $current_url,
                    'DC.Source' => $this->config->get('config_url'), // Default
                    'DC.Language' => $this->language->get('code'), // Default
                    'DC.Coverage' => $this->language->get('code'), // Default
                    'DC.Rights' => sprintf($this->language->get('text_dc_rights'), $store_owner), // User configured
                ];

                if ($ps_seo_pack_route === 'information/information') {
                    $args['ps_seo_pack_dublincores']['DC.Type'] = 'Text.Article';
                }

                if (isset($result['date_added']) && $result['date_added'] !== '0000-00-00 00:00:00') {
                    $args['ps_seo_pack_dublincores']['DC.Date'] = date('Y-m-d\TH:i:sP', strtotime($result['date_added']));
                }

                $args['ps_seo_pack_links'] = [
                    'schema.DC' => 'http://purl.org/dc/elements/1.1/',
                    'schema.DCTERMS' => 'http://purl.org/dc/terms/',
                ];
            }
            #endregion

            #region Open Graph
            if ($open_graph_enabled) {
                $html_prefix[] = 'og: https://ogp.me/ns#';
                $html_prefix[] = 'article: https://ogp.me/ns/article#';

                $args['ps_seo_pack_opengraphs'] = [
                    ['property' => 'og:locale', 'content' => $this->language->get('code')],
                    ['property' => 'og:title', 'content' => $result['title']],
                    ['property' => 'og:site_name', 'content' => $store_name],
                    ['property' => 'og:description', 'content' => $short_description],
                    ['property' => 'og:url', 'content' => $current_url],
                    ['property' => 'fb:app_id', 'content' => $facebook_app_id],
                    ['property' => 'article:publisher', 'content' => $this->config->get('config_url')],
                ];

                if ($ps_seo_pack_route === 'information/information') {
                    $args['ps_seo_pack_opengraphs'][] = ['property' => 'og:type', 'content' => 'article'];
                } else {
                    $args['ps_seo_pack_opengraphs'][] = ['property' => 'og:type', 'content' => 'website'];
                }

                if (
                    isset($result['date_added'], $result['date_modified']) &&
                    $result['date_added'] !== '0000-00-00 00:00:00' &&
                    $result['date_modified'] !== '0000-00-00 00:00:00'
                ) {
                    $args['ps_seo_pack_opengraphs'][] = ['property' => 'article:published_time', 'content' => date('Y-m-d\TH:i:sP', strtotime($result['date_added']))];
                    $args['ps_seo_pack_opengraphs'][] = ['property' => 'article:modified_time', 'content' => date('Y-m-d\TH:i:sP', strtotime($result['date_modified']))];
                }

                if (isset($result['tags'])) {
                    foreach ($result['tags'] as $tag) {
                        $args['ps_seo_pack_opengraphs'][] = ['property' => 'article:tag', 'content' => $tag];
                    }
                }

                if (isset($result['category'])) {
                    $args['ps_seo_pack_opengraphs'][] = ['property' => 'article:section', 'content' => $result['category']];
                }

                if (isset($result['manufacturer'])) {
                    $args['ps_seo_pack_opengraphs'][] = ['property' => 'product:brand', 'content' => $result['manufacturer']];
                }

                if (isset($result['special']) && $result['special']) {
                    $args['ps_seo_pack_opengraphs'][] = ['property' => 'product:price:amount', 'content' => $result['special']];
                } else if (isset($result['price']) && $result['price']) {
                    $args['ps_seo_pack_opengraphs'][] = ['property' => 'product:price:amount', 'content' => $result['price']];
                }

                if (isset($result['price_currency'])) {
                    $args['ps_seo_pack_opengraphs'][] = ['property' => 'product:price:currency', 'content' => $result['price_currency']];
                }

                if (isset($result['quantity'], $result['stock_status_id'])) {
                    if ($result['quantity'] > 0) {
                        $json_availability = 'available for order';
                    } else {
                        $json_availability = 'out of stock';
                    }

                    if ($open_graph_stock_status_enabled && $result['quantity'] === 0) {
                        foreach ($open_graph_stock_status_assocs as $open_graph_stock_status_assoc) {
                            if ((int) $open_graph_stock_status_assoc['stock_status_id'] === $result['stock_status_id']) {
                                $json_availability = $open_graph_stock_status_assoc['open_graph_id'];
                                break;
                            }
                        }
                    }

                    $args['ps_seo_pack_opengraphs'][] = ['property' => 'product:availability', 'content' => $json_availability];
                }

                if (isset($result['images']) && $result['images']) {
                    foreach ($result['images'] as $images) {
                        $args['ps_seo_pack_opengraphs'][] = ['property' => 'og:image', 'content' => $images];
                    }
                } else {
                    $args['ps_seo_pack_opengraphs'][] = ['property' => 'og:image', 'content' => $store_image_url];
                }

                if (isset($result['additional_property'])) {
                    foreach ($result['additional_property'] as $index => $additional_property) {
                        $args['ps_seo_pack_opengraphs'][] = ['property' => 'product:custom_label_' . $index, 'content' => $additional_property['name'] . ' ' . $additional_property['text']];
                    }
                }
            }
            #endregion

            #region Twitter
            if ($twitter_enabled) {
                $args['ps_seo_pack_twitters'] = [
                    ['name' => 'twitter:site', 'content' => $twitter_handle],
                    ['name' => 'twitter:title', 'content' => $result['title']],
                    ['name' => 'twitter:description', 'content' => $short_description],
                    ['name' => 'twitter:card', 'content' => $twitter_card_type],
                ];

                if (isset($result['images']) && $result['images']) {
                    foreach ($result['images'] as $images) {
                        $args['ps_seo_pack_twitters'][] = ['name' => 'twitter:image', 'content' => $store_image_url];
                    }
                } else {
                    $args['ps_seo_pack_twitters'][] = ['name' => 'twitter:image', 'content' => $store_image_url];
                }

                if (isset($result['additional_property'])) {
                    foreach ($result['additional_property'] as $index => $additional_property) {
                        $args['ps_seo_pack_twitters'][] = ['name' => 'twitter:label' . $index, 'content' => $additional_property['name']];
                        $args['ps_seo_pack_twitters'][] = ['name' => 'twitter:data' . $index, 'content' => $additional_property['text']];
                    }
                }
            }
            #endregion

            #region Structured Data Markup (LD+JSON)
            if ($sdm_enabled) {
                $richSnippet = [];

                #region WebPage
                $richSnippet['WebPage'] = [
                    '@type' => 'WebPage',
                    '@id' => $this->config->get('config_url'),
                    'url' => $this->config->get('config_url'),
                    'name' => $result['title'],
                    'isPartOf' => [
                        '@id' => sprintf('%s#%s', $this->config->get('config_url'), 'website'),
                    ],
                    'about' => [
                        '@id' => sprintf('%s#%s', $this->config->get('config_url'), 'website'),
                    ],
                    'primaryImageOfPage' => [
                        '@id' => sprintf('%s#%s', $this->config->get('config_url'), 'primaryimage'),
                    ],
                    'image' => [
                        '@id' => sprintf('%s#%s', $this->config->get('config_url'), 'primaryimage'),
                    ],
                    'description' => $long_description,
                    'inLanguage' => $this->language->get('code'),
                    'potentialAction' => [
                        '@type' => 'ReadAction',
                        'target' => [$current_url]
                    ]
                ];

                if ($ps_seo_pack_route === 'product/product') {
                    $richSnippet['WebPage']['about'] = [
                        '@id' => sprintf('%s#%s', $current_url, 'product'),
                    ];
                }

                if ($ps_seo_pack_route === 'information/contact') {
                    $richSnippet['WebPage']['about'] = [
                        '@id' => sprintf('%s#%s', $this->config->get('config_url'), 'organization'),
                    ];
                }

                if (
                    isset($result['date_added'], $result['date_modified']) &&
                    $result['date_added'] !== '0000-00-00 00:00:00' &&
                    $result['date_modified'] !== '0000-00-00 00:00:00'
                ) {
                    $richSnippet['WebPage']['datePublished'] = date('Y-m-d\TH:i:sP', strtotime($result['date_added']));
                    $richSnippet['WebPage']['dateModified'] = date('Y-m-d\TH:i:sP', strtotime($result['date_modified']));
                }
                #endregion

                #region WebSite
                $richSnippet['WebSite'] = [
                    '@type' => 'WebSite',
                    '@id' => sprintf('%s#%s', $this->config->get('config_url'), 'website'),
                    'url' => $this->config->get('config_url'),
                    'name' => $store_owner,
                    'description' => $store_description,
                    'publisher' => [
                        '@id' => sprintf('%s#%s', $this->config->get('config_url'), 'organization'),
                    ],
                    'potentialAction' => [
                        [
                            '@type' => 'SearchAction',
                            'target' => [
                                '@type' => 'EntryPoint',
                                'urlTemplate' => str_replace('&amp;', '&', $this->url->link('product/search', 'language=' . $this->config->get('config_language') . '&search=')) . '{search_term_string}',
                            ],
                            'query-input' => [
                                '@type' => 'PropertyValueSpecification',
                                'valueRequired' => true,
                                'valueName' => 'search_term_string'
                            ]
                        ]
                    ],
                    'inLanguage' => $this->language->get('code')
                ];

                foreach ($alternate_store_names as $alternate_store_name) {
                    $richSnippet['WebSite']['alternateName'][] = $alternate_store_name;
                }
                #endregion

                #region Primary Image
                $richSnippet['ImageObject'] = [
                    '@type' => 'ImageObject',
                    'inLanguage' => $this->language->get('code'),
                    '@id' => sprintf('%s#%s', $this->config->get('config_url'), 'primaryimage'),
                    'url' => $store_image_url,
                    'contentUrl' => $store_image_url,
                    'width' => 1500,
                    'height' => 1500,
                ];
                #endregion

                #region Organization
                $richSnippet['Organization'] = [
                    '@type' => 'Organization',
                    '@id' => sprintf('%s#%s', $this->config->get('config_url'), 'organization'),
                    'url' => $this->config->get('config_url'),
                    'name' => $store_owner,
                    'logo' => [
                        '@type' => 'ImageObject',
                        'inLanguage' => $this->language->get('code'),
                        '@id' => sprintf('%s#%s', $this->config->get('config_url'), '/schema/logo/image/'),
                        'url' => $store_image_url,
                        'contentUrl' => $store_image_url,
                        'width' => 1500,
                        'height' => 1500,
                        'caption' => $store_owner,
                    ],
                    'image' => [
                        [
                            '@id' => sprintf('%s#%s', $this->config->get('config_url'), '/schema/logo/image/'),
                        ]
                    ],
                ];
                #endregion

                #region Store
                $richSnippet['Store'] = [
                    '@type' => 'Store',
                    '@id' => sprintf('%s#%s', $this->config->get('config_url'), 'store'),
                    'url' => $this->config->get('config_url'),
                    'name' => $store_name,
                    'image' => [
                        [
                            '@id' => sprintf('%s#%s', $this->config->get('config_url'), '/schema/logo/image/'),
                        ]
                    ],
                    'sameAs' => $same_as,
                    'telephone' => $this->config->get('config_telephone'),
                    'email' => $this->config->get('config_email'),
                    'openingHours' => $this->compactOpeningHours($opening_hour),
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => $postal_address['address'],
                        'addressLocality' => $postal_address['city'],
                        'addressRegion' => $postal_address['state'],
                        'addressCountry' => $postal_address['country_code'],
                        'postalCode' => $postal_address['postal_code'],
                    ],
                    'location' => [
                        '@type' => 'Place',
                        'address' => [
                            '@type' => 'PostalAddress',
                            'streetAddress' => $location_address['address'],
                            'addressLocality' => $location_address['city'],
                            'addressRegion' => $location_address['state'],
                            'addressCountry' => $location_address['country_code'],
                            'postalCode' => $location_address['postal_code'],
                        ],
                    ],
                    'geo' => [],
                    'priceRange' => $price_range,
                    'contactPoint' => []
                ];

                if ($geo_coordinates['latitude'] && $geo_coordinates['longtitude']) {
                    $richSnippet['Store']['geo'] = [
                        '@type' => 'GeoCoordinates',
                        'latitude' => $geo_coordinates['latitude'],
                        'longitude' => $geo_coordinates['longtitude'],
                    ];
                }

                foreach ($contact_points as $key => $data) {
                    $richSnippet['Store']['contactPoint'][] = [
                        '@type' => 'ContactPoint',
                        'telephone' => $data['telephone'],
                        'contactType' => $data['contact_type'],
                    ];
                }
                #endregion

                #region Offer
                if ($ps_seo_pack_route === 'product/product' && !$result['error']) {
                    $json_item_condition = 'NewCondition';

                    if ($item_condition_enabled && $this->_strlen(trim($result['location'])) > 0) {
                        foreach ($item_condition_assocs as $item_condition_assoc) {
                            if ($item_condition_assoc['col_value'] === $result['location']) {
                                $json_item_condition = $item_condition_assoc['item_condition'];
                                break;
                            }
                        }
                    }

                    if ($result['quantity'] > 0) {
                        $json_availability = 'InStock';
                    } else {
                        $json_availability = 'OutOfStock';
                    }

                    if ($sdm_stock_status_enabled && $result['quantity'] === 0) {
                        foreach ($sdm_stock_status_assocs as $sdm_stock_status_assoc) {
                            if ((int) $sdm_stock_status_assoc['stock_status_id'] === $result['stock_status_id']) {
                                $json_availability = $sdm_stock_status_assoc['schema_org_id'];
                                break;
                            }
                        }
                    }

                    $richSnippet['Product'] = [
                        '@type' => 'Product',
                        '@id' => sprintf('%s#%s', $current_url, 'product'),
                        'url' => $current_url,
                        'name' => $result['name'],
                        'description' => $long_description,
                        'image' => (isset($result['images']) && $result['images']) ? $result['images'] : [$store_image_url],
                        'brand' => $result['manufacturer'],
                        'productID' => $result['product_id'],
                        'model' => $result['model'],
                        'offers' => [],
                        'depth' => [
                            '@type' => 'QuantitativeValue',
                            'value' => (float) $result['length'],
                            'unitText' => $result['length_unit'],
                        ],
                        'height' => [
                            '@type' => 'QuantitativeValue',
                            'value' => (float) $result['height'],
                            'unitText' => $result['length_unit'],
                        ],
                        'width' => [
                            '@type' => 'QuantitativeValue',
                            'value' => (float) $result['width'],
                            'unitText' => $result['length_unit'],
                        ],
                        'weight' => [
                            '@type' => 'QuantitativeValue',
                            'value' => (float) $result['weight'],
                            'unitText' => $result['weight_unit'],
                        ],
                    ];

                    $json_merchant_return_policy = [];
                    $json_shipping_rates = [];

                    if ($return_policy_enabled) {
                        foreach ($return_policies as $return_policy) {
                            $json_merchant_return_policy_row = [
                                '@type' => 'MerchantReturnPolicy',
                                'applicableCountry' => $return_policy['country_id'],
                                'returnPolicyCategory' => 'https://schema.org/' . $return_policy['return_policy_category'],
                            ];

                            if ($return_policy['return_policy_category'] !== 'MerchantReturnNotPermitted') {
                                $json_merchant_return_policy_row['merchantReturnDays'] = $return_policy['return_days'];
                                $json_merchant_return_policy_row['returnMethod'] = 'https://schema.org/' . $return_policy['return_method'];
                                $json_merchant_return_policy_row['returnFees'] = 'https://schema.org/' . $return_policy['return_fee'];
                            }

                            $json_merchant_return_policy[] = $json_merchant_return_policy_row;
                        }
                    }

                    if ($shipping_rate_enabled) {
                        foreach ($shipping_rates as $shipping_rate) {
                            $json_shipping_rates[] = [
                                '@type' => 'OfferShippingDetails',
                                'shippingRate' => [
                                    '@type' => 'MonetaryAmount',
                                    'value' => (float) $shipping_rate['rate'],
                                    'currency' => $shipping_rate['currency'],
                                ],
                                'countryautocomplete' => [
                                    '@type' => 'DefinedRegion',
                                    'addressCountry' => $shipping_rate['destination_id'],
                                ],
                                'deliveryTime' => [
                                    '@type' => 'ShippingDeliveryTime',
                                    'handlingTime' => [
                                        '@type' => 'QuantitativeValue',
                                        'minValue' => (int) $shipping_rate['handling_time_min'],
                                        'maxValue' => (int) $shipping_rate['handling_time_max'],
                                        'unitCode' => 'd',
                                    ],
                                    'transitTime' => [
                                        '@type' => 'QuantitativeValue',
                                        'minValue' => (int) $shipping_rate['transit_time_min'],
                                        'maxValue' => (int) $shipping_rate['transit_time_max'],
                                        'unitCode' => 'd',
                                    ],
                                ],
                            ];
                        }
                    }


                    $json_offers = [];

                    $json_offer = [
                        '@type' => 'Offer',
                        'price' => $result['price'],
                        'priceCurrency' => $result['price_currency'],
                        'url' => $current_url,
                        'itemCondition' => 'https://schema.org/' . $json_item_condition,
                        'availability' => 'https://schema.org/' . $json_availability,
                    ];

                    if ($json_merchant_return_policy) {
                        $json_offer['hasMerchantReturnPolicy'] = $json_merchant_return_policy;
                    }

                    if ($json_shipping_rates) {
                        $json_offer['shippingDetails'] = $json_shipping_rates;
                    }

                    $json_offers[] = $json_offer;

                    if ($result['special'] && $result['special_valid_until'] !== '0000-00-00') {
                        $json_offer = [
                            '@type' => 'Offer',
                            'price' => $result['special'],
                            'priceCurrency' => $result['price_currency'],
                            'priceValidUntil' => date('Y-m-d\TH:i:sP', strtotime($result['special_valid_until'])),
                            'url' => $current_url,
                            'itemCondition' => 'https://schema.org/' . $json_item_condition,
                            'availability' => 'https://schema.org/' . $json_availability,
                        ];

                        if ($json_merchant_return_policy) {
                            $json_offer['hasMerchantReturnPolicy'] = $json_merchant_return_policy;
                        }

                        if ($json_shipping_rates) {
                            $json_offer['shippingDetails'] = $json_shipping_rates;
                        }

                        $json_offers[] = $json_offer;
                    }

                    $richSnippet['Product']['offers'] = $json_offers;


                    if ($result['reviews']) {
                        $json_reviews = [];

                        foreach ($result['reviews'] as $review) {
                            $json_reviews[] = [
                                '@type' => 'Review',
                                'reviewRating' => [
                                    '@type' => 'Rating',
                                    'ratingValue' => (int) $review['rating'],
                                    'bestRating' => 5,
                                ],
                                'author' => [
                                    '@type' => 'Person',
                                    'name' => trim(strip_tags(html_entity_decode($review['author'], ENT_QUOTES, 'UTF-8'))),
                                ],
                                'reviewBody' => trim(strip_tags(html_entity_decode($review['text'], ENT_QUOTES, 'UTF-8'))),
                                'datePublished' => date('Y-m-d', strtotime($review['date_added'])),
                            ];
                        }

                        $all_rating_values = array_column($result['reviews'], 'rating');

                        if (count($all_rating_values) > 0) {
                            $average_rating = array_sum($all_rating_values) / count($all_rating_values);
                        } else {
                            $average_rating = 0;
                        }

                        $richSnippet['Product']['review'] = $json_reviews;
                        $richSnippet['Product']['aggregateRating'] = [
                            '@type' => 'AggregateRating',
                            'reviewCount' => (int) $result['review_count'],
                            'bestRating' => 5,
                            'ratingCount' => (int) $result['review_count'],
                            'ratingValue' => (int) $average_rating,
                        ];
                    }

                    if ($result['additional_property']) {
                        $json_additional_property = [];

                        foreach ($result['additional_property'] as $additional_property) {
                            $json_additional_property[] = [
                                '@type' => 'PropertyValue',
                                'name' => $additional_property['name'],
                                'value' => $additional_property['text'],
                            ];
                        }

                        $richSnippet['Product']['additionalProperty'] = $json_additional_property;
                    }
                }
                #endregion

                #region BreadcrumbList
                if (isset($result['breadcrumb'])) {
                    $richSnippet['BreadcrumbList'] = [
                        '@type' => 'BreadcrumbList',
                        '@id' => sprintf('%s#%s', $current_url, 'breadcrumb'),
                        'itemListElement' => []
                    ];

                    foreach ($result['breadcrumb'] as $index => $breadcrumb) {
                        $breadcrumb['position'] = $index;

                        $richSnippet['BreadcrumbList']['itemListElement'][] = $breadcrumb;
                    }
                }
                #endregion

                $args['ps_seo_pack_ld_json'] = json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => array_values($richSnippet)
                ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            }
            #endregion

            if ($result['url']) {
                foreach ($result['url'] as $url) {
                    if ($store_language_code && $url['code'] === $store_language_code) {
                        $args['ps_seo_pack_language_links'][] = [
                            'hreflang' => 'x-default',
                            'href' => $url['href'],
                        ];
                    }

                    $args['ps_seo_pack_language_links'][] = [
                        'hreflang' => $url['code'],
                        'href' => $url['href'],
                    ];
                }
            }

            if ($html_prefix) {
                $args['ps_seo_pack_html_prefix'] = implode(' ', $html_prefix);
            }

            if ($this->_strlen(trim($store_owner)) > 0) {
                $args['title'] = $result['title'] . ' | ' . $store_owner;
            }

            if ($this->_strlen(trim($short_description)) > 0) {
                $args['description'] = $short_description;
            } else if ($this->_strlen(trim($long_description)) > 0) {
                $args['description'] = $this->_substr($long_description, 0, 150);
            }
        }

        $headerViews = $this->model_extension_ps_seo_pack_module_ps_seo_pack->replaceHeaderViews($args);

        $template = $this->replaceViews($route, $template, $headerViews);
    }

    /**
     * Get information/contact route informations.
     *
     * @param array $languages
     *
     * @return array
     */
    private function getInformationContact(array $languages): array
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => str_replace('&amp;', '&', $this->url->link('information/contact', 'language=' . $language['code'])),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'title' => $this->language->get('heading_title'),
            'description' => $this->config->get('config_meta_description'),
            'meta_description' => $this->config->get('config_meta_description'),
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('heading_title'),
        ];

        return $result;
    }

    /**
     * Get common/home route informations.
     *
     * @param array $languages
     *
     * @return array
     */
    private function getCommonHome(array $languages): array
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $language['code'])),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'title' => $this->config->get('config_meta_title'),
            'description' => $this->config->get('config_meta_description'),
            'meta_description' => $this->config->get('config_meta_description'),
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
        ];

        return $result;
    }

    /**
     * Get account/login route informations.
     *
     * @param array $languages
     *
     * @return array
     */
    private function getAccountLogin(array $languages): array
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => str_replace('&amp;', '&', $this->url->link('account/login', 'language=' . $language['code'])),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'title' => $this->language->get('heading_title'),
            'description' => $this->config->get('config_meta_description'),
            'meta_description' => $this->config->get('config_meta_description'),
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_account'),
            'item' => str_replace('&amp;', '&', $this->url->link('account/account', 'language=' . $this->config->get('config_language')))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_login'),
        ];

        return $result;
    }

    /**
     * Get account/register route informations.
     *
     * @param array $languages
     *
     * @return array
     */
    private function getAccountRegister(array $languages): array
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => str_replace('&amp;', '&', $this->url->link('account/register', 'language=' . $language['code'])),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'title' => $this->language->get('heading_title'),
            'description' => $this->config->get('config_meta_description'),
            'meta_description' => $this->config->get('config_meta_description'),
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_account'),
            'item' => str_replace('&amp;', '&', $this->url->link('account/account', 'language=' . $this->config->get('config_language')))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_register'),
        ];

        return $result;
    }

    /**
     * Get account/forgotten route informations.
     *
     * @param array $languages
     *
     * @return array
     */
    private function getAccountForgotten(array $languages): array
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => str_replace('&amp;', '&', $this->url->link('account/forgotten', 'language=' . $language['code'])),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'title' => $this->language->get('heading_title'),
            'description' => $this->config->get('config_meta_description'),
            'meta_description' => $this->config->get('config_meta_description'),
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language'))),
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_account'),
            'item' => str_replace('&amp;', '&', $this->url->link('account/account', 'language=' . $this->config->get('config_language'))),
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_forgotten'),
        ];

        return $result;
    }

    /**
     * Get product/manufacturer[.|]info route informations.
     *
     * @param array $languages
     *
     * @return array
     */
    private function getProductManufacturerInfo(array $languages): array
    {
        if (isset($this->request->get['manufacturer_id'])) {
            $manufacturer_id = (int) $this->request->get['manufacturer_id'];
        } else {
            $manufacturer_id = 0;
        }

        $separator = version_compare(VERSION, '4.0.2.0', '>=') ? '.' : '|';

        $result = [];

        $this->load->model('catalog/manufacturer');
        $this->load->model('tool/image');

        $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

        if ($manufacturer_info) {
            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $urls = [];

            foreach ($languages as $language) {
                $urls[$language['code']] = [
                    'href' => str_replace('&amp;', '&', $this->url->link('product/manufacturer' . $separator . 'info', 'language=' . $language['code'] . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)),
                    'code' => $language['code'],
                ];
            }

            $images = [];

            if (!empty($manufacturer_info['image'])) {
                $images[] = $this->model_tool_image->resize($manufacturer_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            }

            $result = [
                'error' => false,
                'title' => $manufacturer_info['name'],
                'description' => $this->config->get('config_meta_description'),
                'meta_description' => $this->config->get('config_meta_description'),
                'url' => $urls,
                'images' => $images,
                'breadcrumb' => [],
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_brand'),
                'item' => str_replace('&amp;', '&', $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language')))
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $manufacturer_info['name'],
            ];
        } else {
            $result = [
                'error' => true,
                'title' => $this->language->get('heading_title'),
                'description' => $this->config->get('config_meta_description'),
                'meta_description' => $this->config->get('config_meta_description'),
                'url' => null,
                'breadcrumb' => [],
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('heading_title'),
            ];
        }

        return $result;
    }

    /**
     * Get product/manufacturer route informations.
     *
     * @param array $languages
     *
     * @return array
     */
    private function getProductManufacturer(array $languages): array
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => str_replace('&amp;', '&', $this->url->link('product/manufacturer', 'language=' . $language['code'])),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'title' => $this->language->get('heading_title'),
            'description' => $this->config->get('config_meta_description'),
            'meta_description' => $this->config->get('config_meta_description'),
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_brand'),
        ];

        return $result;
    }

    /**
     * Get information/sitemap route informations.
     *
     * @param array $languages
     *
     * @return array
     */
    private function getInformationSitemap(array $languages): array
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => str_replace('&amp;', '&', $this->url->link('information/sitemap', 'language=' . $language['code'])),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'title' => $this->language->get('heading_title'),
            'description' => $this->config->get('config_meta_description'),
            'meta_description' => $this->config->get('config_meta_description'),
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('heading_title'),
        ];

        return $result;
    }

    /**
     * Get checkout/cart route informations.
     *
     * @param array $languages
     *
     * @return array
     */
    private function getCheckoutCart(array $languages): array
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => str_replace('&amp;', '&', $this->url->link('checkout/cart', 'language=' . $language['code'])),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'title' => $this->language->get('heading_title'),
            'description' => $this->config->get('config_meta_description'),
            'meta_description' => $this->config->get('config_meta_description'),
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('heading_title'),
        ];

        return $result;
    }

    /**
     * Get product/search route informations.
     *
     * @param array $languages
     *
     * @return array
     */
    private function getProductSearch(array $languages): array
    {
        $url = '';

        if (isset($this->request->get['search'])) {
            $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['tag'])) {
            $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['description'])) {
            $url .= '&description=' . $this->request->get['description'];
        }

        if (isset($this->request->get['category_id'])) {
            $url .= '&category_id=' . $this->request->get['category_id'];
        }

        if (isset($this->request->get['sub_category'])) {
            $url .= '&sub_category=' . $this->request->get['sub_category'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => str_replace('&amp;', '&', $this->url->link('product/search', 'language=' . $language['code'] . $url)),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'title' => $this->language->get('heading_title'),
            'description' => $this->config->get('config_meta_description'),
            'meta_description' => $this->config->get('config_meta_description'),
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('heading_title'),
        ];

        return $result;
    }

    /**
     * Get product/product route informations.
     *
     * @param array $languages
     *
     * @return array
     */
    private function getProductProduct(array $languages): array
    {
        if (isset($this->request->get['product_id'])) {
            $product_id = (int) $this->request->get['product_id'];
        } else {
            $product_id = 0;
        }

        $separator = version_compare(VERSION, '4.0.2.0', '>=') ? '.' : '|';

        $this->load->language('extension/ps_seo_pack/module/ps_seo_pack');
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/review');
        $this->load->model('tool/image');

        $result = [];

        $product_info = $this->model_catalog_product->getProduct($product_id);

        if ($product_info) {
            $images = [];

            if (!empty($product_info['image'])) {
                $images[] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            }

            $other_images = $this->model_catalog_product->getImages($product_id);

            foreach (array_slice($other_images, 0, 10) as $other_image) {
                if (!empty($other_image['image'])) {
                    $images[] = $this->model_tool_image->resize($other_image['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                }
            }

            $length_unit = $this->model_extension_ps_seo_pack_module_ps_seo_pack->getLengthClass($product_info['length_class_id']);
            $weight_unit = $this->model_extension_ps_seo_pack_module_ps_seo_pack->getWeightClass($product_info['weight_class_id']);

            $urls = [];

            foreach ($languages as $language) {
                $urls[$language['code']] = [
                    'href' => str_replace('&amp;', '&', $this->url->link('product/product', 'language=' . $language['code'] . '&product_id=' . $product_id)),
                    'code' => $language['code'],
                ];
            }

            $result = [
                'error' => false,
                'title' => $product_info['name'],
                'name' => $product_info['name'],
                'description' => $this->normalizeDescription($product_info['description']),
                'meta_description' => $this->normalizeDescription($product_info['meta_description']),
                'date_added' => $product_info['date_added'],
                'date_modified' => $product_info['date_modified'],
                'product_id' => $product_id,
                'model' => $product_info['model'],
                'quantity' => (int) $product_info['quantity'],
                'stock_status_id' => (int) $product_info['stock_status_id'],
                'location' => $product_info['location'],
                'price_currency' => $this->config->get('config_currency'),
                'url' => $urls,
                'breadcrumb' => [],
                'images' => array_unique($images),
                'review_count' => (int) $this->model_catalog_review->getTotalReviewsByProductId($product_id),
                'reviews' => $this->model_catalog_review->getReviewsByProductId($product_id, 0, 5),
                'additional_property' => [],
                'length' => $product_info['length'],
                'height' => $product_info['height'],
                'width' => $product_info['width'],
                'weight' => $product_info['weight'],
                'length_unit' => $length_unit['unit'],
                'weight_unit' => $weight_unit['unit'],
                'tags' => [],
                'category' => null,
            ];

            if ($product_info['tag']) {
                $tags = explode(',', $product_info['tag']);

                foreach ($tags as $tag) {
                    $result['tags'][] = trim($tag);
                }
            }

            $product_attribute_groups = $this->model_catalog_product->getAttributes($product_id);

            foreach ($product_attribute_groups as $product_attribute_group) {
                foreach ($product_attribute_group['attribute'] as $product_attribute) {
                    $result['additional_property'][] = [
                        'name' => $product_attribute['name'],
                        'text' => $product_attribute['text'],
                    ];
                }
            }

            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

            if ($manufacturer_info) {
                $result['manufacturer'] = $manufacturer_info['name'];
            } else {
                $result['manufacturer'] = '';
            }

            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $normal_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));

                $result['price'] = (float) $this->currency->format($normal_price, $this->config->get('config_currency'), 0, false);
            } else {
                $result['price'] = false;
            }

            if ((float) $product_info['special']) {
                $special_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));

                $result['special'] = (float) $this->currency->format($special_price, $this->config->get('config_currency'), 0, false);
                $result['special_valid_until'] = $this->model_extension_ps_seo_pack_module_ps_seo_pack->getSpecialPriceDatesByProductId($product_id);
            } else {
                $result['special'] = false;
                $result['special_valid_until'] = '';
            }



            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
            ];

            if (isset($this->request->get['path'])) {
                $path = '';

                $parts = explode('_', (string) $this->request->get['path']);

                $category_id = (int) array_pop($parts);
                $last_category = null;

                foreach ($parts as $path_id) {
                    if (!$path) {
                        $path = $path_id;
                    } else {
                        $path .= '_' . $path_id;
                    }

                    $category_info = $this->model_catalog_category->getCategory($path_id);

                    if ($category_info) {
                        $result['breadcrumb'][] = [
                            '@type' => 'ListItem',
                            'name' => $category_info['name'],
                            'item' => str_replace('&amp;', '&', $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $path))
                        ];

                        $last_category = $category_info['name'];
                    }
                }

                // Set the last category breadcrumb
                $category_info = $this->model_catalog_category->getCategory($category_id);

                if ($category_info) {
                    $url = '';

                    if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                    }

                    if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                    }

                    if (isset($this->request->get['page'])) {
                        $url .= '&page=' . $this->request->get['page'];
                    }

                    if (isset($this->request->get['limit'])) {
                        $url .= '&limit=' . $this->request->get['limit'];
                    }

                    $result['breadcrumb'][] = [
                        '@type' => 'ListItem',
                        'name' => $category_info['name'],
                        'item' => str_replace('&amp;', '&', $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $this->request->get['path'] . $url))
                    ];

                    $last_category = $category_info['name'];
                }

                if ($last_category) {
                    $result['category'] = $last_category;
                }
            }

            if (isset($this->request->get['manufacturer_id'])) {
                $result['breadcrumb'][] = [
                    '@type' => 'ListItem',
                    'name' => $this->language->get('text_brand'),
                    'item' => str_replace('&amp;', '&', $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language')))
                ];

                $url = '';

                if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
                }

                if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
                }

                if (isset($this->request->get['limit'])) {
                    $url .= '&limit=' . $this->request->get['limit'];
                }

                $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

                if ($manufacturer_info) {
                    $result['breadcrumb'][] = [
                        '@type' => 'ListItem',
                        'name' => $manufacturer_info['name'],
                        'item' => str_replace('&amp;', '&', $this->url->link('product/manufacturer' . $separator . 'info', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url))
                    ];
                }
            }

            if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
                $url = '';

                if (isset($this->request->get['search'])) {
                    $url .= '&search=' . $this->request->get['search'];
                }

                if (isset($this->request->get['tag'])) {
                    $url .= '&tag=' . $this->request->get['tag'];
                }

                if (isset($this->request->get['description'])) {
                    $url .= '&description=' . $this->request->get['description'];
                }

                if (isset($this->request->get['category_id'])) {
                    $url .= '&category_id=' . $this->request->get['category_id'];
                }

                if (isset($this->request->get['sub_category'])) {
                    $url .= '&sub_category=' . $this->request->get['sub_category'];
                }

                if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
                }

                if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
                }

                if (isset($this->request->get['limit'])) {
                    $url .= '&limit=' . $this->request->get['limit'];
                }

                $result['breadcrumb'][] = [
                    '@type' => 'ListItem',
                    'name' => $this->language->get('text_search'),
                    'item' => str_replace('&amp;', '&', $this->url->link('product/search', 'language=' . $this->config->get('config_language') . $url))
                ];
            }

            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['manufacturer_id'])) {
                $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . $this->request->get['search'];
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . $this->request->get['tag'];
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description=' . $this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id=' . $this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category=' . $this->request->get['sub_category'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $product_info['name'],
                'item' => str_replace('&amp;', '&', $this->url->link('product/product', 'language=' . $this->config->get('config_language') . $url . '&product_id=' . $product_id))
            ];
        } else {
            $result = [
                'error' => true,
                'title' => $this->language->get('heading_title'),
                'description' => $this->config->get('config_meta_description'),
                'meta_description' => $this->config->get('config_meta_description'),
                'url' => null,
                'breadcrumb' => [],
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('heading_title'),
            ];
        }

        return $result;
    }

    /**
     * Get information/information route informations.
     *
     * @param array $languages
     *
     * @return array
     */
    private function getInformationInformation(array $languages): array
    {
        if (isset($this->request->get['information_id'])) {
            $information_id = (int) $this->request->get['information_id'];
        } else {
            $information_id = 0;
        }

        $this->load->model('catalog/information');

        $result = [];

        $information_info = $this->model_catalog_information->getInformation($information_id);

        if ($information_info) {
            $urls = [];

            foreach ($languages as $language) {
                $urls[$language['code']] = [
                    'href' => str_replace('&amp;', '&', $this->url->link('information/information', 'language=' . $language['code'] . '&information_id=' . $information_id)),
                    'code' => $language['code'],
                ];
            }

            $result = [
                'error' => false,
                'title' => $information_info['title'],
                'description' => $this->normalizeDescription($information_info['description']),
                'meta_description' => $this->normalizeDescription($information_info['meta_description']),
                'url' => $urls,
                'breadcrumb' => [],
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $information_info['title']
            ];
        } else {
            $result = [
                'error' => true,
                'title' => $this->language->get('heading_title'),
                'description' => $this->config->get('config_meta_description'),
                'meta_description' => $this->config->get('config_meta_description'),
                'url' => null,
                'breadcrumb' => [],
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('heading_title'),
            ];
        }

        return $result;
    }

    /**
     * Get product/category route informations.
     *
     * @param array $languages
     *
     * @return array
     */
    private function getProductCategory(array $languages): array
    {
        if (isset($this->request->get['path'])) {
            $path = (string) $this->request->get['path'];
        } else {
            $path = '';
        }

        $parts = explode('_', $path);

        $category_id = (int) array_pop($parts);

        $this->load->model('catalog/category');
        $this->load->model('tool/image');

        $result = [];

        $category_info = $this->model_catalog_category->getCategory($category_id);

        if ($category_info) {
            $images = [];

            if (!empty($category_info['image'])) {
                $images[] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            }

            $result = [
                'error' => false,
                'title' => '',
                'description' => $this->normalizeDescription($category_info['description']),
                'meta_description' => $this->normalizeDescription($category_info['meta_description']),
                'url' => null,
                'images' => $images,
                'date_added' => $category_info['date_added'],
                'date_modified' => $category_info['date_modified'],
                'breadcrumb' => [],
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
            ];

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $path = '';

            foreach ($parts as $index => $path_id) {
                if (!$path) {
                    $path = (int) $path_id;
                } else {
                    $path .= '_' . (int) $path_id;
                }

                $sub_category_info = $this->model_catalog_category->getCategory($path_id);

                if ($sub_category_info) {
                    if ($this->_strlen(trim($result['description'])) === 0) {
                        $result['description'] = $this->normalizeDescription($sub_category_info['description']);
                    }

                    if ($result['images']) {
                        $images = [];

                        if (!empty($sub_category_info['image'])) {
                            $images[] = $this->model_tool_image->resize($sub_category_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                        }

                        $result['images'] = $images;
                    }

                    $result['breadcrumb'][] = [
                        '@type' => 'ListItem',
                        'name' => $sub_category_info['name'],
                        'item' => str_replace('&amp;', '&', $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $path . $url))
                    ];
                }
            }

            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            // Set the last category breadcrumb
            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $category_info['name'],
                'item' => str_replace('&amp;', '&', $this->url->link('product/category', 'language=' . $this->config->get('config_language') . $url))
            ];

            $urls = [];

            foreach ($languages as $language) {
                $urls[$language['code']] = [
                    'href' => str_replace('&amp;', '&', $this->url->link('product/category', 'language=' . $language['code'] . $url)),
                    'code' => $language['code'],
                ];
            }

            $result['url'] = $urls;
            $result['title'] = $category_info['name'];
        } else {
            $result = [
                'error' => true,
                'title' => $this->language->get('heading_title'),
                'description' => $this->config->get('config_meta_description'),
                'meta_description' => $this->config->get('config_meta_description'),
                'url' => null,
                'breadcrumb' => [],
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => str_replace('&amp;', '&', $this->url->link('common/home', 'language=' . $this->config->get('config_language')))
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('heading_title'),
            ];
        }

        return $result;
    }

    /**
     * Compresses opening hours into a more compact format by identifying
     * contiguous days with the same opening hours.
     *
     * This method processes an array of days and their corresponding
     * opening hours (AM and PM) and groups consecutive days with the
     * same hours into a single entry. If there are days without
     * specified hours, those are ignored, and any ongoing range is
     * finalized.
     *
     * @param array $days An associative array where each key is a day
     *                    (e.g., 'Monday', 'Tuesday') and each value
     *                    is an associative array containing 'am' and
     *                    'pm' opening hours.
     *
     * @return array An array of compacted opening hour ranges. Each
     *               entry contains the formatted string representation
     *               of the range, e.g., 'Monday to Tuesday: 09:00 - 17:00'.
     */
    private function compactOpeningHours(array $days): array
    {
        $result = [];
        $prev_am = $prev_pm = '';
        $range_start = '';
        $last_day = '';

        foreach ($days as $day => $hours) {
            $current_am = $hours['am'];
            $current_pm = $hours['pm'];

            // Skip days without set hours
            if ($this->_strlen(trim($current_am)) === 0 && $this->_strlen(trim($current_pm)) === 0) {
                if ($range_start !== '') {
                    $result[] = $this->formatRange($range_start, $last_day, $prev_am, $prev_pm);
                }
                $range_start = '';
                continue;
            }

            // If hours are the same, extend the range
            if ($current_am === $prev_am && $current_pm === $prev_pm) {
                $last_day = $day;
            } else {
                // If we have a range, store it
                if ($range_start !== '') {
                    $result[] = $this->formatRange($range_start, $last_day, $prev_am, $prev_pm);
                }

                // Start a new range
                $range_start = $day;
                $last_day = $day;
            }

            $prev_am = $current_am;
            $prev_pm = $current_pm;
        }

        // Add the final range
        if ($range_start !== '') {
            $result[] = $this->formatRange($range_start, $last_day, $prev_am, $prev_pm);
        }

        return $result;
    }

    /**
     * Formats a range of days and times into a readable string.
     *
     * This method generates a string representation of a range of days
     * from a start day to an end day, along with specified AM and PM times.
     * If the start day and end day are the same, it returns a single day
     * with the specified time range. Otherwise, it returns the range of
     * days with the specified time.
     *
     * @param string $start_day The starting day of the range (e.g., 'monday').
     * @param string $end_day The ending day of the range (e.g., 'friday').
     * @param string $am The starting time in AM (e.g., '09:00').
     * @param string $pm The ending time in PM (e.g., '17:00').
     *
     * @return string A formatted string indicating the range of days and times.
     */
    private function formatRange(string $start_day, string $end_day, string $am, string $pm): string
    {
        $dayNames = [
            'monday' => 'Mo',
            'tuesday' => 'Tu',
            'wednesday' => 'We',
            'thursday' => 'Th',
            'friday' => 'Fr',
            'saturday' => 'Sa',
            'sunday' => 'Su',
        ];

        if ($start_day === $end_day) {
            return $dayNames[$start_day] . " $am-$pm";
        } else {
            return $dayNames[$start_day] . '-' . $dayNames[$end_day] . " $am-$pm";
        }
    }

    /**
     * Normalize a description string by removing HTML tags, decoding HTML entities,
     * and replacing multiple whitespace characters with a single space.
     *
     * This method takes a raw description string, processes it to ensure that
     * the output is clean and free of unnecessary formatting, which is especially
     * useful for displaying user-generated content or cleaning up input data.
     *
     * @param string $description The raw description string that needs normalization.
     *
     * @return string The normalized description with HTML tags stripped, HTML entities decoded,
     *                and excess whitespace reduced to a single space.
     */
    private function normalizeDescription(string $description): string
    {
        return trim(
            preg_replace(
                ['/[\r\n\t]+/', '/\s+/i'], // Combine newlines, tabs, and spaces
                [' ', ' '],            // Replace them with single space or empty string
                strip_tags(html_entity_decode($description, ENT_QUOTES, 'UTF-8')) // Decode entities and strip tags
            )
        );
    }

    /**
     * Retrieves the contents of a template file based on the provided route.
     *
     * This method checks if an event template buffer is provided. If so, it returns that buffer.
     * If not, it constructs the template file path based on the current theme settings and checks
     * for the existence of the template file. If the file exists, it reads and returns its contents.
     * It supports loading templates from both the specified theme directory and the default template directory.
     *
     * @param string $route The route for which the template is being retrieved.
     *                      This should match the naming convention for the template files.
     * @param string $event_template_buffer The template buffer that may be passed from an event.
     *                                       If provided, this buffer will be returned directly,
     *                                       bypassing file retrieval.
     *
     * @return mixed Returns the contents of the template file as a string if it exists,
     *               or false if the template file cannot be found or read.
     */
    protected function getTemplateBuffer(string $route, string $event_template_buffer): mixed
    {
        if ($event_template_buffer) {
            return $event_template_buffer;
        }

        if (defined('DIR_CATALOG')) {
            $dir_template = DIR_TEMPLATE;
        } else {
            if ($this->config->get('config_theme') == 'default') {
                $theme = $this->config->get('theme_default_directory');
            } else {
                $theme = $this->config->get('config_theme');
            }

            $dir_template = DIR_TEMPLATE . $theme . '/template/';
        }

        $template_file = $dir_template . $route . '.twig';

        if (file_exists($template_file) && is_file($template_file)) {
            $template_file = $this->modCheck($template_file);

            return file_get_contents($template_file);
        }

        if (defined('DIR_CATALOG')) {
            return false;
        }

        $dir_template = DIR_TEMPLATE . 'default/template/';
        $template_file = $dir_template . $route . '.twig';

        if (file_exists($template_file) && is_file($template_file)) {
            $template_file = $this->modCheck($template_file);

            return file_get_contents($template_file);
        }

        // Support for OC4 catalog
        $dir_template = DIR_TEMPLATE;
        $template_file = $dir_template . $route . '.twig';

        if (file_exists($template_file) && is_file($template_file)) {
            $template_file = $this->modCheck($template_file);

            return file_get_contents($template_file);
        }

        return false;
    }

    /**
     * Checks and modifies the provided file path based on predefined directory constants.
     *
     * This method checks if the file path starts with specific directory constants (`DIR_MODIFICATION`,
     * `DIR_APPLICATION`, and `DIR_SYSTEM`). Depending on these conditions, it modifies the file path to
     * point to the appropriate directory under `DIR_MODIFICATION`.
     *
     * - If the file path starts with `DIR_MODIFICATION`, it checks if it should point to either the
     *   `admin` or `catalog` directory based on the definition of `DIR_CATALOG`.
     * - If `DIR_CATALOG` is defined, the method checks for the file in the `admin` directory.
     *   Otherwise, it checks in the `catalog` directory.
     * - If the file path starts with `DIR_SYSTEM`, it checks for the file in the `system` directory
     *   within `DIR_MODIFICATION`.
     *
     * The method ensures that the returned file path exists before modifying it.
     *
     * @param string $file The original file path to check and modify.
     * @return string|null The modified file path if found, or null if it does not exist.
     */
    protected function modCheck(string $file): mixed
    {
        if (defined('DIR_MODIFICATION')) {
            if ($this->startsWith($file, DIR_MODIFICATION)) {
                if (defined('DIR_CATALOG')) {
                    if (file_exists(DIR_MODIFICATION . 'admin/' . substr($file, strlen(DIR_APPLICATION)))) {
                        $file = DIR_MODIFICATION . 'admin/' . substr($file, strlen(DIR_APPLICATION));
                    }
                } else {
                    if (file_exists(DIR_MODIFICATION . 'catalog/' . substr($file, strlen(DIR_APPLICATION)))) {
                        $file = DIR_MODIFICATION . 'catalog/' . substr($file, strlen(DIR_APPLICATION));
                    }
                }
            } elseif ($this->startsWith($file, DIR_SYSTEM)) {
                if (file_exists(DIR_MODIFICATION . 'system/' . substr($file, strlen(DIR_SYSTEM)))) {
                    $file = DIR_MODIFICATION . 'system/' . substr($file, strlen(DIR_SYSTEM));
                }
            }
        }

        return $file;
    }

    /**
     * Checks if a given string starts with a specified substring.
     *
     * This method determines if the string $haystack begins with the substring $needle.
     *
     * @param string $haystack The string to be checked.
     * @param string $needle The substring to search for at the beginning of $haystack.
     *
     * @return bool Returns true if $haystack starts with $needle; otherwise, false.
     */
    protected function startsWith(string $haystack, string $needle): bool
    {
        if (strlen($haystack) < strlen($needle)) {
            return false;
        }

        return (substr($haystack, 0, strlen($needle)) == $needle);
    }

    /**
     * Replaces specific occurrences of a substring in a string with a new substring.
     *
     * This method searches for all occurrences of a specified substring ($search) in a given string ($string)
     * and replaces the occurrences at the positions specified in the $nthPositions array with a new substring ($replace).
     *
     * @param string $search The substring to search for in the string.
     * @param string $replace The substring to replace the found occurrences with.
     * @param string $string The input string in which replacements will be made.
     * @param array $nthPositions An array of positions (1-based index) indicating which occurrences
     *                            of the search substring to replace.
     *
     * @return mixed The modified string with the specified occurrences replaced, or the original string if no matches are found.
     */
    protected function replaceNth(string $search, string $replace, string $string, array $nthPositions): mixed
    {
        $pattern = '/' . preg_quote($search, '/') . '/';
        $matches = [];
        $count = preg_match_all($pattern, $string, $matches, PREG_OFFSET_CAPTURE);

        if ($count > 0) {
            foreach ($nthPositions as $nth) {
                if ($nth > 0 && $nth <= $count) {
                    $offset = $matches[0][$nth - 1][1];
                    $string = substr_replace($string, $replace, $offset, strlen($search));
                }
            }
        }

        return $string;
    }

    /**
     * Replaces placeholders in a template with corresponding values from the views array.
     *
     * This method retrieves the template content based on the given route and template name,
     * then replaces specified search strings with their corresponding replace strings.
     * If positions are specified, the method performs replacements only at those positions.
     *
     * @param string $route The route associated with the template.
     * @param string $template The name of the template to be processed.
     * @param array $views An array of associative arrays where each associative array contains:
     *                     - string 'search': The string to search for in the template.
     *                     - string 'replace': The string to replace the 'search' string with.
     *                     - array|null 'positions': (Optional) An array of positions
     *                     where replacements should occur. If not provided,
     *                     all occurrences will be replaced.
     *
     * @return mixed The modified template content after performing the replacements.
     */
    protected function replaceViews(string $route, string $template, array $views): mixed
    {
        $output = $this->getTemplateBuffer($route, $template);

        foreach ($views as $view) {
            if (isset($view['positions']) && $view['positions']) {
                $output = $this->replaceNth($view['search'], $view['replace'], $output, $view['positions']);
            } else {
                $output = str_replace($view['search'], $view['replace'], $output);
            }
        }

        return $output;
    }

    /**
     * Get the length of a string while ensuring compatibility across OpenCart versions.
     *
     * This method returns the length of the provided string. It utilizes different
     * string length functions based on the OpenCart version being used to ensure
     * accurate handling of UTF-8 characters.
     *
     * - For OpenCart versions before 4.0.1.0, it uses `utf8_strlen()`.
     * - For OpenCart versions from 4.0.1.0 up to (but not including) 4.0.2.0,
     *   it uses `\Opencart\System\Helper\Utf8\strlen()`.
     * - For OpenCart version 4.0.2.0 and above, it uses `oc_strlen()`.
     *
     * @param string $string The input string whose length is to be calculated.
     *
     * @return int The length of the input string.
     */
    private function _strlen(string $string): int
    {
        if (version_compare(VERSION, '4.0.1.0', '<')) { // OpenCart versions before 4.0.1.0
            return utf8_strlen($string);
        } elseif (version_compare(VERSION, '4.0.2.0', '<')) { // OpenCart version 4.0.1.0 up to, but not including, 4.0.2.0
            return \Opencart\System\Helper\Utf8\strlen($string);
        }

        return oc_strlen($string); // OpenCart version 4.0.2.0 and above
    }

    /**
     * Get a substring from a string while ensuring compatibility across OpenCart versions.
     *
     * This method returns a portion of the provided string. It utilizes different
     * substring functions based on the OpenCart version being used to ensure
     * accurate handling of UTF-8 characters.
     *
     * - For OpenCart versions before 4.0.1.0, it uses `utf8_substr()`.
     * - For OpenCart versions from 4.0.1.0 up to (but not including) 4.0.2.0,
     *   it uses `\Opencart\System\Helper\Utf8\substr()`.
     * - For OpenCart version 4.0.2.0 and above, it uses `substr()`.
     *
     * @param string $value The input string from which to extract the substring.
     * @param int $start The starting position of the substring.
     * @param int|null $length The length of the substring (optional).
     *
     * @return string The extracted substring.
     */
    private function _substr(string $value, int $start, ?int $length = null): string
    {
        if (version_compare(VERSION, '4.0.1.0', '<')) { // OpenCart versions before 4.0.1.0
            return utf8_substr($value, $start, $length);
        } elseif (version_compare(VERSION, '4.0.2.0', '<')) { // OpenCart version 4.0.1.0 up to, but not including, 4.0.2.0
            return \Opencart\System\Helper\Utf8\substr($value, $start, $length);
        }

        return substr($value, $start, $length); // OpenCart version 4.0.2.0 and above
    }
}
