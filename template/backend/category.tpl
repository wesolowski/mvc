{include file="base/header.tpl" title="Admin - Product's"}
<h1>Admin - Category's</h1>
<hr/>
<p><a href="/index.php?page=a$Home">Back to Admin Home</a></p>
<p>Category's</p>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Category</th>
        <th></th>
    </tr>
    {foreach from=$categoryList item=category}
        <tr>
            <td>{$category->id}</td>
            <td>{$category->categoryname}</td>
            <td><a href="/index.php?page=ap$EditCategory&category={$category->id}${$category->categoryname}">Update/Delete</a></td>
        </tr>
    {/foreach}
    <tr>
        <form action="/index.php?page=ac$Category" method="post">
            <td></td>
            <td><input type="text" name="newCategoryName" placeholder="Category" value="{$newCategoryName|default: ''}" </td>
            <td><input type="submit" value="Create" name="createCategory"></td>
        </form>
    </tr>
</table>
<p>{$error|default: ''}</p>
{include file="base/footer.tpl"}