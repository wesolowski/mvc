{include file="base/header.tpl" title="Home"}
<h1>Home</h1>
<hr/>
<p>Products</p>
<ul>
    {foreach from=$categoryList item=category}
        <li><a href="/index.php?page=c$Category&category={$category->categoryname}">{$category->categoryname}</a></li>
    {/foreach}
</ul>
{include file="base/footer.tpl"}