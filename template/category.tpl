{include file="base/header.tpl" title="Category"}
<h1>Category</h1>
<hr/>
<p>Category's</p>
<ul>
    {foreach from=$categoryDTOList item=categoryDTO}
        <li><a href="/index.php?area=Consumer&page=Product&categoryId={$categoryDTO->id}">{$categoryDTO->name}</a></li>
    {/foreach}
</ul>
{include file="base/footer.tpl"}