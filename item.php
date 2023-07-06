<?php
class Item
{
    public $itemID;
    public $companyName;
    public $supplierID;
    public $itemName;
    public $ImageFile;
    public $itemDescription;
    public $stockItemQty;
    public $price;

    function __construct(array $data)
    {
        $this->itemID = $data['itemID'];
        $this->companyName = $data['COMPANYNAME'];
        $this->supplierID = $data['supplierID'];
        $this->itemName = $data['itemName'];
        $this->ImageFile = $data['ImageFile'];
        $this->itemDescription = $data['itemDescription'];
        $this->stockItemQty = $data['stockItemQty'];
        $this->price = $data['price'];
    }
}