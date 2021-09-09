{include file="base/header.tpl" title="Admin - Category's"}
<h1>Admin - Edit Category</h1>
<hr/>
<p><a href="/index.php?page=ac$Category">Back to Category's</a></p>
<p>{$category->categoryname}</p>
<form action="/index.php?page=ap$EditCategory&category={$category->id}${$newCategoryName}" method="post">
    <p><input type="text" name="newCategoryName" value="{$newCategoryName}"></p>
    <p>
        <input type="submit" name="updateCategory" value="Update">
        <input type="submit" name="deleteCategory" value="Delete">
    </p>
</form>
<p>Products</p>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Product</th>
        <th></th>
    </tr>
    {foreach from=$productList item=product}
        <tr>
            <td>{$product->id}</td>
            <td>{$product->productname}</td>
            <td><a href="/index.php?page=ap$EditProduct&category={$category->id}${$newCategoryName}&id={$product->id}">Update/Delete</a></td>
        </tr>
    {/foreach}
</table>

{include file="base/footer.tpl"}