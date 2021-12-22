<?php
//Autor: Marco Kleine-Albers

/**
 * checking links of a sitemap for broken links, see config.php
 *
 * @var array $sites
 */

require 'config.php';
$results_dir = dirname(__FILE__) . '/results/results.html';

//get the content of the xml file
$urlContent = file_get_contents($sites['config']['sitemap']);

unset($sites['config']);
$providers = $sites;

//the website links we check
$links = extract_xmlsitemap_links($urlContent);

//save already checked links for more efficientcy
$checked_links = [];
$link_count = count($links);

foreach ($links as $url_key => $url) {

  //get the website link html code
  $webseite_data = check_url($url);
  $header = $webseite_data['headers']['http_code'];
  $website_code = $webseite_data['data'];

  //extract all links form that websites html code
  $website_links = extract_html_links($website_code);

  //@todo 302 redirects on amazon redirect wrong short urls to amazon.com? no?

  //check for all providers, see config.php
  foreach ($providers as $provider_name => $provider_data) {
    $urls_pattern = implode('|', $provider_data['urls']);

    //extract provider_urls
    $provider_links = extract_provider_links($website_links, $provider_data['urls']);

    //now we have all provider links and can loop these for checking
    foreach ($provider_links as $key => $provider_link) {

      //only check a provider link ONCE on a run!
      if (!in_array($provider_link, $checked_links)) {
        //add to checked list
        $checked_links[] = $provider_link;

        //check the links of provider
        $provider = "$provider_name";

        //we check if a product is not avaliable no more. We use the STRING from config.php for that
        $na_regex = '/' . $provider_data['not_avaliable'] . '/m';
        $error_regex = '/' . $provider_data['not_found_string'] . '/m';

        //now call the providers link/url!
        $provider_website_data = check_url($provider_link);

        //debug error page 404
        //$provider_website_data = check_url('https://www.amazon.de/gp/product/B08LKC334PPWM');
        //debug not avaliable
        //$provider_website_data = check_url('https://www.amazon.de/dp/B00AJWBAB2');
        //debug broken shortlink
        //$provider_website_data = check_url('https://amzn.to/3q13NPc');
        
        $provider_header = $provider_website_data['headers']['http_code'];
        $provider_code = $provider_website_data['data'];
        $provider_location = $provider_website_data['headers']['url'];

        //@todo wrong shortlinks automatically redirect to the amazon.com homepage...detect that
        //@todo if we have no povider date we are banned for a certain time?

        //only check on product pages containing xy. Example amazon has /dp/ in url for product pages
        //header: location
        if(array_search_partial($provider_data['product_page_url_string'], $provider_location) !== false  ) {
          $provider = "$provider product-page";

          //$test = preg_match($na_regex, $provider_code, $matches, PREG_OFFSET_CAPTURE, 0);
          if (preg_match($na_regex, $provider_code, $matches, PREG_OFFSET_CAPTURE, 0) == 1) {
            $provider = "$provider not-avaliable ";
          }
          //$test = preg_match($error_regex, $provider_code, $matches, PREG_OFFSET_CAPTURE, 0);
          if ( (preg_match($error_regex, $provider_code, $matches, PREG_OFFSET_CAPTURE, 0)) == 1 || $provider_header == 404) {
            $provider = "$provider error ";
          }

        }
        else {
          $provider = "$provider non-product-page";
        }
      }
      else {
        $provider = 'already checked skipped ';
      }

      $items['url_checks'][$url_key][] = ['url' => $url, 'provider_url' => $provider_link, 'header' => $header, 'provider' => $provider];

      //sleep after every link checked. Avoid hammering/throtteling bans

      //need to sleep on a link we already checked
      if($provider == 'already checked skipped ') {
        $rand = 0;
      }
      else {
        $rand = rand(5, 10);
      }

      $link_nr = $url_key+1;

      $message = "
      Process link number $link_nr of $link_count pages
      Processed url: $url
      Processed provider link: $provider_link
      Result: $provider
      Sleep $rand seconds
      --------------------------
      ";

      echo $message;

      //full log
      file_put_contents('full_log.txt', $message, FILE_APPEND);

      sleep($rand);
    }
  }
}

$stop=1;
echo "Finished processing all urls:\r\n";

