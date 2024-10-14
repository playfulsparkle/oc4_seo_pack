<?php
namespace Opencart\Catalog\Controller\Extension\PsSeoPack\Module;

class PsSeoPack extends \Opencart\System\Engine\Controller
{
    public function eventCatalogViewCommonHeaderBefore(&$route, &$args, &$template)
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

        $this->load->model('localisation/language');
        $this->load->model('setting/setting');
        $this->load->model('extension/ps_seo_pack/module/ps_seo_pack');

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

        $config = $this->model_setting_setting->getSetting('module_ps_seo_pack', $config_store_id);

        $sdm_enabled = isset($config['module_ps_seo_pack_sdm']) ? (bool) $config['module_ps_seo_pack_sdm'] : false;
        $dublin_core_enabled = isset($config['module_ps_seo_pack_dublin_core']) ? (bool) $config['module_ps_seo_pack_dublin_core'] : false;

        $open_graph_enabled = isset($config['module_ps_seo_pack_open_graph']) ? (bool) $config['module_ps_seo_pack_open_graph'] : false;
        $facebook_app_id = isset($config['module_ps_seo_pack_facebook_app_id']) ? $config['module_ps_seo_pack_facebook_app_id'] : '';

        $twitter_enabled = isset($config['module_ps_seo_pack_twitter']) ? (bool) $config['module_ps_seo_pack_twitter'] : false;
        $twitter_handle = isset($config['module_ps_seo_pack_twitter_handle']) ? $config['module_ps_seo_pack_twitter_handle'] : '';
        $twitter_card_type = isset($config['module_ps_seo_pack_twitter_card_type']) ? $config['module_ps_seo_pack_twitter_card_type'] : '';

        $sdm_stock_status_enabled = isset($config['module_ps_seo_pack_sdm_stock_status']) ? (bool) $config['module_ps_seo_pack_sdm_stock_status'] : false;
        $sdm_stock_status_assocs = isset($config['module_ps_seo_pack_sdm_stock_status_assoc']) ? $config['module_ps_seo_pack_sdm_stock_status_assoc'] : [];

        $open_graph_stock_status_enabled = isset($config['module_ps_seo_pack_open_graph_stock_status']) ? (bool) $config['module_ps_seo_pack_open_graph_stock_status'] : false;
        $open_graph_stock_status_assocs = isset($config['module_ps_seo_pack_open_graph_stock_status_assoc']) ? $config['module_ps_seo_pack_open_graph_stock_status_assoc'] : [];

        $return_policy_enabled = isset($config['module_ps_seo_pack_return_policy']) ? (bool) $config['module_ps_seo_pack_return_policy'] : false;
        $return_policies = isset($config['module_ps_seo_pack_return_policies']) ? $config['module_ps_seo_pack_return_policies'] : [];

        $shipping_rate_enabled = isset($config['module_ps_seo_pack_shipping_rate']) ? (bool) $config['module_ps_seo_pack_shipping_rate'] : false;
        $shipping_rates = isset($config['module_ps_seo_pack_shipping_rates']) ? $config['module_ps_seo_pack_shipping_rates'] : [];

        $item_condition_enabled = isset($config['module_ps_seo_pack_item_condition']) ? (bool) $config['module_ps_seo_pack_item_condition'] : false;
        $item_condition_assocs = isset($config['module_ps_seo_pack_item_condition_assoc']) ? $config['module_ps_seo_pack_item_condition_assoc'] : [];

        $store_language_code = isset($config['module_ps_seo_pack_store_language_code']) ? $config['module_ps_seo_pack_store_language_code'] : false;

        $store_name = isset($config['module_ps_seo_pack_store_name']) ? $config['module_ps_seo_pack_store_name'] : [];

        if (isset($store_name[$config_language_id])) {
            $store_name = $store_name[$config_language_id];
        } else {
            $store_name = '';
        }

        $alternate_store_names = isset($config['module_ps_seo_pack_alternate_store_name']) ? $config['module_ps_seo_pack_alternate_store_name'] : [];

