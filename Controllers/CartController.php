<?php
namespace Controllers;

class CartController  extends MainController{

    public function cart()
    {
        $data_page = [
            "page_description" => "Panier",
            "page_title" => "panier",
            "view" => "Views/panier.view.php",
            "template" => "Views/common/template.php"
        ];
        $this->newPage($data_page);
    }

    public function viewCart()
    {
        if (!empty($_SESSION["id_panier"])){
            $carts = $this->productsManager->viewCart($_SESSION["id_panier"]);
        } else {
            $carts = NULL;
        }
        $data_page = [
            "page_description" => "Panier",
            "page_title" => "panier",
            "view" => "Views/panier.view.php",
            "cart" => $carts,
            "template" => "Views/common/template.php"
        ];
        $this->newPage($data_page);
    }

    public function addToCart()
    {
        if (!empty($_SESSION["connected"]) && $_SESSION["connected"] === true){
            $id = $_GET["id"];
            $id_panier = bin2hex(openssl_random_pseudo_bytes(10)); //var_char aleatoire à 10 caractères
            if (empty($_SESSION["id_panier"])){
                $_SESSION["id_panier"] = $id_panier;
            }
            $cartStatus = $this->productsManager->viewStatusCart($_SESSION["id_panier"]);
            $oneProduct = $this->productsManager->showOneProduct($id);
            $aimCartProduct = $this->productsManager->aimViewCart($_SESSION["id_panier"], $id);
            var_dump($oneProduct[0]["stock"]);
            if (empty($aimCartProduct)){
                if (isset($_SESSION["id_panier"], $id, $_SESSION["idUser"])){
                    $this->productsManager->addToCart($_SESSION["id_panier"], $_SESSION["idUser"], $id);
                    if (empty($cartStatus["id_panier"])){
                        $this->productsManager->addToCartStatus($_SESSION["id_panier"], $_SESSION["idUser"]);
                    }
                    $_SESSION["alert"] = [
                        "message" => "Article bien ajouté à votre panier !",
                        "type" => SELF::ALERT_SUCCESS
                    ];
                    if (!empty($_POST["numberProduct"])){
                        if (isset($aimCartProduct[0]["quantity"])){
                            $addQuantity = $aimCartProduct[0]["quantity"] + $_POST["numberProduct"];
                            if ($addQuantity > $oneProduct[0]["stock"]){
                                $addQuantity = $oneProduct[0]["stock"];
                            }
                            if ($aimCartProduct[0]["quantity"] <= $oneProduct[0]["stock"] && $addQuantity <= $oneProduct[0]["stock"]){
                                $this->productsManager->addQuantityProduct($addQuantity, $id);
                            } else {
                                $_SESSION["alert"] = [
                                    "message" => "Nous n'avons pas assez de stock !",
                                    "type" => SELF::ALERT_DANGER
                                ];
                            }
                        } else {
                            $addQuantity = $_POST["numberProduct"];
                            if ($addQuantity <= $oneProduct[0]["stock"]){
                                $this->productsManager->addQuantityProduct($addQuantity, $id);
                            } else {
                                $_SESSION["alert"] = [
                                    "message" => "Nous n'avons pas assez de stock nous avons ajouté le maximum de produit à votre panier! ",
                                    "type" => SELF::ALERT_WARNING
                                ];
                                $addQuant = $oneProduct[0]["stock"];
                                $this->productsManager->addQuantityProduct($addQuant, $id);
                            }
                        }
                    }    
                } else {
                    $_SESSION["alert"] = [
                        "message" => "Nous n'avons pas assez de stock nous avons ajouté le maximum de produit à votre panier! ",
                        "type" => SELF::ALERT_WARNING
                    ];
                    $addQuantity = $oneProduct[0]["stock"];
                    $this->productsManager->addQuantityProduct($addQuantity, $id);
                }
            }else {
                    if (empty($_POST["numberProduct"])){
                        $addQuantity = $aimCartProduct[0]["quantity"] +1;
                    } else {
                        $addQuantity = $aimCartProduct[0]["quantity"] + $_POST["numberProduct"];
                    }
                if ($addQuantity <= $oneProduct[0]["stock"]){

                    $this->productsManager->addQuantityProduct($addQuantity, $id);
                    $_SESSION["alert"] = [
                        "message" => "Vous avez bien rajouté un exemplaire à cet article dans votre panier !",
                        "type" => SELF::ALERT_SUCCESS
                    ];
                } else {
                    $addQuantity = $oneProduct[0]["stock"];
                    $this->productsManager->addQuantityProduct($addQuantity, $id);
                    $_SESSION["alert"] = [
                        "message" => "Nous n'avons pas assez de stock 0!",
                        "type" => SELF::ALERT_DANGER
                    ];
                }
            }
        } else {
            $_SESSION["alert"] = [
                "message" => "Vous devez vous connecter avant d'ajouter des produits dans votre panier",
                "type" => SELF::ALERT_WARNING
            ];
        }
    }

