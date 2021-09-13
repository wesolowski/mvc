{include file="base/header.tpl" title="Admin - Category's"}
<h1>Admin - Edit Category</h1>
<hr/>
<p><a href="/index.php?page=ac$Category">Back to Category's</a></p>
<p>{$category->categoryname}</p>
<form action="/index.php?page=ap$EditCategory&category={$category->id}${$editCategoryName}" method="post">
    <p><input type="text" name="editCategoryName" value="{$editCategoryName}"></p>
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
        <th>Description</th>
        <th></th>
    </tr>
    {foreach from=$productList item=product}
        <tr>
            <td>{$product->id}</td>
            <td>{$product->productname}</td>
            <td>{$product->description}</td>
            <td><a href="/index.php?page=ap$EditProduct&category={$category->id}${$editCategoryName}&id={$product->id}">Update/Delete</a></td>
        </tr>
    {/foreach}
    <tr>
        <form action="index.php?page=ap$EditCategory&category={$category->id}${$category->categoryname}" method="post">
            <td></td>
            <td><input type="text" name="newProductName" placeholder="Product" value="{$newProductName|default: ''}" </td>
            <td><input type="text" name="newProductDescription" placeholder="Description" value="{$newProductDescription|default: ''}" </td>
            <td><input type="submit" value="Create" name="createProduct"></td>
        </form>
    </tr>
</table>

{include file="base/footer.tpl"}