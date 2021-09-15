{include file="base/header.tpl" title="Backend Login"}
<h1>Backend Login</h1>
<hr/>
<form action="index.php?area=Admin&page=Login" method="post">
    <table>
        <tr>
            <td>Username</td>
            <td><input type="text" name="username" value="{$username|default:''}" placeholder="maxmustermann" /></td>
        </tr>
        <tr>
            <td>Passwort</td>
            <td><input type="password" name="password" placeholder="123456789" /></td>
        </tr>
        <tr>
            <td><input type="submit" name="login" value="login" /></td>
            <td><input type="reset" /></td>
        </tr>
    </table>
</form>
{if !empty($errors)}
    <h3>Errors:</h3>
    <ul>
        {foreach from=$errors|default:null item=error}
            <li>{$error}</li>
        {/foreach}
    </ul>
{/if}
{include file="base/footer.tpl"}