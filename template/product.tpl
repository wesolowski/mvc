{include file="base/header.tpl" title="Product - {$category->categoryname}"}
<h1>Category - {$category->categoryname}</h1>
<hr/>
<a href="../index_old.php">Back to Category's</a>
<p>Products</p>
<ul>
    {foreach from=$productList item=product}
        <li><a href="/index.php?area=Consumer&page=ProductDetail&categoryID={$category->id}&productID={$product->id}">{$product->productname}</a></li>
    {/foreach}
</ul>
{include file="base/footer.tpl"}