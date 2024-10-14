# Playful Sparkle - SEO Pack Extension for OpenCart 4

The Playful Sparkle - SEO Pack enhances the SEO of your OpenCart webshop by adding essential structured data and metadata. With support for multiple stores and languages, this extension helps improve search engine visibility and optimize content for social media engagement.

## Key Features
- **Structured Markup Data (LD+JSON)**: Provides rich snippets for prices, reviews, ratings, can display both regular and discount price and more.
- **Dublin Core**: Adds Dublin Core metadata to improve content discoverability.
- **Open Graph**: Helps control how your content appears when shared on social media.
- **Twitter Cards**: Configures metadata to control how content appears in Twitter feeds.
- **Multi-store and multi-language support**: Ensures seamless integration across different stores and languages.
- **Customizable Settings**: Store name, owner, description, logo, and stock status can all be adjusted.

### Important note

OpenCart requires all extension package filenames to end in the .ocmod.zip format for successful installation. The Playful Sparkle - SEO Pack extension must follow this naming convention to ensure compatibility with OpenCart's installer.

### 1. Download the Extension
Download the latest **Playful Sparkle - SEO Pack** release from this repository.

### 2. Upload the Extension Files
1. Log in to your OpenCart admin panel.
2. Navigate to `Extensions > Installer`.
3. Click the `Upload` button and upload the `ps_seo_pack.ocmod.zip` file.

### 3. Install the Extension
4. Once uploaded, click on the green `Install` button.
5. Navigate to `Extensions` and select `Modules` from the dropdown.
6. Locate the **Playful Sparkle - SEO Pack** extension in the list.
7. Click on the green `Install` button.

### 4. Configure the Extension
1. After installation, remain on the `Extensions` page and ensure `Modules` is selected from the dropdown.
2. Click the `Edit` button next to the installed extension.
3. Select your store from the upper-right corner.
4. Configure the extension as desired, enabling or disabling `Structured Markup Data`, `Dublin Core`, `Open Graph`, and `Twitter`.
5. Save your configuration.

### Settings
- **Structured Data Markup (LD+JSON)**:
  - Enable/Disable.
  - Customize rich snippets (prices, ratings, reviews, etc.).
  - Configure:
    - Store Address (Postal Address, Location Address).
    - Price Range.
    - GEO Coordinates.
    - Opening Hours (will be compacted eg.: Mo-Tu 07:00-17:00).
    - Social Media Links (sameAs).
    - Contact Points.
    - Stock Status (quantity-based or stock status-based).
    - Item Condition (new, used, refurbished, etc.).
    - Shipping method and return policy definitions.

- **Dublin Core Metadata**:
  - Enable/Disable Dublin Core tags for content discoverability.

- **Open Graph**:
  - Enable/Disable Open Graph metadata.
  - Customize Stock Status (based on quantity or stock status).
  - Set Facebook App ID for integration.
  - Display product information like (`product:brand`, `product:price:amount`, `product:price:currency`, `product:availability`), and custom labels (`article:tag`, `article:section`, `product:custom_label_x`).

- **Twitter Cards**:
  - Enable/Disable Twitter metadata.
  - Set Twitter handle, product information (`twitter:label`, `twitter:data`) and card type (summary or summary with large image).

## Language and Multi-store Support
The extension generates proper `<link>` tags for alternative pages for each language, including an `x-default` tag for the default language. You can also customize store-specific information such as store name, description, and logo for each store.

## Metadata Integration
The extension will automatically add the correct prefixes to the `<html>` tag based on the options you enable:
- **Dublin Core**: `dc: http://purl.org/dc/elements/1.1/`, `dcterms: http://purl.org/dc/terms/`, `dcmitype: http://purl.org/dc/dcmitype/`
- **Open Graph**: `og: https://ogp.me/ns#`, `article: https://ogp.me/ns/article#`

## Structured Data Details
- Supports display of regular and special prices.
- Displays rating and review information (if available).
- Includes rich structured data for web page information, read action property, web site information, alternate names, organization information, store information, same as property, opening hours, postal and location address, geo location information, price range, contact point informations, product information, product prices, shipping details, merchant return policies, breadcrumbs and more.

## Troubleshooting
- **Structured Data not displaying**: Ensure Structured Markup Data is enabled in the settings and that your product data is correctly filled out.
- **Metadata tags not appearing**: Verify that the corresponding Dublin Core, Open Graph, or Twitter options are enabled in the settings.

## Support & Feedback
After your purchase, you will receive free updates for the OpenCart 4 version. If future updates are released for the 4.x series, they will be available free of charge. If you encounter any problems or need assistance, you can request support via email at [support@playfulsparkle.com](support@playfulsparkle.com).
