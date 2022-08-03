<?php

namespace app\controllers\site;

use app\models\Shop;
use app\models\Product;
use system\library\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $data = array();
        return $this->render('site', 'Home/index.twig', $data);
    }
    public function indexShop($token, $shop_name)
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $shop_id = $this->decodeProductId($token);
        $product = new Shop();
        $item = $product->getSingleShopData($shop_id);
        $link = _env('APP_URL', "https://cagura.rw/") . "shop/view/" . $token . "/" . $shop_name;
        $data = array(
            'link' => $link,
            'shop' => $item
        );
        return $this->render('site', 'Home/index_shop.twig', $data);
    }
    public function indexProduct($token, $product_name)
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $product_id = $this->decodeProductId($token);
        $product = new Product();
        $item = $product->getProductItem($product_id);
        $link = _env('APP_URL', "https://cagura.rw/") . "product/view/" . $token . "/" . $product_name;
        $data = array(
            'link' => $link,
            'product' => $item
        );
        return $this->render('site', 'Home/index_product.twig', $data);
    }

    public function encodeProductId($id)
    {
        $encode = $id + 2020 * 2020;
        $encode = str_replace("", "c", $encode);
        $encode = str_replace("", "a", $encode);
        return $encode;
    }
    public function decodeProductId($encode)
    {
        $decode = str_replace("c", "", $encode);
        $decode = str_replace("a", "", $decode);
        $decode = (int) $decode - 2020 * 2020;
        return $decode;
    }
    public function email()
    {
        $data = array();
        //return $this->render('site', 'Pages/reset_email.twig', $data);
        //return $this->render('site', 'Pages/verify_email.twig', $data);
    }
};