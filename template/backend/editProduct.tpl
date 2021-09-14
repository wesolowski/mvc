{include file="base/header.tpl" title="Admin - Product's"}
<h1>Admin - Edit Product's</h1>
<hr/>
<p><a href="/index_old.php?page=ap$EditCategory&category={$category}">Back to Category's</a></p>
<p>{$product->productname}</p>
<form action="/index_old.php?page=ap$EditProduct&category={$category}&id={$product->id}" method="post">
    <p><input type="text" name="editProductName" value="{$editProduct['name']|default: ''}"></p>
    <p><textarea name="editProductDescription">{$editProduct['description']|default: ''}</textarea></p>
    <p>
        <input type="submit" name="updateProduct" value="Update">
        <input type="submit" name="deleteProduct" value="Delete">
    </p>
</form>
<p>{$error|default: ''}</p>

{include file="base/footer.tpl"}