    public function checkout()
    {
        $cart = $this->productsManager->viewCart($_SESSION["id_panier"]);
        $user = $this->usermanager->viewUser($_SESSION["login"]);
        var_dump($_SESSION["login"]);
        var_dump($_SESSION["idUser"]);
        $data_page = [
            "page_description" => "checkout",
            "page_title" => "checkout",
            "view" => "Views/checkout.view.php",
            "cart" => $cart,
            "user" => $user,
            "template" => "Views/common/template.php"
        ];
        $this->newPage($data_page);
    }

    public function deleteProductCart()
    {
        $_SESSION["alert"] = [
            "message" => "Vous avez bien supprimé l'article de votre panier !",
            "type" => SELF::ALERT_SUCCESS
        ];
        $this->productsManager->deleteProductCart($_SESSION["id_panier"], $_POST['idProduct']);    
    }

    public function success()
    {
        $viewStatusCart = $this->productsManager->viewStatusCart($_SESSION["id_panier"]);
        $cart = $this->productsManager->viewCart($_SESSION["id_panier"]);
        $stock = null;
        foreach ($cart as $carts){
            $product = $this->productsManager->showOneProduct($carts["id_produit"]);
            foreach ($product as $products){
                $stock = $products["stock"] - $carts["quantity"];
                $this->productsManager->updateStock($stock, $carts["id_produit"]);
            }
        }
        if (!empty($_SESSION["paid"]) && $_SESSION["paid"] == true){
            $this->productsManager->updateStatusCart(true, $_SESSION["id_panier"]);
        }
        if ($viewStatusCart["verrou"] == true && $_SESSION["paid"] == true){
            $_SESSION["alert"] = [
                "message" => "Votre achat a bien été effectué !",
                "type" => SELF::ALERT_SUCCESS
            ];
            $data_page = [
                "page_description" => "Success",
                "page_title" => "Success",
                "view" => "Views\stripeSuccess.view.php",
                "template" => "Views/common/template.php"
            ];
            $this->newPage($data_page);
            
            unset($_SESSION["id_panier"]);
            unset($_SESSION["paid"]);
            unset($_SESSION["payment_intent"]);

        } else {
            $_SESSION["alert"] = [
                "message" => "Veuillez payer votre panier ! :) ",
                "type" => SELF::ALERT_WARNING
            ];
            $data_page = [
                "page_description" => "erreur",
                "page_title" => "erreur",
                "view" => "Views\stripeError.view.php",
                "template" => "Views/common/template.php"
            ];
            $this->newPage($data_page);
        }
    }

    public function lockCart()
    {
        if (!empty($_SESSION["id_panier"])){
        $viewStatusCart = $this->productsManager->viewStatusCart($_SESSION["id_panier"]);
        }
        if (isset($_GET["page"]) && $_GET["page"] === "checkout" || $_GET["page"] === "stripeSuccess" && $viewStatusCart["verrou"] == false){
            $this->productsManager->lockCart(true, $_SESSION["id_panier"]);
        } else {
            if (!empty($_SESSION["id_panier"])){
                $this->productsManager->lockCart(false, $_SESSION["id_panier"]);
            }
        }
    }

}