        if (isset($alternate_store_names[$config_language_id])) {
            $alternate_store_names = $alternate_store_names[$config_language_id];
        } else {
            $alternate_store_names = [];
        }

        $store_owner = isset($config['module_ps_seo_pack_store_owner']) ? $config['module_ps_seo_pack_store_owner'] : [];

        if (isset($store_owner[$config_language_id])) {
            $store_owner = $store_owner[$config_language_id];
        } else {
            $store_owner = '';
        }

        $store_description = isset($config['module_ps_seo_pack_store_description']) ? $config['module_ps_seo_pack_store_description'] : [];

        if (isset($store_description[$config_language_id])) {
            $store_description = $store_description[$config_language_id];
        } else {
            $store_description = '';
        }

        $store_image = isset($config['module_ps_seo_pack_image']) ? $config['module_ps_seo_pack_image'] : '';

        if (is_file(DIR_IMAGE . $store_image)) {
            $store_image_url = $this->model_tool_image->resize($store_image, 1500, 1500);
        } else {
            $store_image_url = $this->model_tool_image->resize('no_image.png', 1500, 1500);
        }

        $same_as = isset($config['module_ps_seo_pack_same_as']) ? $config['module_ps_seo_pack_same_as'] : [];

        $opening_hour = isset($config['module_ps_seo_pack_opening_hour']) ? $config['module_ps_seo_pack_opening_hour'] : [];

        $postal_address = isset($config['module_ps_seo_pack_postal_address']) ? $config['module_ps_seo_pack_postal_address'] : [];

        if (isset($postal_address[$config_language_id])) {
            $postal_address = $postal_address[$config_language_id];
        } else {
            $postal_address = [];
        }

        $location_address = isset($config['module_ps_seo_pack_location_address']) ? $config['module_ps_seo_pack_location_address'] : [];

        if (isset($location_address[$config_language_id])) {
            $location_address = $location_address[$config_language_id];
        } else {
            $location_address = [];
        }

        $geo_coordinates = isset($config['module_ps_seo_pack_geo_coordinates']) ? $config['module_ps_seo_pack_geo_coordinates'] : [];

        $price_range = isset($config['module_ps_seo_pack_price_range']) ? $config['module_ps_seo_pack_price_range'] : '';

        $contact_points = isset($config['module_ps_seo_pack_contact_point']) ? $config['module_ps_seo_pack_contact_point'] : [];


        $result = [];

