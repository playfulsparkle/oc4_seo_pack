<?php
namespace Opencart\Admin\Controller\Extension\PsSeoPack\Module;
/**
 * Class PsSeoPack
 *
 * @package Opencart\Admin\Controller\Extension\PsSeoPack\Module
 */
class PsSeoPack extends \Opencart\System\Engine\Controller
{
    /**
     * @var string The support email address.
     */
    const EXTENSION_EMAIL = 'support@playfulsparkle.com';

    /**
     * @var string The documentation URL for the extension.
     */
    const EXTENSION_DOC = 'https://playfulsparkle.com/en-us/resources/downloads/';

    /**
     * @return void
     */
    public function index(): void
    {
        $this->load->language('extension/ps_seo_pack/module/ps_seo_pack');

        $this->load->model('localisation/language');
        $this->load->model('localisation/currency');
        $this->load->model('localisation/stock_status');
        $this->load->model('setting/store');
        $this->load->model('setting/setting');
        $this->load->model('tool/image');


        if (isset($this->request->get['store_id'])) {
            $store_id = (int) $this->request->get['store_id'];
        } else {
            $store_id = 0;
        }


        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/ps_seo_pack', 'user_token=' . $this->session->data['user_token'], true),
        ];


        // Store defaults
        $data['config_name'] = $this->config->get('config_name');
        $data['config_owner'] = $this->config->get('config_owner');
        $data['config_address'] = $this->config->get('config_address');
        $data['config_meta_description'] = $this->config->get('config_meta_description');
        $data['config_geocode'] = explode(',', $this->config->get('config_geocode'));


        $config = $this->model_setting_setting->getSetting('module_ps_seo_pack', $store_id);

