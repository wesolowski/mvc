{include file="base/header.tpl" title="Admin - Category's"}
<h1>Admin - Category's</h1>
<hr/>
<p>Category's</p>
<ul>
    {foreach from=$categoryList item=category}
        <li><a href="/index.php?page=ac$Category&category={$category->id}${$category->categoryname}">{$category->categoryname}</a></li>
    {/foreach}
</ul>
{include file="base/footer.tpl"}