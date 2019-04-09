<?php include('inc/pdo.php');
include('inc/requests.php');
include('inc/functions.php');

$error = array();
$success = false;

if (isset($_POST['submitted'])) {
    // Protection faille XSS
    $productname      = trim(strip_tags($_POST['productname']));
    $description      = trim(strip_tags($_POST['description']));
    $price            = trim(strip_tags($_POST['price']));
    $image            = trim(strip_tags($_POST['image']));

    // verification productname
    if (!empty($productname)){
        if(strlen($productname) < 3 ) {
            $error['productname'] = 'Your product\'s name is too short. (Min 3 characters)';
        } elseif(strlen($productname) > 255) {
            $error['productname'] = 'Your product\'s name is too long. (Max 255 characters)';
        }
    } else {
        $error['productname'] = 'Please enter your product\'s name.';
    }

    // verification description
    if (!empty($description)){
        if(strlen($description) < 3 ) {
            $error['description'] = 'Your product\'s description is too short. (Min 10 characters)';
        } elseif(strlen($description) > 255) {
            $error['description'] = 'Your product\'s description is too long. (Max 255 characters)';
        }
    } else {
        $error['description'] = 'Please enter your product\'s description.';
    }

    // verification price
    if (!isset($price)){
        $error['price'] = 'Please enter your product\'s price.';
    }

    // verification image
    if (!empty($description)){
        if(strlen($description) < 3 ) {
            $error['description'] = 'Your product\'s description is too short. (Min 10 characters)';
        } elseif(strlen($description) > 255) {
            $error['description'] = 'Your product\'s description is too long. (Max 255 characters)';
        }
    } else {
        $error['description'] = 'Please enter your product\'s description.';
    }

}




$title = 'Profil';
include('inc/header.php'); ?>


<h3>Add a product to your list</h3>

<form>
    <label for="productname">Product's name</label><br>
    <input type="text" name="productname" placeholder="What's your product ?" value=""><br><br>

    <label for="description">Product's description</label><br>
    <textarea name="description" placeholder="Describe your product" ></textarea><br><br>

    <label for="price">Product's price</label><br>
    <input type="number" name="price" placeholder="€" value=""><br><br>

    <label for="image">Product's picture</label><br>
    <input type="file" name="image">

    <input type="submit" name="submitted" value="Add this product">

</form>
