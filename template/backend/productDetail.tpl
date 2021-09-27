{include file="base/header.tpl" title="Admin - Product's"}
<h1>Admin - Edit Product's</h1>
<hr/>
<p><a href="/index.php?area=Admin&page=CategoryDetail&categoryID={$categoryID}">Back to Category's</a></p>
<p>{$product->productname}</p>
<form action="/index.php?area=Admin&page=ProductDetail&categoryID={$categoryID}&productID={$product->id}" method="post">
    <p><input type="text" name="editProductName" value="{$product->productname}"></p>
    <p><textarea name="editProductDescription">{$product->description}</textarea></p>
    <p>
        <input type="submit" name="updateProduct" value="Update">
        <input type="submit" name="removeProductFromCategory" value="Remove from Category">
        <input type="submit" name="deleteProduct" value="Delete">
    </p>
</form>
<p>{$error|default: ''}</p>

{include file="base/footer.tpl"}