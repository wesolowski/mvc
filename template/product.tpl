{include file="base/header.tpl" title="Product - {$categoryDTO->name}"}
<h1>Category - {$categoryDTO->name}</h1>
<hr/>
<a href="../index.php">Back to Category's</a>
<p>Products</p>
<ul>
    {foreach from=$productDTOList item=productDTO}
        <li><a href="/index.php?area=Consumer&page=ProductDetail&categoryId={$categoryDTO->id}&productId={$productDTO->id}">{$productDTO->name}</a></li>
    {/foreach}
</ul>
{include file="base/footer.tpl"}