<?php
/**
 * Created by PhpStorm.
 * User: Zoran
 * Date: 11/13/14
 * Time: 1:04 PM
 */

require_once 'utility.php';
require_once 'library/simple_html_dom.php';

class GoogleShopping {
    /**
     * Host name
     * @var string
     */
    private $host = 'https://www.google.nl';

    /**
     * Check digits length of EAN number and fill it with zero if necessary
     *
     * @param string $ean
     * @return string
     */
    private function checkDigitLen($ean) {
        // ean length is less than 14, fill it with zero
        if(strlen($ean) < 14) {
            $util = new utility();
            $ean = $util->fillZeroUoTo($ean, 14);
        }

        return $ean;
    }

    /**
     * find the first product link on this page
     *
     * @param DOM $html
     * @return string
     * @throws Exception
     */
    private function findProdLink($html) {
        // initialise variable
        $sub = "";

        // find all links
        foreach($html->find('a') as $element) {
            // find the first product link for /shopping/product/12115883691353094589
            if(strchr($element->href, "/shopping/product/12115883691353094589")) {
                // substring product link
                $sub = substr($element->href, 0, strlen("/shopping/product/12115883691353094589"));

                break;
            }
        }

        if(!isset($sub))
            throw new Exception("No one link exist.");

        return $sub;
    }

    /**
     * Create prices array
     *
     * @param DOM $downloadedProd
     * @return array
     */
    private function createPricesArray($downloadedProd) {
        // initialise variables
        $prods = array();
        $util = new utility();

        // get prices foreach table row
        foreach($downloadedProd->find('table[id=os-sellers-table].tr.os-row') as $prod) {
            // get seller and price
            $item['seller']    = trim($prod->find('os-row.td.os-seller-name', 0)->plaintext);
            $item['price']     = $util->tofloat($prod->find('os-row.td.os-total-col', 0)->plaintext);

            // push seller and price into the array
            $prods[] = $item;
        }

        return $prods;
    }

    /** 
     * Get prices by ean
     *
     * @param string $ean
     * @return array
     */
    public function getPrices($ean) {
    	// 1. check digits length of EAN number and fill it with zero if necessary
        $ean = $this->checkDigitLen($ean);

        // 2. request search page by url
        // Create DOM from URL
        $html = file_get_html($this->host . '/search?hl=nl&output=search&tbm=shop&q=' . $ean);

        // 3. find the first product link on this page
        $prodLink = $this->findProdLink($html);

        // 4. add /online?hl=nl string to the end of the product link and compose url for product link
        $prodLink .= '/online?hl=nl';
        $prodLink = $this->host . $prodLink; // https://www.google.nl/shopping/product/12115883691353094589/online?hl=nl

        // 5. download the product link
        $downloadedProd = file_get_html($prodLink);

        // 6. Create an array with the prices
        return $this->createPricesArray($downloadedProd);

    }
}