        if ($ps_seo_pack_route === 'common/home') {
            $result = $this->getCommonHome($languages);
        } else if ($ps_seo_pack_route === 'account/login') {
            $result = $this->getAccountLogin($languages);
        } else if ($ps_seo_pack_route === 'account/register') {
            $result = $this->getAccountRegister($languages);
        } else if ($ps_seo_pack_route === 'account/forgotten') {
            $result = $this->getAccountForgotten($languages);
        } else if ($ps_seo_pack_route === 'product/manufacturer.info') {
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

            if (!empty($result['meta_description'])) {
                $short_description = oc_substr($result['meta_description'], 0, 150);
            } else if (!empty($result['description'])) {
                $short_description = oc_substr($result['description'], 0, 150);
            } else {
                $short_description = '';
            }

            if (!empty($result['meta_description'])) {
                $long_description = oc_substr($result['meta_description'], 0, 1000);
            } else if (!empty($result['description'])) {
                $long_description = oc_substr($result['description'], 0, 1000);
            } else {
                $long_description = '';
            }

            #region Dublin Core
            if ($dublin_core_enabled) {
                $html_prefix[] = 'dc: http:/purl.org/dc/elements/1.1/';
                $html_prefix[] = 'dcterms: http://purl.org/dc/terms/';
                $html_prefix[] = 'dcmitype: http://purl.org/dc/dcmitype/';

                $args['ps_seo_pack_dublincores'] = [
                    'DC.Type' => 'InteractiveResource',
                    'DC.Title' => $this->document->getTitle(), // Default
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
                    [
                        'property' => 'og:type',
                        'content' => 'website'
                    ],
                    [
                        'property' => 'og:locale',
                        'content' => $this->language->get('code')
                    ],
                    [
                        'property' => 'og:title',
                        'content' => $this->document->getTitle()
                    ],
                    [
                        'property' => 'og:site_name',
                        'content' => $store_name
                    ],
                    [
                        'property' => 'og:description',
                        'content' => $short_description
                    ],
                    [
                        'property' => 'og:url',
                        'content' => $current_url
                    ],
                    [
                        'property' => 'fb:app_id',
                        'content' => $facebook_app_id
                    ],
                    [
                        'property' => 'article:publisher',
                        'content' => $this->config->get('config_url')
                    ],
                ];

                if (
                    isset($result['date_added'], $result['date_modified']) &&
                    $result['date_added'] !== '0000-00-00 00:00:00' &&
                    $result['date_modified'] !== '0000-00-00 00:00:00'
                ) {
                    $args['ps_seo_pack_opengraphs'][] = [
                        'property' => 'article:published_time',
                        'content' => date('Y-m-d\TH:i:sP', strtotime($result['date_added']))
                    ];
                    $args['ps_seo_pack_opengraphs'][] = [
                        'property' => 'article:modified_time',
                        'content' => date('Y-m-d\TH:i:sP', strtotime($result['date_modified']))
                    ];
                }

                if ($ps_seo_pack_route === 'product/product' && !$result['error']) {
                    foreach ($result['tags'] as $tag) {
                        $args['ps_seo_pack_opengraphs'][] = [
                            'property' => 'article:tag',
                            'content' => $tag
                        ];
                    }

                    if ($result['category']) {
                        $args['ps_seo_pack_opengraphs'][] = [
                            'property' => 'article:section',
                            'content' => $result['category']
                        ];
                    }

                    $args['ps_seo_pack_opengraphs'][] = [
                        'property' => 'product:brand',
                        'content' => $result['manufacturer']
                    ];

                    $args['ps_seo_pack_opengraphs'][] = [
                        'property' => 'product:price:amount',
                        'content' => $result['price']
                    ];

                    $args['ps_seo_pack_opengraphs'][] = [
                        'property' => 'product:price:currency',
                        'content' => $result['price_currency']
                    ];

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

                    $args['ps_seo_pack_opengraphs'][] = [
                        'property' => 'product:availability',
                        'content' => $json_availability
                    ];

                    foreach ($result['images'] as $images) {
                        $args['ps_seo_pack_opengraphs'][] = ['property' => 'og:image', 'content' => $images];
                    }

                    foreach ($result['additional_property'] as $index => $additional_property) {
                        $args['ps_seo_pack_opengraphs'][] = ['property' => 'product:custom_label_' . $index, 'content' => $additional_property['name'] . ' ' . $additional_property['text']];
                    }
                } else {
                    $args['ps_seo_pack_opengraphs'][] = ['property' => 'og:image', 'content' => $store_image_url];
                }
            }
            #endregion

            #region Twitter
            if ($twitter_enabled) {
                $args['ps_seo_pack_twitters'] = [
                    ['name' => 'twitter:site', 'content' => $twitter_handle],
                    ['name' => 'twitter:title', 'content' => $this->document->getTitle()],
                    ['name' => 'twitter:description', 'content' => $short_description],
                    ['name' => 'twitter:card', 'content' => $twitter_card_type],
                ];

                if ($ps_seo_pack_route === 'product/product' && !$result['error']) {
                    foreach ($result['images'] as $images) {
                        $args['ps_seo_pack_twitters'][] = ['name' => 'twitter:image', 'content' => $store_image_url];
                    }

                    foreach ($result['additional_property'] as $index => $additional_property) {
                        $args['ps_seo_pack_twitters'][] = ['name' => 'twitter:label' . $index, 'content' => $additional_property['name']];
                        $args['ps_seo_pack_twitters'][] = ['name' => 'twitter:data' . $index, 'content' => $additional_property['text']];
                    }
                } else {
                    $args['ps_seo_pack_twitters'][] = ['name' => 'twitter:image', 'content' => $store_image_url];
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
                    'name' => $this->document->getTitle(),
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
                                'urlTemplate' => $this->url->link('product/search', 'language=' . $this->config->get('config_language') . '&search=') . '{search_term_string}',
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

                    if ($item_condition_enabled && !empty($result['location'])) {
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
                        'image' => $result['images'],
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
                                'shippingDestination' => [
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

                        if (!empty($all_rating_values)) {
                            $average_rating = array_sum($all_rating_values) / count($all_rating_values);
                        } else {
                            $average_rating = 0; // Handle case with no ratings
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

            $args['ps_seo_pack_html_prefix'] = implode(' ', $html_prefix);
        }


        // echo '<details><pre>';
        // print_r($this->request->get);
        // echo '</pre></details>';

        // if ($ps_seo_pack_route === 'product/manufacturer.info') {
        //     $args['title'] = $this->language->get('text_brand') . ': ' . $this->document->getTitle() . ' | ' . $store_owner;
        // } else {
        $args['title'] = $this->document->getTitle() . ' | ' . $store_owner;
        // }

        if ($config_description = $this->document->getDescription()) {
            $args['description'] = oc_substr($config_description, 0, 150);
        } else if ($short_description) {
            $args['description'] = $short_description;
        } else if ($long_description) {
            $args['description'] = oc_substr($long_description, 0, 150);
        }

        $headerViews = $this->model_extension_ps_seo_pack_module_ps_seo_pack->replaceHeaderViews($args);

        $template = $this->replaceViews($route, $template, $headerViews);
    }

    private function getInformationContact($languages): array
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => $this->url->link('information/contact', 'language=' . $language['code']),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'description' => '',
            'meta_description' => '',
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('heading_title'),
        ];

        return $result;
    }

    private function getCommonHome($languages): array
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => $this->url->link('common/home', 'language=' . $language['code']),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
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

    private function getAccountLogin($languages)
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => $this->url->link('account/login', 'language=' . $language['code']),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'description' => '',
            'meta_description' => '',
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_account'),
            'item' => $this->url->link('account/account', 'language=' . $this->config->get('config_language'))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_login'),
        ];

        return $result;
    }

