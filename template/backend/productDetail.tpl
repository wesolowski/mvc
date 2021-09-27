{include file="base/header.tpl" title="Admin - Product's"}
<h1>Admin - Edit Product's</h1>
<hr/>
<p><a href="/index.php?area=Admin&page=CategoryDetail&categoryId={$categoryId}">Back to Category's</a></p>
<p>{$product->name}</p>
<form action="/index.php?area=Admin&page=ProductDetail&categoryId={$categoryId}&productId={$product->id}" method="post">
    <p><input type="text" name="updateName" value="{$product->name}"></p>
    <p><input style="width: 50px;" type="text" name="updatePrice" value="{$product->price}"> €</p>
    <p><textarea name="updateDescription">{$product->description}</textarea></p>
    <p>
        <input type="submit" name="updateProduct" value="Update">
        <input type="submit" name="removeProductFromCategory" value="Remove from Category">
        <input type="submit" name="deleteProduct" value="Delete">
    </p>
</form>
<p>{$error|default: ''}</p>

{include file="base/footer.tpl"}