        $data['module_ps_seo_pack_status'] = isset($config['module_ps_seo_pack_status']) ? (bool) $config['module_ps_seo_pack_status'] : false;
        $data['module_ps_seo_pack_store_name'] = isset($config['module_ps_seo_pack_store_name']) ? $config['module_ps_seo_pack_store_name'] : [];
        $data['module_ps_seo_pack_store_owner'] = isset($config['module_ps_seo_pack_store_owner']) ? $config['module_ps_seo_pack_store_owner'] : [];
        $data['module_ps_seo_pack_store_description'] = isset($config['module_ps_seo_pack_store_description']) ? $config['module_ps_seo_pack_store_description'] : [];
        $data['module_ps_seo_pack_postal_address'] = isset($config['module_ps_seo_pack_postal_address']) ? $config['module_ps_seo_pack_postal_address'] : [];
        $data['module_ps_seo_pack_location_address'] = isset($config['module_ps_seo_pack_location_address']) ? $config['module_ps_seo_pack_location_address'] : [];
        $data['module_ps_seo_pack_alternate_store_name'] = isset($config['module_ps_seo_pack_alternate_store_name']) ? $config['module_ps_seo_pack_alternate_store_name'] : [];
        $data['module_ps_seo_pack_same_as'] = isset($config['module_ps_seo_pack_same_as']) ? $config['module_ps_seo_pack_same_as'] : [];
        $data['module_ps_seo_pack_price_range'] = isset($config['module_ps_seo_pack_price_range']) ? $config['module_ps_seo_pack_price_range'] : '';
        $data['module_ps_seo_pack_geo_coordinates'] = isset($config['module_ps_seo_pack_geo_coordinates']) ? $config['module_ps_seo_pack_geo_coordinates'] : [];
        $data['module_ps_seo_pack_opening_hour'] = isset($config['module_ps_seo_pack_opening_hour']) ? $config['module_ps_seo_pack_opening_hour'] : [];
        $data['module_ps_seo_pack_contact_point'] = isset($config['module_ps_seo_pack_contact_point']) ? $config['module_ps_seo_pack_contact_point'] : [];
        $data['module_ps_seo_pack_return_policy'] = isset($config['module_ps_seo_pack_return_policy']) ? $config['module_ps_seo_pack_return_policy'] : 0;
        $data['module_ps_seo_pack_return_policies'] = isset($config['module_ps_seo_pack_return_policies']) ? $config['module_ps_seo_pack_return_policies'] : [];
        $data['module_ps_seo_pack_sdm_stock_status'] = isset($config['module_ps_seo_pack_sdm_stock_status']) ? $config['module_ps_seo_pack_sdm_stock_status'] : 0;
        $data['module_ps_seo_pack_sdm_stock_status_assoc'] = isset($config['module_ps_seo_pack_sdm_stock_status_assoc']) ? $config['module_ps_seo_pack_sdm_stock_status_assoc'] : [];
        $data['module_ps_seo_pack_shipping_rate'] = isset($config['module_ps_seo_pack_shipping_rate']) ? $config['module_ps_seo_pack_shipping_rate'] : 0;
        $data['module_ps_seo_pack_shipping_rates'] = isset($config['module_ps_seo_pack_shipping_rates']) ? $config['module_ps_seo_pack_shipping_rates'] : [];
        $data['module_ps_seo_pack_sdm'] = isset($config['module_ps_seo_pack_sdm']) ? $config['module_ps_seo_pack_sdm'] : 0;
        $data['module_ps_seo_pack_dublin_core'] = isset($config['module_ps_seo_pack_dublin_core']) ? $config['module_ps_seo_pack_dublin_core'] : 0;
        $data['module_ps_seo_pack_open_graph'] = isset($config['module_ps_seo_pack_open_graph']) ? $config['module_ps_seo_pack_open_graph'] : 0;
        $data['module_ps_seo_pack_twitter'] = isset($config['module_ps_seo_pack_twitter']) ? $config['module_ps_seo_pack_twitter'] : 0;
        $data['module_ps_seo_pack_store_language_code'] = isset($config['module_ps_seo_pack_store_language_code']) ? $config['module_ps_seo_pack_store_language_code'] : $this->config->get('config_language');
        $data['module_ps_seo_pack_facebook_app_id'] = isset($config['module_ps_seo_pack_facebook_app_id']) ? $config['module_ps_seo_pack_facebook_app_id'] : '';
        $data['module_ps_seo_pack_twitter_handle'] = isset($config['module_ps_seo_pack_twitter_handle']) ? $config['module_ps_seo_pack_twitter_handle'] : '';
        $data['module_ps_seo_pack_twitter_card_type'] = isset($config['module_ps_seo_pack_twitter_card_type']) ? $config['module_ps_seo_pack_twitter_card_type'] : '';
        $data['module_ps_seo_pack_item_condition'] = isset($config['module_ps_seo_pack_item_condition']) ? $config['module_ps_seo_pack_item_condition'] : 0;
        $data['module_ps_seo_pack_item_condition_assoc'] = isset($config['module_ps_seo_pack_item_condition_assoc']) ? $config['module_ps_seo_pack_item_condition_assoc'] : [];
        $data['module_ps_seo_pack_open_graph_stock_status'] = isset($config['module_ps_seo_pack_open_graph_stock_status']) ? $config['module_ps_seo_pack_open_graph_stock_status'] : 0;
        $data['module_ps_seo_pack_open_graph_stock_status_assoc'] = isset($config['module_ps_seo_pack_open_graph_stock_status_assoc']) ? $config['module_ps_seo_pack_open_graph_stock_status_assoc'] : [];


        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $image = isset($config['module_ps_seo_pack_image']) ? $config['module_ps_seo_pack_image'] : '';

        if ($this->_strlen(trim($image)) === 0) {
            $data['module_ps_seo_pack_image'] = [
                'image' => '',
                'thumb' => $data['placeholder'],
            ];
        } else {
            if (is_file(DIR_IMAGE . $image)) {
                $data['module_ps_seo_pack_image'] = [
                    'image' => $image,
                    'thumb' => $this->model_tool_image->resize($image, 100, 100),
                ];
            } else {
                $data['module_ps_seo_pack_image'] = [
                    'image' => '',
                    'thumb' => $data['placeholder'],
                ];
            }
        }

