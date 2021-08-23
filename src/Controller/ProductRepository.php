<?php
declare(strict_types=1);

namespace App\Controller;

class ProductRepository
{
    public function getList():array{
        $path = file_get_contents(__DIR__ . "/../Model/Product.json");
        $list = json_decode($path, true);
        if(json_last_error()){
            exit("json error: ".json_last_error_msg(). " (" . json_last_error() . ")");
        }
        return $list;

    }
    public function getProduct($id):string{
        $id--; //Da id im json mit 1 beginnt aber indexe bei 0 anfangen
        $list = $this->getList();
        $output = "<ul>";
        foreach ($list[$id] as $key => $value){
            $output .= "<li>".$key.": ".$value."</li>";
        }
        $output .= "</ul>";
        return $output;
    }
    public function hasProduct($product_name){
        $list = $this->getList();
        foreach ($list as $key => $value){
            if($product_name === $list[$key]['productname']){
                return "Product found!";
            }
        }
    }
}