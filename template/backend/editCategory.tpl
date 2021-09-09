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
<ul>
    {foreach from=$productList item=product}
        <li><a href="/index.php?page=ap$EditProduct&category={$category->id}${$newCategoryName}&id={$product->id}">{$product->productname}</a></li>
    {/foreach}
</ul>

{include file="base/footer.tpl"}