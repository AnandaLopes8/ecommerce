SELECT * FROM tb_products WHERE idproduct IN(
	SELECT a.idproduct
	FROM tb_products a
	INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
	WHERE b.idcategory = 3
);