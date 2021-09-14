{include file="base/header.tpl" title="Category"}
<h1>Home</h1>
<hr/>
<p>Category's</p>
<ul>
    {foreach from=$categoryList item=category}
        <li><a href="/index.php?page=p$Category&category={$category->id}${$category->categoryname}">{$category->categoryname}</a></li>
    {/foreach}
</ul>
{include file="base/footer.tpl"}