//create html code from results array


$html_log = generate_html_log($items);

//write log as html
//delete file if already exists because we arite an ew log every time
file_put_contents($results_dir, $html_log);

/**
 * @param $arr
 * @param $keyword
 *
 *  Helper: Check a string if values of an array are present in teh string
 *
 * @return int|string
 */

function array_search_partial($arr, $haystack) {
  $result = false;
  foreach($arr as $index => $string) {
    if (strpos($haystack, $string) !== FALSE) {
      $result = true;
    }
  }

  return $result;
}


  /**
   * extract links for an url schema
   *
   * @param $link_array
   * @param $provider_schema
   */

  function extract_provider_links($link_array, $provider_schema) {
    $provider_links = [];
    foreach ($link_array as $key => $link) {
      foreach ($provider_schema as $pkey => $plink) {
        if (strpos($link, $plink) !== FALSE) {
          $provider_links[] = $link;
        }
      }

    }
    return $provider_links;

  }


  /**
   * extract all links from a sitemap.xml
   *
   * @param $urlContent 'content of xml file'
   *
   * @return array
   */

  function extract_xmlsitemap_links($urlContent) {
    $urls = [];

    if ($urlContent !== FALSE) {
      $DomDocument = new DOMDocument();
      $DomDocument->preserveWhiteSpace = FALSE;
      $DomDocument->loadXML($urlContent);
      $DomNodeList = $DomDocument->getElementsByTagName('url');

      foreach ($DomNodeList as $url) {
        $locs = $url->getElementsByTagName('loc');
        $loc = $locs->item(0)->nodeValue;
        $urls[] = $loc;
      }

      //save the urls we will check.
      file_put_contents('url_log.txt', implode(PHP_EOL, $urls));
    }

    return $urls;
  }

  /**
   * extract all links from a website
   */

  function extract_html_links($urlContent) {
    $urls = [];

    if ($urlContent !== FALSE) {
      $dom = new DOMDocument;
      @$dom->loadHTML($urlContent);

      $links = $dom->getElementsByTagName('a');
      foreach ($links as $link) {
        $urls[] = $link->getAttribute('href');
      }
    }

    return $urls;
  }


  /**
   * @param $url
   *
   * @return mixed
   *
   * check an url, get header code
   */

  function check_url($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_ENCODING , "gzip"); //page may be gzipped? most servers do
    $data = curl_exec($ch);
    $headers = curl_getinfo($ch);
    curl_close($ch);

    $result['data'] = $data;
    $result['headers'] = $headers;
    return $result;
  }


/**
 * create html code for logfile from url checked items array
 *
 * @param $data
 *
 * @return string
 */

  function generate_html_log($data) {
    $html = '<link rel="stylesheet" href="style.css">';
    $html .= '<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">';
    $html .= '<script src="../js/jquery-3.5.1.min.js"></script>';
    $html .= "<script src='../js/custom.js'></script>";

    $html .= "<button type='button' class='btn btn-primary hide-skipped'>Remove skipped</button>";
    $html .= "<button type='button' class='btn btn-primary hide-200'>Hide 200</button>";
    $html .= "<button type='button' class='btn btn-primary hide-301'>Hide 301</button>";

    $html .= "<table class='table table-striped table-bordered table-hover table-sm'>
            <thead>
                <tr>
                <th>Result Nr.</th>
                <th>Titel</th>
                <th>URL Check</th></tr>
             </thead>
            <tbody>";

    $check_nr = 1;

    foreach ($data['url_checks'] as $key => $items) {
      $html .= "<tr>";
      $html .= "<td>" . $check_nr . "</td>";
      $check_nr++;
      $html .= "<td><a href='" . $items[0]['url'] . "'>" . $items[0]['url'] . "</a></td>";

      $html .= "<td><ul>";
      foreach ($items as $ukey => $urldata) {
        $classes = $urldata['provider'] . ' header-' . $urldata['header'];
        $html .= "<li class='$classes'><a href='" . $urldata['provider_url'] . "' target='_blank'>" . $urldata['provider_url'] . "</a></li>";
      }
      $html .= "</ul></td>";

      $html .= "</tr>";
    }

    $html .= "</tbody>";
    $html .= "</table>";

    return $html;
  }