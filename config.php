<?php

/**
 * Define what sites are checked and also define strings that lead to "not avaliable"
 * The other erros are checked using headers in code
 *   404 is not found like https://www.gamerheadset.net/asads123
 *   200 is full ok like https://www.gamerheadset.net/artikel
 */

//@todo ebay and other affiliate network links
//need to collect data, need broken link examples for ebay.

$sites = array();

//REPLACE this url with yor sitemap.xml //@todo simple url list?
$sites['config']['sitemap'] = 'https://www.funkkopfhoerer-infos.de/testberichte/sitemap.xml';


/**
 * AMAZON Header codes
*  A broken link like: https://amzn.to/32WDPadadsC will redirect to the homepage amazon.com"
 * 302 is generated when url is wrong, temp moved to different url on amazon!
 * 301 is moved permanently. shortened amazon url will result in that code
 *
 * Amazon also throws error pages if an ASIN is invalid/deleted
 * Example: https://www.amazon.de/gp/product/B07MLR333QLXX/
*/

//do not use dots or !? #+ special chars!
//product pages have "dp" in url, video have "gp". Only works for products not for videos or other stuff!
$sites['amazon'] = array (
  'urls' => array('amazon.de', 'amzn.to', 'amazon.com'),
  'not_found_string'    => 'Seite wurde nicht gefunden',
  'not_avaliable'       => 'Ob und wann dieser Artikel wieder vorrätig sein wird, ist unbekannt',
  'product_page_url_string' => array('dp', 'gp'),
);


//@todo make search strings more unique? allow html or combination of multiple strings? remember escaping for regex if used
//@todo collect data for ebay or other affiliate sites
