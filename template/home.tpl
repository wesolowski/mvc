{include file="base/header.tpl" title="Home"}
<h1>Home</h1>
<hr/>
<p>Products</p>
<ul>
    {foreach from=$categoryList item=category}
        <li><a href="/index.php?page=Detail&id={$category->id}">{$product->categoryname}</a></li>
    {/foreach}
</ul>
{include file="base/footer.tpl"}