{include file="base/header.tpl" title="Detail"}
<h1>Detail</h1>
<hr/>
<a href="../index.php?page=Home">Back to list</a>
<p>{$product->productname}</p>
<ul>
    <li>ID: {$product->id}</li>
    <li>Product: {$product->productname}</li>
    <li>Description: {$product->description}</li>
</ul>
{include file="base/footer.tpl"}