        $separator = version_compare(VERSION, '4.0.2.0', '>=') ? '.' : '|';

        $data['action'] = $this->url->link('extension/ps_seo_pack/module/ps_seo_pack' . $separator . 'save', 'user_token=' . $this->session->data['user_token']);
        $data['fix_event_handler'] = $this->url->link('extension/ps_seo_pack/module/ps_seo_pack' . $separator . 'fixEventHandler', 'user_token=' . $this->session->data['user_token']);
        $data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

        $data['user_token'] = $this->session->data['user_token'];

        $data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['store_id'] = $store_id;

        $data['stores'] = [];

        $data['stores'][] = [
            'store_id' => 0,
            'name' => $this->config->get('config_name') . '&nbsp;' . $this->language->get('text_default'),
            'href' => $this->url->link('extension/ps_seo_pack/module/ps_seo_pack', 'user_token=' . $this->session->data['user_token'] . '&store_id=0'),
        ];

        $stores = $this->model_setting_store->getStores();

        foreach ($stores as $store) {
            $data['stores'][] = [
                'store_id' => $store['store_id'],
                'name' => $store['name'],
                'href' => $this->url->link('extension/ps_seo_pack/module/ps_seo_pack', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store['store_id']),
            ];
        }

        $data['currency_options'] = [];

        $currencies = $this->model_localisation_currency->getCurrencies();

        foreach ($currencies as $currency) {
            if ($currency['status']) {
                $data['currency_options'][] = [
                    'title' => $currency['title'],
                    'code' => $currency['code']
                ];
            }
        }

        $data['price_range_options'] = [
            '' => $this->language->get('text_select'),
            '$' => $this->language->get('text_price_range_0'),
            '$$' => $this->language->get('text_price_range_1'),
            '$$$' => $this->language->get('text_price_range_2'),
            '$$$$' => $this->language->get('text_price_range_3'),
        ];

        $data['stock_status_options'] = [
            $this->language->get('entry_stock_status_0'),
            $this->language->get('entry_stock_status_1'),
        ];

        $data['schema_org_options'] = [
            'InStock' => $this->language->get('entry_schema_org_option_instock'),
            'OutOfStock' => $this->language->get('entry_schema_org_option_outofstock'),
            'PreOrder' => $this->language->get('entry_schema_org_option_preorder'),
            'PreSale' => $this->language->get('entry_schema_org_option_presale'),
            'InStoreOnly' => $this->language->get('entry_schema_org_option_instoreonly'),
            'OnlineOnly' => $this->language->get('entry_schema_org_option_onlineonly'),
            'LimitedAvailability' => $this->language->get('entry_schema_org_option_limited'),
            'Discontinued' => $this->language->get('entry_schema_org_option_discontinued'),
            'SoldOut' => $this->language->get('entry_schema_org_option_soldout'),
        ];

        $data['open_graph_options'] = [
            'available for order' => $this->language->get('entry_open_graph_option_instock'),
            'out of stock' => $this->language->get('entry_open_graph_option_outofstock'),
            'backorder' => $this->language->get('entry_open_graph_option_backorder'),
        ];

        $data['twitter_card_type_options'] = [
            'summary' => $this->language->get('twitter_card_summary'),
            'summary_large_image' => $this->language->get('twitter_card_summary_large_image'),
        ];

        $data['return_policy_category_options'] = [
            'MerchantReturnFiniteReturnWindow' => $this->language->get('text_merchant_return_finite_return_window'),
            'MerchantReturnNotPermitted' => $this->language->get('text_merchant_return_not_permitted'),
            'MerchantReturnUnlimitedWindow' => $this->language->get('text_merchant_return_unlimite_dwindow'),
            'MerchantReturnUnspecified' => $this->language->get('text_merchant_return_unspecified'),
        ];

