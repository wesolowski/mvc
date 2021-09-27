{include file="base/header.tpl" title="Admin - Product's"}
<h1>Admin - Edit Category</h1>
<hr/>
<p><a href="/index.php?area=Admin&page=Category">Back to Category's</a></p>
<p>{$categoryDTO->name}</p>
<form action="index.php?area=Admin&page=CategoryDetail&categoryId={$categoryDTO->id}" method="post">
    <p><input type="text" name="updateName" value="{$updateName}"></p>
    <p>
        <input type="submit" name="updateCategory" value="Update">
        <input type="submit" name="deleteCategory" value="Delete">
    </p>
</form>
<p>{$error['category']|default: ''}</p>
<p>Products</p>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Product</th>
        <th>Price</th>
        <th>Description</th>
        <th></th>
    </tr>
    {foreach from=$productDTOList item=productDTO}
        <tr>
            <td>{$productDTO->id}</td>
            <td>{$productDTO->name}</td>
            <td>{$productDTO->price} €</td>
            <td>{$productDTO->description}</td>
            <td><a href="/index.php?area=Admin&page=ProductDetail&categoryId={$categoryDTO->id}&productId={$productDTO->id}">Update/Delete</a></td>
        </tr>
    {/foreach}
    <form action="index.php?area=Admin&page=CategoryDetail&categoryId={$categoryDTO->id}" method="post">
        <tr>
            <td></td>
            <td>
                <select name="selectProduct">
                    {foreach from=$productDTOListExcludeCategory item=productDTO}
                        <option value="{$productDTO->id}">{$productDTO->name}</option>
                    {/foreach}
                </select>
            </td>
            <td></td>
            <td><input type="submit" value="Add" name="addProduct"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="text" name="create[name]" placeholder="Product" value="{$createProduct.name|default: ''}" </td>
            <td><input type="text" name="create[price]" placeholder="00.00 €" value="{$createProduct.price|default: ''}" </td></td>
            <td><input type="text" name="create[description]" placeholder="Description" value="{$createProduct.description|default: ''}" </td>
            <td><input type="submit" value="Create" name="createProduct"></td>
        </tr>
    </form>
</table>
<p>{$error['product']|default: ''}</p>

{include file="base/footer.tpl"}