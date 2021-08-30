{include file="base/header.tpl" title="Home"}
<h1>Home</h1>
<hr/>
<p>Products</p>
<ul>
    {foreach from=$productList item=product}
        <li><a href="/index.php?page=Detail&id={$product->id}">{$product->productname}</a></li>
    {/foreach}
</ul>
{include file="base/footer.tpl"}