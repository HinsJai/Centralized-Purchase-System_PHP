-- SQLBook: Code
UPDATE ITEM
SET
    IMAGEFILE=?,
    ITEMDESCRIPTION=?,
    STOCKITEMQTY=?,
    PRICE=?
WHERE
    ITEMID = ?;