        $data['return_method_options'] = [
            '' => $this->language->get('text_none'),
            'KeepProduct' => $this->language->get('text_keep_product'),
            'ReturnAtKiosk' => $this->language->get('text_return_at_kiosk'),
            'ReturnByMail' => $this->language->get('text_return_by_mail'),
            'ReturnInStore' => $this->language->get('text_return_in_store'),
        ];

        $data['return_fee_options'] = [
            '' => $this->language->get('text_none'),
            'FreeReturn' => $this->language->get('text_free_return'),
            'OriginalShippingFees' => $this->language->get('text_original_shipping_fees'),
            'RestockingFees' => $this->language->get('text_restocking_fees'),
            'ReturnFeesCustomerResponsibility' => $this->language->get('text_return_fees_customer_responsibility'),
            'ReturnShippingFees' => $this->language->get('text_return_shipping_fees'),
        ];

        $data['item_conditions'] = [
            'NewCondition' => $this->language->get('text_item_condition_new'),
            'UsedCondition' => $this->language->get('text_item_condition_used'),
            'RefurbishedCondition' => $this->language->get('text_item_condition_refurbished'),
            'DamagedCondition' => $this->language->get('text_item_condition_damaged'),
        ];

        $data['item_condition_options'] = [
            $this->language->get('text_item_condition_always_new'),
            $this->language->get('text_item_condition_product_field'),
        ];

