{include file="base/header.tpl" title="Category - {$category}"}
<h1>Category - {$category}</h1>
<hr/>
<p>Products</p>
<ul>
    {foreach from=$productList item=product}
        <li><a href="/index.php?page=p$Detail&category={$category}&id={$product->id}">{$product->productname}</a></li>
    {/foreach}
</ul>
{include file="base/footer.tpl"}