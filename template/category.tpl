{include file="base/header.tpl" title="Category"}
<h1>Category</h1>
<hr/>
<p>Category's</p>
<ul>
    {foreach from=$categoryList item=category}
        <li><a href="/index.php?area=Consumer&page=Category&categoryID={$category->id}">{$category->categoryname}</a></li>
    {/foreach}
</ul>
{include file="base/footer.tpl"}