        $data['text_contact'] = sprintf($this->language->get('text_contact'), self::EXTENSION_EMAIL, self::EXTENSION_EMAIL, self::EXTENSION_DOC);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/ps_seo_pack/module/ps_seo_pack', $data));
    }

    /**
     * Retrieves a list of countries based on a name filter for autocomplete functionality.
     *
     * This method checks for a 'filter_name' parameter in the request. If present, it uses
     * this value to filter the countries. It loads the country model to fetch the filtered
     * list of countries, returning only the first five results. The method constructs a JSON
     * response containing the names and ISO codes of the matched countries.
     *
     * @return void
     */
    public function countryautocomplete(): void
    {
        $json = [];

        if (isset($this->request->get['filter_name'])) {
            $filter_name = trim($this->request->get['filter_name']);
        } else {
            $filter_name = '';
        }

        if ($this->_strlen($filter_name) > 0) {
            $this->load->model('localisation/country');

            $filter_data = [
                'filter_name' => $filter_name,
                'start' => 0,
                'limit' => 5,
            ];

            $countries = $this->model_localisation_country->getCountries($filter_data);

            foreach ($countries as $country) {
                $json[] = [
                    'name' => $country['name'],
                    'iso_code_2' => $country['iso_code_2'],
                ];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    /**
     * Saves the settings for the PS SEO Pack module.
     *
     * This method validates the input data from the request, checking for necessary permissions
     * and required fields. If all validations pass, it saves the settings to the database.
     * The method returns a JSON response containing success or error messages.
     *
     * The following validations are performed:
     * - User permission check: Ensures the user has the 'modify' permission for the SEO Pack module.
     * - Required fields validation:
     *   - Checks for the presence of 'store_id'.
     *   - Ensures store name, store owner, and store description are provided for each language.
     * - Postal and location addresses: Validates fields if 'module_ps_seo_pack_sdm' is set.
     * - Contact points: Checks for empty values or missing data.
     * - Shipping rates: Validates the shipping rate values and destinations.
     * - Return policies: Validates return policies against defined categories and ensures proper values are provided.
     *
     * On successful validation:
     * - Saves the settings using the model's editSetting method.
     * - Returns a success message in JSON format.
     *
     * On validation failure:
     * - Returns an error message in JSON format with details of the validation issues.
     *
     * @return void
     */
    public function save(): void
    {
        $this->load->language('extension/ps_seo_pack/module/ps_seo_pack');

        $json = [];

        if (!$this->user->hasPermission('modify', 'extension/ps_seo_pack/module/ps_seo_pack')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            if (!isset($this->request->post['store_id'])) {
                $json['error'] = $this->language->get('error_store_id');
            }
        }

        if (!$json) {
            if (isset($this->request->post['module_ps_seo_pack_store_name'])) {
                foreach ($this->request->post['module_ps_seo_pack_store_name'] as $language_id => $value) {
                    if ($this->_strlen(trim(($value))) === 0) {
                        $json['error']['input-store-name-' . $language_id] = $this->language->get('error_store_name');
                    }
                }
            }

            if (isset($this->request->post['module_ps_seo_pack_store_owner'])) {
                foreach ($this->request->post['module_ps_seo_pack_store_owner'] as $language_id => $value) {
                    if ($this->_strlen(trim(($value))) === 0) {
                        $json['error']['input-store-owner-' . $language_id] = $this->language->get('error_store_owner');
                    }
                }
            }

            if (isset($this->request->post['module_ps_seo_pack_store_description'])) {
                foreach ($this->request->post['module_ps_seo_pack_store_description'] as $language_id => $value) {
                    if ($this->_strlen(trim(($value))) === 0) {
                        $json['error']['input-store-description-' . $language_id] = $this->language->get('error_store_description');
                    }
                }
            }

            if (isset($this->request->post['module_ps_seo_pack_sdm']) && (bool) $this->request->post['module_ps_seo_pack_sdm']) {
                if (isset($this->request->post['module_ps_seo_pack_postal_address'])) {
                    foreach ($this->request->post['module_ps_seo_pack_postal_address'] as $language_id => $input_fields) {
                        foreach ($input_fields as $key => $value) {
                            if ($this->_strlen(trim(($value))) === 0) {
                                $json['error']['input-postal-address-' . strtr($key, '_', '-') . '-' . $language_id] = $this->language->get('error_postal_address_' . $key);
                            }
                        }
                    }
                }

                if (isset($this->request->post['module_ps_seo_pack_location_address'])) {
                    foreach ($this->request->post['module_ps_seo_pack_location_address'] as $language_id => $input_fields) {
                        foreach ($input_fields as $key => $value) {
                            if ($this->_strlen(trim($value)) === 0) {
                                $json['error']['input-location-address-' . strtr($key, '_', '-') . '-' . $language_id] = $this->language->get('error_location_address_' . $key);
                            }
                        }
                    }
                }

                if (isset($this->request->post['module_ps_seo_pack_contact_point'])) {
                    foreach ($this->request->post['module_ps_seo_pack_contact_point'] as $row_id => $input_fields) {
                        foreach ($input_fields as $field_name => $field_value) {
                            if ($this->_strlen(trim($field_value)) === 0) {
                                $json['error']['input-contact-point-' . strtr($field_name, '_', '-') . '-' . $row_id] = $this->language->get('error_contact_point_' . $field_name);
                            }
                        }
                    }
                } else {
                    $json['error']['input-contact-point'] = $this->language->get('error_contact_point');
                }
            }

            if (isset($this->request->post['module_ps_seo_pack_open_graph']) && (bool) $this->request->post['module_ps_seo_pack_open_graph']) {
                if ($this->_strlen(trim($this->request->post['module_ps_seo_pack_facebook_app_id'])) === 0) {
                    $json['error']['input-facebook-app-id'] = $this->language->get('error_facebook_app_id');
                }
            }

            if (isset($this->request->post['module_ps_seo_pack_twitter']) && (bool) $this->request->post['module_ps_seo_pack_twitter']) {
                if ($this->_strlen(trim($this->request->post['module_ps_seo_pack_twitter_handle'])) === 0) {
                    $json['error']['input-twitter-handle'] = $this->language->get('error_twitter_handle');
                }
            }

            if (isset($this->request->post['module_ps_seo_pack_shipping_rates'])) {
                foreach ($this->request->post['module_ps_seo_pack_shipping_rates'] as $row_id => $data) {
                    if ($data['rate'] <= 0) {
                        $json['error']['input-shipping-rate-rate-' . $row_id] = $this->language->get('error_shipping_rate_rate');
                    }

                    if ($this->_strlen(trim($data['destination'])) === 0 || $this->_strlen(trim($data['destination_id'])) === 0) {
                        $json['error']['input-shipping-rate-destination-' . $row_id] = $this->language->get('error_shipping_rate_destination');
                    }

                    if ($data['handling_time_min'] <= 0) {
                        $json['error']['input-shipping-rate-handling-time-min-' . $row_id] = $this->language->get('error_shipping_rate_handling_time_min');
                    }

                    if ($data['handling_time_max'] <= 0) {
                        $json['error']['input-shipping-rate-handling-time-max-' . $row_id] = $this->language->get('error_shipping_rate_handling_time_max');
                    }

                    if ($data['transit_time_min'] <= 0) {
                        $json['error']['input-shipping-rate-transit-time-min-' . $row_id] = $this->language->get('error_shipping_rate_transit_time_min');
                    }

                    if ($data['transit_time_max'] <= 0) {
                        $json['error']['input-shipping-rate-transit-time-max-' . $row_id] = $this->language->get('error_shipping_rate_transit_time_max');
                    }
                }
            }

            if (isset($this->request->post['module_ps_seo_pack_return_policies'])) {
                $policy_map = [
                    'MerchantReturnFiniteReturnWindow' => [
                        'allowed_methods' => ['KeepProduct', 'ReturnAtKiosk', 'ReturnByMail', 'ReturnInStore'],
                        'allowed_fees' => ['FreeReturn', 'OriginalShippingFees', 'RestockingFees', 'ReturnFeesCustomerResponsibility', 'ReturnShippingFees']
                    ],
                    'MerchantReturnNotPermitted' => [
                        'allowed_methods' => [''], // No methods allowed
                        'allowed_fees' => [''] // No fees apply
                    ],
                    'MerchantReturnUnlimitedWindow' => [
                        'allowed_methods' => ['KeepProduct', 'ReturnAtKiosk', 'ReturnByMail', 'ReturnInStore'],
                        'allowed_fees' => ['FreeReturn', 'OriginalShippingFees', 'RestockingFees', 'ReturnFeesCustomerResponsibility', 'ReturnShippingFees']
                    ],
                    'MerchantReturnUnspecified' => [
                        'allowed_methods' => ['', 'KeepProduct', 'ReturnAtKiosk', 'ReturnByMail', 'ReturnInStore'],
                        'allowed_fees' => ['', 'FreeReturn', 'OriginalShippingFees', 'RestockingFees', 'ReturnFeesCustomerResponsibility', 'ReturnShippingFees']
                    ]
                ];

                foreach ($this->request->post['module_ps_seo_pack_return_policies'] as $row_id => $data) {
                    $return_policy_category = isset($data['return_policy_category']) ? $data['return_policy_category'] : '';
                    $return_method = isset($data['return_method']) ? $data['return_method'] : '';
                    $return_days = isset($data['return_days']) ? (int) $data['return_days'] : 0;
                    $return_fee = isset($data['return_fee']) ? $data['return_fee'] : '';

                    if ($this->_strlen(trim($data['country'])) === 0 || $this->_strlen(trim($data['country_id'])) === 0) {
                        $json['error']['input-return-policy-country-' . $row_id] = $this->language->get('error_return_country');
                    }

                    if (isset($policy_map[$return_policy_category])) {
                        $allowed_methods = $policy_map[$return_policy_category]['allowed_methods'];
                        $allowed_fees = $policy_map[$return_policy_category]['allowed_fees'];

                        if (!in_array($return_method, $allowed_methods)) {
                            $json['error']['input-return-policy-return-method-' . $row_id] = $this->language->get('error_return_method');
                        }

                        if ($return_policy_category === 'MerchantReturnNotPermitted') {
                            if ($return_days !== 0) {
                                $json['error']['input-return-policy-return-days-' . $row_id] = $this->language->get('error_return_days');
                            }
                        } else {
                            if ($return_days === 0) {
                                $json['error']['input-return-policy-return-days-' . $row_id] = $this->language->get('error_return_days');
                            }
                        }

                        if (!in_array($return_fee, $allowed_fees)) {
                            $json['error']['input-return-policy-return-fee-' . $row_id] = $this->language->get('error_return_fee');
                        }
                    }
                }
            }

            if ($json) {
                $json['error']['warning'] = $this->language->get('error_form');
            }
        }

        if (!$json) {
            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('module_ps_seo_pack', $this->request->post, $this->request->post['store_id']);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function fixEventHandler(): void
    {
        $this->load->language('extension/ps_seo_pack/module/ps_seo_pack');

        $json = [];

        if (!$this->user->hasPermission('modify', 'extension/ps_seo_pack/module/ps_seo_pack')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            $this->load->model('setting/event');

            $this->model_setting_event->deleteEventByCode('module_ps_seo_pack');

            $separator = version_compare(VERSION, '4.0.2.0', '>=') ? '.' : '|';

            if (version_compare(VERSION, '4.0.1.0', '>=')) {
                $result = $this->model_setting_event->addEvent([
                    'code' => 'module_ps_seo_pack',
                    'description' => '',
                    'trigger' => 'catalog/view/common/header/before',
                    'action' => 'extension/ps_seo_pack/module/ps_seo_pack' . $separator . 'eventCatalogViewCommonHeaderBefore',
                    'status' => '1',
                    'sort_order' => '0'
                ]);
            } else {
                $result = $this->model_setting_event->addEvent(
                    'module_ps_seo_pack',
                    'catalog/view/common/header/before',
                    'extension/ps_seo_pack/module/ps_seo_pack' . $separator . 'eventCatalogViewCommonHeaderBefore'
                );
            }

            if ($result > 0) {
                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_event');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    /**
     * Installs the SEO Pack module.
     *
     * This method checks if the current user has the permission to modify the SEO
     * Pack module settings. If permission is granted, it loads the required models
     * and sets the module's status to enabled. Additionally, it configures an event
     * trigger that allows the module to perform actions before the common header
     * of the catalog view is rendered, depending on the OpenCart version.
     *
     * @return void
     */
    public function install(): void
    {
        if ($this->user->hasPermission('modify', 'extension/ps_seo_pack/module/ps_seo_pack')) {
            $this->load->model('setting/event');

            $this->model_setting_event->deleteEventByCode('module_ps_seo_pack');

            $separator = version_compare(VERSION, '4.0.2.0', '>=') ? '.' : '|';

            if (version_compare(VERSION, '4.0.1.0', '>=')) {
                $this->model_setting_event->addEvent([
                    'code' => 'module_ps_seo_pack',
                    'description' => '',
                    'trigger' => 'catalog/view/common/header/before',
                    'action' => 'extension/ps_seo_pack/module/ps_seo_pack' . $separator . 'eventCatalogViewCommonHeaderBefore',
                    'status' => '1',
                    'sort_order' => '0'
                ]);
            } else {
                $this->model_setting_event->addEvent(
                    'module_ps_seo_pack',
                    'catalog/view/common/header/before',
                    'extension/ps_seo_pack/module/ps_seo_pack' . $separator . 'eventCatalogViewCommonHeaderBefore'
                );
            }
        }
    }

    /**
     * Uninstalls the SEO Pack module.
     *
     * This method removes the event associated with the `ps_seo_pack` module
     * if the user has the necessary permissions. It first checks whether the
     * user has permission to modify the extension, and if so, it proceeds
     * to load the event model and deletes the event.
     *
     * @return void
     */
    public function uninstall(): void
    {
        if ($this->user->hasPermission('modify', 'extension/ps_seo_pack/module/ps_seo_pack')) {
            $this->load->model('setting/event');

            $this->model_setting_event->deleteEventByCode('module_ps_seo_pack');
        }
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
}
