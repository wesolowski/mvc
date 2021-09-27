{include file="base/header.tpl" title="ProductDetail"}
<h1>Detail</h1>
<hr/>
<a href="../index.php?area=Consumer&page=Product&categoryId={$categoryDTO->id}">Back to {$categoryDTO->name}</a>
<p>{$productDTO->name}</p>
<ul>
    <li>ID: {$productDTO->id}</li>
    <li>Product: {$productDTO->name}</li>
    <li>Price: {$productDTO->price} €</li>
    <li>Description: {$productDTO->description}</li>
</ul>
{include file="base/footer.tpl"}