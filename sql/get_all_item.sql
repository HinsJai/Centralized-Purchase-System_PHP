-- Active: 1687437372148@@127.0.0.1@3306@projectdb
SELECT
    ITEM.*,
    SUPPLIER.COMPANYNAME
FROM
    ITEM
    INNER JOIN SUPPLIER
    ON ITEM.SUPPLIERID = SUPPLIER.SUPPLIERID
WHERE
    ITEM.SUPPLIERID = ?;