    private function getAccountRegister($languages)
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => $this->url->link('account/register', 'language=' . $language['code']),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'description' => '',
            'meta_description' => '',
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_account'),
            'item' => $this->url->link('account/account', 'language=' . $this->config->get('config_language'))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_register'),
        ];

        return $result;
    }

    private function getAccountForgotten($languages)
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => $this->url->link('account/forgotten', 'language=' . $language['code']),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'description' => '',
            'meta_description' => '',
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language')),
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_account'),
            'item' => $this->url->link('account/account', 'language=' . $this->config->get('config_language')),
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_forgotten'),
        ];

        return $result;
    }

    private function getProductManufacturerInfo($languages)
    {
        if (isset($this->request->get['manufacturer_id'])) {
            $manufacturer_id = (int) $this->request->get['manufacturer_id'];
        } else {
            $manufacturer_id = 0;
        }

        $result = [];

        $this->load->model('catalog/manufacturer');

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
                    'href' => $this->url->link('product/manufacturer.info', 'language=' . $language['code'] . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url),
                    'code' => $language['code'],
                ];
            }

            $result = [
                'error' => false,
                'description' => '',
                'meta_description' => '',
                'url' => $urls,
                'breadcrumb' => [],
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_brand'),
                'item' => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language'))
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $manufacturer_info['name'],
            ];
        } else {
            $result = [
                'error' => true,
                'description' => '',
                'meta_description' => '',
                'url' => null,
                'breadcrumb' => [],
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('heading_title'),
            ];
        }

        return $result;
    }

    private function getProductManufacturer($languages)
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => $this->url->link('product/manufacturer', 'language=' . $language['code']),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'description' => '',
            'meta_description' => '',
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_brand'),
        ];

        return $result;
    }

    private function getInformationSitemap($languages)
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => $this->url->link('information/sitemap', 'language=' . $language['code']),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'description' => '',
            'meta_description' => '',
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('heading_title'),
        ];

        return $result;
    }

    private function getCheckoutCart($languages)
    {
        $result = [];

        $urls = [];

        foreach ($languages as $language) {
            $urls[$language['code']] = [
                'href' => $this->url->link('checkout/cart', 'language=' . $language['code']),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'description' => '',
            'meta_description' => '',
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('heading_title'),
        ];

        return $result;
    }

    private function getProductSearch($languages)
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
                'href' => $this->url->link('product/search', 'language=' . $language['code'] . $url),
                'code' => $language['code'],
            ];
        }

        $result = [
            'error' => false,
            'description' => '',
            'meta_description' => '',
            'url' => $urls,
            'breadcrumb' => [],
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('text_frontpage'),
            'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
        ];

        $result['breadcrumb'][] = [
            '@type' => 'ListItem',
            'name' => $this->language->get('heading_title'),
        ];

        return $result;
    }

    private function getProductProduct($languages)
    {
        if (isset($this->request->get['product_id'])) {
            $product_id = (int) $this->request->get['product_id'];
        } else {
            $product_id = 0;
        }

        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/review');

        $result = [];

        $product_info = $this->model_catalog_product->getProduct($product_id);

        if ($product_info) {
            $images = [];

            if (is_file(DIR_IMAGE . html_entity_decode($product_info['image'], ENT_QUOTES, 'UTF-8'))) {
                $images[] = $this->model_tool_image->resize(html_entity_decode($product_info['image'], ENT_QUOTES, 'UTF-8'), $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
            }

            $other_images = $this->model_catalog_product->getImages($product_id);

            foreach (array_slice($other_images, 0, 10) as $other_image) {
                if (is_file(DIR_IMAGE . html_entity_decode($other_image['image'], ENT_QUOTES, 'UTF-8'))) {
                    $images[] = $this->model_tool_image->resize(html_entity_decode($other_image['image'], ENT_QUOTES, 'UTF-8'), $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
                }
            }

            $length_unit = $this->model_extension_ps_seo_pack_module_ps_seo_pack->getLengthClass($product_info['length_class_id']);
            $weight_unit = $this->model_extension_ps_seo_pack_module_ps_seo_pack->getWeightClass($product_info['weight_class_id']);

            $urls = [];

            foreach ($languages as $language) {
                $urls[$language['code']] = [
                    'href' => $this->url->link('product/product', 'language=' . $language['code'] . '&product_id=' . $product_id),
                    'code' => $language['code'],
                ];
            }

            $result = [
                'error' => false,
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
                'price_currency' => $this->session->data['currency'],
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
                $normal_price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

                $result['price'] = (float) preg_replace('/[^0-9\.,]/', '', $normal_price);
            } else {
                $result['price'] = false;
            }

            if ((float) $product_info['special']) {
                $special_price = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

                $result['special'] = (float) preg_replace('/[^0-9\.,]/', '', $special_price);
                $result['special_valid_until'] = $this->model_extension_ps_seo_pack_module_ps_seo_pack->getSpecialPriceDatesByProductId($product_id);
            } else {
                $result['special'] = false;
                $result['special_valid_until'] = '';
            }



            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
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
                            'item' => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $path)
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
                        'item' => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $this->request->get['path'] . $url)
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
                    'item' => $this->url->link('product/manufacturer', 'language=' . $this->config->get('config_language'))
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
                        'item' => $this->url->link('product/manufacturer.info', 'language=' . $this->config->get('config_language') . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
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
                    'item' => $this->url->link('product/search', 'language=' . $this->config->get('config_language') . $url)
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
                'item' => $this->url->link('product/product', 'language=' . $this->config->get('config_language') . $url . '&product_id=' . $product_id)
            ];
        } else {
            $result = [
                'error' => true,
                'description' => '',
                'meta_description' => '',
                'url' => null,
                'breadcrumb' => [],
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('heading_title'),
            ];
        }

        return $result;
    }

    private function getInformationInformation($languages)
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
                    'href' => $this->url->link('information/information', 'language=' . $language['code'] . '&information_id=' . $information_id),
                    'code' => $language['code'],
                ];
            }

            $result = [
                'error' => false,
                'description' => $this->normalizeDescription($information_info['description']),
                'meta_description' => $this->normalizeDescription($information_info['meta_description']),
                'url' => $urls,
                'breadcrumb' => [],
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $information_info['title']
            ];
        } else {
            $result = [
                'error' => true,
                'description' => '',
                'meta_description' => '',
                'url' => null,
                'breadcrumb' => [],
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('heading_title'),
            ];
        }

        return $result;
    }

    private function getProductCategory($languages): array
    {
        if (isset($this->request->get['path'])) {
            $path = (string) $this->request->get['path'];
        } else {
            $path = '';
        }

        $parts = explode('_', $path);

        $category_id = (int) array_pop($parts);

        $this->load->model('catalog/category');

        $result = [];

        $category_info = $this->model_catalog_category->getCategory($category_id);

        if ($category_info) {
            $result = [
                'error' => false,
                'description' => $this->normalizeDescription($category_info['description']),
                'meta_description' => $this->normalizeDescription($category_info['meta_description']),
                'url' => null,
                'date_added' => $category_info['date_added'],
                'date_modified' => $category_info['date_modified'],
                'breadcrumb' => [],
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
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
                    if (empty($result['description'])) {
                        $result['description'] = $this->normalizeDescription($sub_category_info['description']);
                    }

                    $result['breadcrumb'][] = [
                        '@type' => 'ListItem',
                        'name' => $sub_category_info['name'],
                        'item' => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . '&path=' . $path . $url)
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
                'item' => $this->url->link('product/category', 'language=' . $this->config->get('config_language') . $url)
            ];

            $urls = [];

            foreach ($languages as $language) {
                $urls[$language['code']] = [
                    'href' => $this->url->link('product/category', 'language=' . $language['code'] . $url),
                    'code' => $language['code'],
                ];
            }

            $result['url'] = $urls;
        } else {
            $result = [
                'error' => true,
                'description' => '',
                'meta_description' => '',
                'url' => null,
                'breadcrumb' => [],
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('text_frontpage'),
                'item' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
            ];

            $result['breadcrumb'][] = [
                '@type' => 'ListItem',
                'name' => $this->language->get('heading_title'),
            ];
        }

        return $result;
    }

    private function compactOpeningHours($days)
    {
        $result = [];
        $prev_am = $prev_pm = '';
        $range_start = '';
        $last_day = '';

        foreach ($days as $day => $hours) {
            $current_am = $hours['am'];
            $current_pm = $hours['pm'];

            // Skip days without set hours
            if (empty($current_am) && empty($current_pm)) {
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

    // Helper function to format a range like "Mo-Fr 08:00-17:00"
    private function formatRange($start_day, $end_day, $am, $pm)
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

    private function normalizeDescription($description, $max_length = 1000): string
    {
        return trim(
            preg_replace(
                ['/[\r\n\s\t]+/', '/&[a-z]+;/i'], // Combine newlines, tabs, and spaces; handle HTML entities
                [' ', ''],                        // Replace them with single space or empty string
                strip_tags(html_entity_decode($description, ENT_QUOTES, 'UTF-8')) // Decode entities and strip tags
            )
        );
    }

    protected function getTemplateBuffer($route, $event_template_buffer)
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

    protected function modCheck($file)
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

    protected function startsWith($haystack, $needle)
    {
        if (strlen($haystack) < strlen($needle)) {
            return false;
        }

        return (substr($haystack, 0, strlen($needle)) == $needle);
    }

    protected function replaceNth($search, $replace, $string, $nthPositions)
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

    protected function replaceViews($route, $template, $views)
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
}
